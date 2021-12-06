<?php

declare(strict_types=1);

namespace Remind\RmndViewHelper\ViewHelper;

use function is_array;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Description of AdditionalParamsViewHelper
 */
class AdditionalParamsViewHelper extends AbstractViewHelper
{
    /**
     * @var string
     */
    public const ARGUMENT_PARAMETER = 'parameter';

    /**
     * @var string
     */
    public const ARGUMENT_AS = 'as';

    /**
     * Register arguments here.
     *
     * @return void
     */
    public function initializeArguments(): void
    {
        parent::initializeArguments();

        $this->registerArgument(self::ARGUMENT_PARAMETER, 'array', 'Some parameter', false);
        $this->registerArgument(self::ARGUMENT_AS, 'string', 'Name of the variable to assign to', true);
    }

    /**
     *
     * @return string
     */
    public function render(): string
    {
        /* Get the description argument or the fallback array */
        $parameter = $this->arguments[self::ARGUMENT_PARAMETER] ?? [];
        $as = (string) $this->arguments[self::ARGUMENT_AS] ?? '';

        /* Cannot assign variable without a name */
        if (empty($as)) {
            return '';
        }

        /*
         * The parameter should be typesafe when returned.
         * TYPO3 can mangle the type of the argument even when it is registered
         * with a definitive type. In case the argument is NULL or something
         * this will reset to the expected return type.
         */
        if (!is_array($parameter)) {
            $parameter = [];
        }

        $this->templateVariableContainer->add($as, $parameter);

        return '';
    }
}
