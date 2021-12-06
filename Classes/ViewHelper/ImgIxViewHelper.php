<?php

declare(strict_types=1);

namespace Remind\RmndViewHelper\ViewHelper;

use function http_build_query;

use function str_replace;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * This viewhelper converts the given arguments into an imgix service url.
 * The main argument ist the FAL resource id used by TYPO3 when using the
 * andersundsehr/aus-driver-amazon-s3 to access amazon bucket files and
 * converts them into a given imgix url resouce.
 */
class ImgIxViewHelper extends AbstractViewHelper
{
    // <editor-fold defaultstate="collapsed" desc="Argument constants">

    /**
     * The image uri argument name.
     * @var string
     */
    public const ARGUMENT_IMG_URI = 'imgUrl';

    /**
     * The complete amazon s3 bucket url argument name.
     * @var string
     */
    public const ARGUMENT_BUCKET_URL = 'bucketUrl';

    /**
     * The desired width argument name.
     * @var string
     */
    public const ARGUMENT_WIDTH = 'width';

    /**
     * The desired height argument name.
     * @var string
     */
    public const ARGUMENT_HEIGHT = 'height';

    /**
     * Enable focal point crosshair overlay argument name.
     * @var string
     */
    public const ARGUMENT_FPDEBUG = 'fpdebug';

    /**
     * Focal point x coordinate argument name.
     * @var string
     */
    public const ARGUMENT_FPX = 'fpx';

    /**
     * Focal point y coordinate argument name.
     * @var string
     */
    public const ARGUMENT_FPY = 'fpy';

    /**
     * Focal point zoom value argument name.
     * @var string
     */
    public const ARGUMENT_FPZ = 'fpz';

    /**
     * The custom imgix service url argument name.
     * @var string
     */
    public const ARGUMENT_IMGIX_URL = 'imgixUrl';

    /**
     * Automatic optimization parameters argument name.
     * @var string
     */
    public const ARGUMENT_AUTO = 'auto';

    /**
     * Image quality argument name.
     * @var string
     */
    public const ARGUMENT_QUALITY = 'quality';

    /**
     * Image output format argument name.
     * @var string
     */
    public const ARGUMENT_FORMAT = 'format';

    /**
     * Image fitting method argument name.
     * @var string
     */
    public const ARGUMENT_FIT = 'crop';

    /**
     * Image cropping technique argument name.
     * @var string
     */
    public const ARGUMENT_CROP = 'focalpoint';

    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Default values">

    /**
     * Default focal point zoom value.
     * @var int
     */
    public const DEFAULT_ZOOM = 1;

    /**
     * Default image quality.
     * @var int
     */
    public const DEFAULT_QUALITY = 60;

    /**
     * Default image fitting technique.
     * @var string
     */
    public const DEFAULT_FIT = 'crop';

    /**
     * Default cropping method.
     * @var string
     */
    public const DEFAULT_CROP = 'focalpoint';

    /**
     * Default automatic optimization parameters.
     * @var string
     */
    public const DEFAULT_AUTO = 'format,compress';

    // </editor-fold>

    /**
     * Contains all url parameters used to build the imgix url.
     *
     * @var array
     */
    protected array $params = [];

    /**
     * Register arguments here.
     *
     * @return void
     */
    public function initializeArguments(): void
    {
        $this->registerArgument(self::ARGUMENT_IMG_URI, 'string', 'TYPO3 FAL image ressource', true);
        $this->registerArgument(self::ARGUMENT_BUCKET_URL, 'string', 'Amazon bucket url', true);
        $this->registerArgument(self::ARGUMENT_IMGIX_URL, 'string', 'ImgIx url', true);
        $this->registerArgument(self::ARGUMENT_WIDTH, 'int', 'Get the render widht for breakpoint', true);
        $this->registerArgument(self::ARGUMENT_HEIGHT, 'int', 'Get the render height for breakpoint', true);
        $this->registerArgument(self::ARGUMENT_FPDEBUG, 'bool', 'Enable focalpoint crosshair overlay', false, false);
        $this->registerArgument(self::ARGUMENT_FPX, 'string', 'Focalpoint x coordinate', false, 0);
        $this->registerArgument(self::ARGUMENT_FPY, 'string', 'Focalpoint y coordinate', false, 0);
        $this->registerArgument(self::ARGUMENT_FPZ, 'string', 'Focalpoint zoom (1-10)', false, self::DEFAULT_ZOOM);
        $this->registerArgument(self::ARGUMENT_AUTO, 'string', 'Automatic optimization', false, self::DEFAULT_AUTO);
        $this->registerArgument(self::ARGUMENT_QUALITY, 'int', 'Output quality (0-100)', false, self::DEFAULT_QUALITY);
        $this->registerArgument(self::ARGUMENT_FORMAT, 'string', 'Output image format', false, '');
        $this->registerArgument(self::ARGUMENT_FIT, 'string', 'Image cropping technique', false, self::DEFAULT_FIT);
        $this->registerArgument(self::ARGUMENT_CROP, 'string', 'Specify cropping mode', false, self::DEFAULT_CROP);
    }

