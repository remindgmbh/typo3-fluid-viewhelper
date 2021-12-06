<?php

declare(strict_types=1);

namespace Remind\RmndViewHelper\ViewHelper;

use Remind\RmndUtil\Helper\IpAddressHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Description of HasIpAddressViewHelper
 */
class HasIpAddressViewHelper extends AbstractViewHelper
{
    /**
     * @var string
     */
    public const ARGUMENT_IPS = 'ips';

    /**
     * Initialize arguments.
     *
     * @return void
     */
    public function initializeArguments(): void
    {
        parent::initializeArguments();

        $this->registerArgument(self::ARGUMENT_IPS, 'array', 'A list of ips');
    }

    /**
     *
     * @return string
     */
    public function render(): string
    {
        if (empty($this->arguments[self::ARGUMENT_IPS])) {
            return '0';
        }

        $ips = $this->arguments[self::ARGUMENT_IPS];

        $ipHelper = new IpAddressHelper();

        $ip = $ipHelper->getRemoteAddr();

        if ($ip === '') {
            return '0';
        }

        foreach ($ips as $address) {
            if ($address === $ip) {
                return '1';
            }
        }

        return '0';
    }
}
