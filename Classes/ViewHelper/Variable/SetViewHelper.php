<?php

declare(strict_types=1);

namespace Remind\RmndViewHelper\ViewHelper\Variable;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Description of SetViewHelper
 */
class SetViewHelper extends AbstractViewHelper
{
    /**
     * The name argument.
     * @var string
     */
    protected const ARGUMENT_NAME = 'name';

    /**
     * The value argument.
     * @var string
     */
    protected const ARGUMENT_VALUE = 'value';

    /**
     * Register arguments.
     *
     * @return void
     */
    public function initializeArguments(): void
    {
        parent::initializeArguments();

        $this->registerArgument(self::ARGUMENT_NAME, 'string', 'A variable name', true);
        $this->registerArgument(self::ARGUMENT_VALUE, 'mixed', 'A value', false, '');
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

        $value = $this->arguments[self::ARGUMENT_VALUE] ?? '';

        $this->templateVariableContainer->add($name, $value);

        return '';
    }
}
