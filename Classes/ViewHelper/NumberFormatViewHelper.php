<?php

declare(strict_types=1);

namespace Remind\RmndViewHelper\ViewHelper;

use Closure;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3\CMS\Fluid\ViewHelpers\Format\NumberViewHelper;

/**
 *
 */
class NumberFormatViewHelper extends NumberViewHelper
{
    /**
     * Format the numeric value as a number with grouped thousands, decimal point and
     * precision.
     *
     *
     * @param array $arguments
     * @param Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     *
     * @return string The formatted number
     */
    public static function renderStatic(
        array $arguments,
        Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): string {
        /* Copy of parent implementation */
        $decimals = $arguments['decimals'];
        $decimalSeparator = $arguments['decimalSeparator'];
        $thousandsSeparator = $arguments['thousandsSeparator'];
        $stringToFormat = $renderChildrenClosure();

        /* Generate the format with the desired decimals */
        $format = '%.' . $decimals . 'f';

        /* By casting to int the decimal value is reset to zero */
        $value = sprintf($format, (int) $stringToFormat);

        return number_format($value, $decimals, $decimalSeparator, $thousandsSeparator);
    }
}
