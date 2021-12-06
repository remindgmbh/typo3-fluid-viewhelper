<?php

declare(strict_types=1);

namespace Remind\RmndViewHelper\ViewHelper;

use function mime_content_type;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * This viewhelper tries to get the mime type of a given file.
 */
class GetMimeTypeViewHelper extends AbstractViewHelper
{
    /**
     * @var string
     */
    public const ARGUMENT_FILE = 'file';

    /**
     * Initialize arguments.
     *
     * @return void
     */
    public function initializeArguments(): void
    {
        parent::initializeArguments();

        $this->registerArgument(self::ARGUMENT_FILE, 'string', 'Path to a file');
    }

    /**
     * Renders the viewhelper.
     *
     * @return string Either the mime type or an empty string
     */
    public function render(): string
    {
        if (empty($this->arguments[self::ARGUMENT_FILE])) {
            return '';
        }

        $file = $this->arguments[self::ARGUMENT_FILE];

        /* Return the result */
        return mime_content_type($file);
    }
}
