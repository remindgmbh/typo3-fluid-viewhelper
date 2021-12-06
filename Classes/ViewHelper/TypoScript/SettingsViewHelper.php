<?php

declare(strict_types=1);

namespace Remind\RmndViewHelper\ViewHelper\TypoScript;

use Remind\RmndUtil\Traits\ConfigurationManagerInjectionTrait;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Loads and merges the typoscript settings from the current context
 * and assigns it to the view.
 *
 * Why:
 * There was a problem when using ke_search where the pi1 and pi2 plugins
 * were not programmed to have the typoscript settings assigned to the view
 * which prevented stored settings from beeing accessed in the templates.
 */
class SettingsViewHelper extends AbstractViewHelper
{
    use ConfigurationManagerInjectionTrait;

    /**
     * Renders the viewhelper.
     *
     * @return string
     */
    public function render(): string
    {
        /* Get the configured typoscript settings */
        $tsSettings = $this->configurationManager->getConfiguration(
            ConfigurationManager::CONFIGURATION_TYPE_SETTINGS
        );

        /* Get the settings from the view */
        $currentSettings = $this->templateVariableContainer->get('settings') ?? [];

        /* Merge settings */
        $merged = array_merge($currentSettings, $tsSettings);

        /* Re-assign merged settings to view */
        $this->templateVariableContainer->add('settings', $merged);

        return '';
    }
}
