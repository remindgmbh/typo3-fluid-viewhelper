<?php

declare(strict_types=1);

namespace Remind\RmndViewHelper\ViewHelper;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\CMS\Core\Core\Environment;

/**
 * Description of PathSiteViewHelper
 */
class PathSiteViewHelper extends AbstractViewHelper
{
    public function render(): string
    {
        return Environment::getPublicPath() . '/';
    }
}