    /**
     * Get auto, fit and crop arguments and apply values to url parameters.
     *
     * @return void
     */
    protected function applyAutoOptimization(): void
    {
        $auto = $this->arguments[self::ARGUMENT_AUTO] ?? self::DEFAULT_AUTO;
        $fit = $this->arguments[self::ARGUMENT_FIT] ?? self::DEFAULT_FIT;
        $crop = $this->arguments[self::ARGUMENT_CROP] ?? self::DEFAULT_CROP;

        $this->params[self::ARGUMENT_AUTO] = $auto;
        $this->arguments[self::ARGUMENT_FIT] = $fit;
        $this->arguments[self::ARGUMENT_CROP] = $crop;
    }

    /**
     * Convert width and height arguments to url parameters.
     *
     * @return void
     */
    protected function applyDimensions(): void
    {
        $width = $this->arguments[self::ARGUMENT_WIDTH] ?? 0;
        $height = $this->arguments[self::ARGUMENT_HEIGHT] ?? 0;

        $params['w'] = $width;
        $params['h'] = $height;
    }

    /**
     * Apply the quality value parameter to the url parameters.
     *
     * @return void
     */
    protected function applyQuality(): void
    {
        $quality = $this->arguments[self::ARGUMENT_QUALITY] ?? self::DEFAULT_QUALITY;

        $this->params['q'] = $quality;
    }

    /**
     * Check focal point values and apply to url parameters.
     *
     * @return void
     */
    protected function applyFocalpointSettings(): void
    {
        $isDebug = $this->arguments[self::ARGUMENT_FPDEBUG] ?? false;
        $fpx = $this->arguments[self::ARGUMENT_FPX] ?? 0;
        $fpy = $this->arguments[self::ARGUMENT_FPY] ?? 0;
        $fpz = $this->arguments[self::ARGUMENT_FPZ] ?? self::DEFAULT_ZOOM;

        /* If both coordinates are given */
        $isManual = ($fpx > 0) && ($fpy > 0);

        /* Set manual coordinates */
        if ($isManual) {
            $this->params['fp-x'] = $fpx;
            $this->params['fp-y'] = $fpy;
            $this->params['fp-z'] = $fpz;
        }

        /* Enable debug */
        if ($isDebug) {
            $this->params['fp-debug'] = 'true';
        }
    }

    /**
     * Apply a given image output format if available.
     *
     * @return void
     */
    protected function applyOutputFormat(): void
    {
        $format = $this->arguments[self::ARGUMENT_FORMAT] ?? '';

        if ($format) {
            $this->params['fm'] = $format;
        }
    }

    /**
     * Render the viewhelper.
     *
     * @return string
     */
    public function render(): string
    {
        /* The image resource identifier used by TYPO3 */
        $falImage = $this->arguments[self::ARGUMENT_IMG_URI] ?? '';

        /* The complete S3 bucket url used for this image */
        $bucketUrl = $this->arguments[self::ARGUMENT_BUCKET_URL] ?? '';

        /* The imgix url */
        $imgIxUrl = $this->arguments[self::ARGUMENT_IMGIX_URL] ?? '';

        /* Substitute the  */
        $url = str_replace($bucketUrl, $imgIxUrl, $falImage);

        /* Convert arguments to url parameters */
        $this->applyAutoOptimization();
        $this->applyDimensions();
        $this->applyQuality();
        $this->applyFocalpointSettings();

        /* Return the imgix url with parsed query parameters */
        return $url . '?' . http_build_query($this->params);
    }
}
