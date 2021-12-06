<?php

declare(strict_types=1);

namespace Remind\RmndViewHelper\ViewHelper;

use Remind\RmndUtil\Helper\YouTubeHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Provides a fluid ViewHelper that parses a YouTube URL and returns
 * the video id.
 */
class YouTubeIdViewHelper extends AbstractViewHelper
{
    /**
     * @var string
     */
    public const ARGUMENT_URL = 'url';

    /**
     * Initialize arguments.
     *
     * @return void
     */
    public function initializeArguments(): void
    {
        parent::initializeArguments();

        $this->registerArgument(self::ARGUMENT_URL, 'string', 'A youtube url');
    }

    /**
     * Renders the ViewHelper.
     *
     * @return string
     */
    public function render(): string
    {
        /* Read url from viewhelper argument if set */
        $data = $this->arguments[self::ARGUMENT_URL] ?? '';

        /* If the url is empty */
        if (empty($data)) {
            /* Parse the tag content and overwrite the parameter */
            $data = $this->renderChildren();
        }

        $youtube = new YouTubeHelper();

        return $youtube->parseVideoId($data);
    }
}
