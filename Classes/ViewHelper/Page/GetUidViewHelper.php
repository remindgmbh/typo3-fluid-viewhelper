<?php

declare(strict_types=1);

namespace Remind\RmndViewHelper\ViewHelper\Page;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Get uid of current page from globals
 */
class GetUidViewHelper extends AbstractViewHelper
{
    /**
     * Get uid of current page from globals
     * @return int uid
     */
    public function render(): string
    {
        return (string) $GLOBALS['TSFE']->id ?? '0';
    }
}
