<?php

declare(strict_types=1);

namespace Remind\RmndViewHelper\ViewHelper;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

use function str_replace;
use function strpos;

/**
 * Description of PhoneNumberViewHelper
 */
class PhoneNumberViewHelper extends AbstractViewHelper
{
    /**
     * @var string
     */
    public const ARGUMENT_NUMBER = 'number';

    /**
     * Register all arguments.
     *
     * @return void
     */
    public function initializeArguments(): void
    {
        parent::initializeArguments();

        $this->registerArgument(self::ARGUMENT_NUMBER, 'string', 'A phone number');
    }

    /**
     * Performs a basic replace of spaces and slashes and returns the result.
     *
     * @return string
     */
    public function render(): string
    {
        /* Get the number argument */
        $number = $this->arguments[self::ARGUMENT_NUMBER] ?? '';

        $pos = strpos($number, '/');

        if ($pos === false) {
            return $number;
        }

        $numberReplaced = str_replace([' ', '/'], ['',' '], $number);

        return $numberReplaced;
    }
}
