<?php

declare(strict_types=1);

namespace Remind\RmndViewHelper\ViewHelper;

use function time;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Description of ContentTimerViewHelper
 */
class ContentTimerViewHelper extends AbstractViewHelper
{
    /**
     * Argument name for start.
     * @var string
     */
    public const ARGUMENT_START = 'start';

    /**
     * Argument name for end.
     * @var string
     */
    public const ARGUMENT_END = 'end';

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

        $this->registerArgument(self::ARGUMENT_START, 'int', 'Start unix timestamp');
        $this->registerArgument(self::ARGUMENT_END, 'int', 'End unix timestamp');
    }

    /**
     * Render the viewhelper.
     *
     * @return string
     */
    public function render(): string
    {
        /* Get argument values or defaults */
        $start = (int) $this->arguments[self::ARGUMENT_START] ?? 0;
        $end = (int) $this->arguments[self::ARGUMENT_END] ?? 0;

        /* Now is the time */
        $now = time();

        /* Prepare output */
        $html = '';

        /* Check conditions */
        if ($start !== 0 && $start > $now) {
            $html = '';
        } elseif ($end !== 0 && $end < $now) {
            $html = '';
        } else {
            $html = $this->renderChildren();
        }

        return $html;
    }
}
