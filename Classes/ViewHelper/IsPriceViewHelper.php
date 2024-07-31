<?php

declare(strict_types=1);

namespace Remind\RmndViewHelper\ViewHelper;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

use function preg_match;

/**
 * Description of IsPriceViewHelper
 * Is the $string a number like "00.000,00 €"
 *
 * @todo maybe i18n and l10n for this?
 */
class IsPriceViewHelper extends AbstractViewHelper
{
    /**
     * Name of the argument string.
     * @var string
     */
    public const ARGUMENT_STRING = 'string';

    /**
     * Register arguments.
     *
     * @return void
     */
    public function initializeArguments(): void
    {
        parent::initializeArguments();

        $this->registerArgument(self::ARGUMENT_STRING, 'string', 'A string');
    }

    /**
     * Render the viewhelper.
     *
     * @return string
     */
    public function render(): string
    {
        $value = $this->arguments[self::ARGUMENT_STRING] ?? '';

        return preg_match('/(\.?[0-9]{1,3})+\,[0-9]{2} €/', $value) === 1 ? '1' : '0';
    }
}
