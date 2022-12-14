<?php

declare(strict_types=1);

namespace Remind\RmndViewHelper\ViewHelper\Variable;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Description of GetViewHelper
 */
class GetViewHelper extends AbstractViewHelper
{
    /**
     * The name argument.
     * @var string
     */
    protected const ARGUMENT_NAME = 'name';

    /**
     * Register arguments.
     *
     * @return void
     */
    public function initializeArguments(): void
    {
        parent::initializeArguments();

        $this->registerArgument(self::ARGUMENT_NAME, 'string', 'A variable name', true);
    }

    /**
     * Render the viewhelper.
     *
     * @return string
     */
    public function render(): string
    {
        $name = $this->arguments[self::ARGUMENT_NAME] ?? '';

        if (empty($name)) {
            return '';
        }

        return (string) $this->templateVariableContainer->get($name);
    }
}
