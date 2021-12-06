<?php

declare(strict_types=1);

namespace Remind\RmndViewHelper\ViewHelper;

/**
 * Description of HasNotIpAddressViewHelper
 */
class HasNotIpAddressViewHelper extends HasIpAddressViewHelper
{
    /**
     * @param array $ips
     * @return string
     */
    public function render(): string
    {
        return parent::render() === '0' ? '1' : '0';
    }
}
