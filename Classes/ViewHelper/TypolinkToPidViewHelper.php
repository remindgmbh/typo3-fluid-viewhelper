<?php

declare(strict_types=1);

namespace Remind\RmndViewHelper\ViewHelper;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Extract the pid from a typolink parameter
 */
class TypolinkToPidViewHelper extends AbstractViewHelper
{
    /**
     * @var string
     */
    public const ARGUMENT_TYPOLINK_PARAMETER = 'parameter';

    /**
     * Register arguments here.
     *
     * @return void
     */
    public function initializeArguments(): void
    {
        parent::initializeArguments();

        $this->registerArgument(self::ARGUMENT_TYPOLINK_PARAMETER, 'string', 'Typolink parameter', true);
    }

    /**
     *
     * @return string
     */
    public function render(): string
    {
        /* Get the description argument or the fallback array */
        $parameter = $this->arguments[self::ARGUMENT_TYPOLINK_PARAMETER] ?? [];

        /* Remove page uid part from typolink */
        $strippedParameter = \str_replace('t3://page?uid=', '', $parameter);

        /* Explode to extract only first url part */
        $urlParts = \explode(' ', $strippedParameter);

        /* First part is always the page */
        $uidPart = $urlParts[0];

        /* When first part is not numeric (file, external link, ...) */
        if (!\is_numeric($uidPart)) {
            return '0';
        }

        /* Return the pid */
        return $uidPart;
    }
}
