<?php

declare(strict_types=1);

namespace Remind\RmndViewHelper\ViewHelper\Powermail;

use Remind\RmndUtil\Traits\ConfigurationManagerInjectionTrait;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Copy from jh_captcha with alternative recaptcha api include.
 */
class ReCaptchaViewHelper extends AbstractViewHelper
{
    use ConfigurationManagerInjectionTrait;

    /**
     * Name of the target id argument.
     * @var string
     */
    public const ARGUMENT_TARGET_ID = 'targetId';

    /**
     * Name of the field uid argument.
     * @var string
     */
    public const ARGUMENT_FIELD_UID = 'fieldUid';

    /**
     * Variable / index name for TYPO3 additional header data.
     * @var string
     */
    public const KEY_ADDITIONAL_HEADER_DATA = 'rmndJhCaptcha';

    /**
     * As this ViewHelper renders HTML, the output must not be escaped.
     *
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * Register arguments.
     *
     * @return void
     */
    public function initializeArguments(): void
    {
        parent::initializeArguments();

        $this->registerArgument(self::ARGUMENT_TARGET_ID, 'string', 'Target Id');
        $this->registerArgument(self::ARGUMENT_FIELD_UID, 'string', 'Field Uid');
    }

    /**
     *
     * @return string
     */
    public function render(): string
    {
        /* Get values from arguments */
        $targetId = $this->arguments[self::ARGUMENT_TARGET_ID] ?? '';
        $fieldUid = $this->arguments[self::ARGUMENT_FIELD_UID] ?? '';

        /* Get TS settings */
        $settings = $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
            'JhCaptcha'
        );

        /* Get values from TS settings */
        $siteKey = $settings['reCaptcha']['siteKey'];
        $theme = $settings['reCaptcha']['theme'];
        $type = $settings['reCaptcha']['type'];
        $lang = $settings['reCaptcha']['lang'];
        $size = $settings['reCaptcha']['size'];

        /* Recaptcha api script */
        $reCaptchaApi = '<script src="https://www.google.com/recaptcha/api.js?hl='
                . $lang . '" async defer></script>';

        /* Alternative include to original @author m.wegner@remind.de */
        $GLOBALS['TSFE']->additionalHeaderData[self::KEY_ADDITIONAL_HEADER_DATA]
            = $reCaptchaApi;

        /* Set target id if possible */
        if (empty($targetId)) {
            $targetId = 'captchaResponse';
        }

        if ($siteKey) {
            /* Create callback script */
            $callBack = '<script type="text/javascript">var captchaCallback' . $fieldUid . ' = function() { '
                . 'var sourceElement = document.body.querySelectorAll("#powermail_fieldwrap_'
                . $fieldUid
                . ' .g-recaptcha-response"); '
                . 'var targetElement = document.getElementById("' . $targetId . '"); '
                . 'sourceElement = sourceElement[0];'
                . 'if(!targetElement || !sourceElement) {'
                . 'return false;'
                . '}'
                . 'targetElement.value = sourceElement.value; '
                . '}</script>';

            /* Create callback target element */
            $reCaptcha = '<div class="g-recaptcha" data-sitekey="'
                . $siteKey . '" data-theme="' . $theme .
                '" data-callback="captchaCallback' . $fieldUid . '" data-type="'
                . $type . '" data-size="' . $size . '"></div>';

            /* Append elements */
            return $callBack . $reCaptcha;
        }

        return LocalizationUtility::translate('setApiKey', null, null);
    }
}
