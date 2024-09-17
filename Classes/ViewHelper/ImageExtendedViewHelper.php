<?php

declare(strict_types=1);

namespace Remind\RmndViewHelper\ViewHelper;

use Exception;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\ProcessedFile;
use TYPO3\CMS\Fluid\ViewHelpers\ImageViewHelper;

use function array_merge;
use function filter_var;

/**
 * ImageExtendedViewHelper
 */
class ImageExtendedViewHelper extends ImageViewHelper
{
    /**
     * @var string
     */
    public const ERROR_STRING = '<!-- # Here would be an image if src wasn\'t empty # -->';

    /**
     * @var string
     */
    public const ARGUMENT_LAZY_MODE = 'lazyMode';

    /**
     * @var string
     */
    public const ARGUMENT_MOBILE_VALUES = 'mobileValues';

    /**
     * @var string
     */
    public const ARGUMENT_LIGHT_BOX_MODE = 'lightBoxMode';

    /**
     * @var string
     */
    public const ARGUMENT_LIGHT_BOX_VALUES = 'lightBoxValues';

    /**
     * @var string
     */
    public const FIELD_CLASSES = 'classes';

    /**
     * @var string
     */
    public const FIELD_TITLE = 'title';

    /**
     * @var string
     */
    public const FIELD_TEXT = 'text';

    /**
     * @var string
     */
    public const FIELD_COPYRIGHT = 'copyright';

    /**
     * @var string
     */
    public const DATA_IMAGE = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB"'
        . 'AAAAAJCAYAAAA7KqwyAAAAD0lEQVQokWNgGAWjgAoAAAJJAAHgPOv0AAAAAElFTkSuQmCC';

    /**
     * As this ViewHelper renders HTML, the output must not be escaped.
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * Register arguments.
     *
     * @return void
     */
    public function initializeArguments(): void
    {
        parent::initializeArguments();

        $this->registerArgument(
            self::ARGUMENT_LAZY_MODE,
            'bool',
            'Render image to data-attribute and render smaller image first'
        );
        $this->registerArgument(
            self::ARGUMENT_LIGHT_BOX_MODE,
            'bool',
            'Render image to data-attribute for lightbox function'
        );
        $this->registerArgument(
            self::ARGUMENT_LIGHT_BOX_VALUES,
            'array',
            'Data attributes for light box text and additional css classes'
        );
        $this->registerArgument(
            self::ARGUMENT_MOBILE_VALUES,
            'array',
            'All attributes again in array for mobile lazy loading'
        );
    }

    /**
     * Resizes a given image (if required) and renders the respective img tag.
     *
     * @todo handle and log exceptions instead of html dumping
     * @todo typecheck parameters
     *
     * @see https://docs.typo3.org/typo3cms/TyposcriptReference/ContentObjects/Image/
     * @return string Rendered tag
     */
    public function render(): string
    {
        /* Mandatory arguments */
        if (empty($this->arguments['src']) && empty($this->arguments['image'])) {
            /* Output html comment to source code for debugging */
            return self::ERROR_STRING;
        }

        /* In case the src is a parsable url */
        if ($this->isRenderDefault()) {
            try {
                return parent::render();
            } catch (Exception $e) {
                return '<!-- # ' . $e->getMessage() . ' # -->';
            }
        }

        /* Get image object */
        try {
            /* @var $image FileInterface */
            $image = $this->imageService->getImage(
                (string) $this->arguments['src'],
                $this->arguments['image'],
                (bool) $this->arguments['treatIdAsReference']
            );
        } catch (Exception $e) {
            /* Dump error as html comment */
            return '<!-- # ' . $e->getMessage() . ' # -->';
        }

        /* This should not happen */
        if (empty($image)) {
            return self::ERROR_STRING;
        }

        $cssClasses = $this->arguments['class'] ?? '';
        $cssClasses .= ' lazyImage';

        /* If light box mode is enabled, then add css class */
        if ($this->arguments[self::ARGUMENT_LIGHT_BOX_MODE]) {
            $cssClasses .= ' lightBoxImage';
        }

        /* Assign css classes */
        $this->tag->addAttribute('class', $cssClasses);

        /* Get preset alternative value */
        $alt = $image->getProperty('alternative');

        /* Don't override values set in template */
        if (empty($this->arguments['alt'])) {
            $this->tag->addAttribute('alt', $alt);
        }

        /* Get preset title value */
        $title = $image->getProperty('title');

        /* Don't override values set in template */
        if (empty($this->arguments['title']) && $title) {
            $this->tag->addAttribute('title', $title);
        }

        /* Set src, srcset and data-srcset */
        $this->addDataSrcset($image);

        /* Set data light box image */
        if ($this->arguments[self::ARGUMENT_LIGHT_BOX_MODE]) {
            $this->addDataLightBoxImage($image);
        }

        /* Render img tag */
        return $this->tag->render();
    }

