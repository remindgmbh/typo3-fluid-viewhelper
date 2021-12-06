<?php

declare(strict_types=1);

namespace Remind\RmndViewHelper\ViewHelper;

use Remind\RmndUtil\Url\StringConverter;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Makes an input string url compatible.
 */
class UrlizeViewHelper extends AbstractViewHelper
{
    /**
     * @var string
     */
    public const ARGUMENT_INPUT = 'input';

    /**
     * Register arguments.
     *
     * @return void
     */
    public function initializeArguments(): void
    {
        parent::initializeArguments();

        $this->registerArgument(self::ARGUMENT_INPUT, 'string', 'Some input string');
    }

    /**
     * Render the viewhelper.
     *
     * @return string
     */
    public function render(): string
    {
        /* Get input or default */
        $input = $this->arguments[self::ARGUMENT_INPUT] ?? '';

        $converter = new StringConverter();

        return $converter->urlize($input);
    }
}