    /**
     * Check if the default image render method should be used
     *
     * @return bool
     */
    protected function isRenderDefault(): bool
    {
        /* @todo check */
        if (!empty($this->arguments['src']) && filter_var($this->arguments['src'], FILTER_VALIDATE_URL) !== false) {
            return true;
        }

        /* Determine lazy mode */
        $isLazyMode = $this->arguments[self::ARGUMENT_LAZY_MODE] ?? false;

        /* If not lazy or is backend */
        if (!$isLazyMode) {
            return true;
        }

        return false;
    }

    /**
     * Set src, srcset and (if lazy mode is enabled) data-srcset
     *
     * @param FileInterface $image
     * @return void
     */
    public function addDataSrcset(FileInterface $image): void
    {
        /* Get desktop image */
        $desktopImage = $this->processImage($image, $this->arguments);
        $desktopImageUri = $this->getProssedImageUri($desktopImage);
        /* Add it to src set */
        $dataSrcSet = $desktopImageUri . ' 1920w';

        if (!empty($this->arguments[self::ARGUMENT_MOBILE_VALUES])) {
            /* Get mobile config */
            $mobileArgs = array_merge($this->arguments, $this->arguments[self::ARGUMENT_MOBILE_VALUES]);

            /* Get mobile image */
            $mobileImage = $this->processImage($image, $mobileArgs);
            $mobileImageUri = $this->getProssedImageUri($mobileImage);
            /* Add it to src set */
            $dataSrcSet = $mobileImageUri . ' 767w,' . $dataSrcSet;
        }

        /* Set src attribute (important for google) */
        $this->tag->addAttribute('src', $desktopImageUri);
        /* Add width and height attributes */
        $this->tag->addAttribute('width', $desktopImage->getProperty('width'));
        $this->tag->addAttribute('height', $desktopImage->getProperty('height'));

        if ($this->arguments[self::ARGUMENT_LAZY_MODE]) {
            /* Set srcset as data-srcset */
            $this->tag->addAttribute('data-srcset', $dataSrcSet);
            /* Assign data image to srcset */
            $this->tag->addAttribute('srcset', self::DATA_IMAGE);
        } else {
            /* Assign srcset */
            $this->tag->addAttribute('srcset', $dataSrcSet);
        }
    }

    /**
     * Set data light box image (if light box mode is enabled)
     *
     * @param FileInterface $image
     * @return void
     */
    public function addDataLightBoxImage(FileInterface $image): void
    {
        $config = [
            'maxWidth' => '1600',
        ];

        $lightBoxImage = $this->processImage($image, $config);
        $lightBoxImageUri = $this->getProssedImageUri($lightBoxImage);

        /* Set light box image */
        $this->tag->addAttribute('data-lightbox', $lightBoxImageUri);

        /* Add data attribute for light box description */
        if ($this->arguments[self::ARGUMENT_LIGHT_BOX_VALUES]) {
            $title = $this->arguments[self::ARGUMENT_LIGHT_BOX_VALUES][self::FIELD_TITLE];
            if ($title) {
                $this->tag->addAttribute('data-title', $title);
            }

            $text = $this->arguments[self::ARGUMENT_LIGHT_BOX_VALUES][self::FIELD_TEXT];
            if ($text) {
                $this->tag->addAttribute('data-text', $text);
            }

            $copyright = $this->arguments[self::ARGUMENT_LIGHT_BOX_VALUES][self::FIELD_COPYRIGHT];
            if ($copyright) {
                $this->tag->addAttribute('data-copyright', $copyright);
            }
        }
    }

    /**
     * Process image into desired dimensions and returns a image.
     *
     * @param FileInterface $image
     * @param mixed $config
     * @return ProcessedFile
     */
    protected function processImage(FileInterface $image, array $config): ProcessedFile
    {
        $instructions = [
            'width' => $config['width'],
            'height' => $config['height'],
            'minWidth' => $config['minWidth'],
            'minHeight' => $config['minHeight'],
            'maxWidth' => $config['maxWidth'],
            'maxHeight' => $config['maxHeight']
        ];

        return $this->imageService->applyProcessingInstructions($image, $instructions);
    }

    /**
     * Return image uri
     *
     * @param ProcessedFile $processedImage
     * @return string
     */
    protected function getProssedImageUri(ProcessedFile $processedImage): string
    {
        return $this->imageService->getImageUri($processedImage, $this->arguments['absolute']);
    }
}
