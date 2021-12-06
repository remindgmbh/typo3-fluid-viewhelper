<?php

declare(strict_types=1);

namespace Remind\RmndViewHelper\ViewHelper;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

use function explode;

/**
 * TableBoldViewHelper
 */
class TableBoldViewHelper extends AbstractViewHelper
{
    /**
     * @var string
     */
    public const ARGUMENT_BOLD_ROWS = 'boldRows';

    /**
     * @var string
     */
    public const ARGUMENT_ROW_INDEX = 'rowIndex';

    /**
     * Register arguments.
     *
     * @return void
     */
    public function initializeArguments(): void
    {
        parent::initializeArguments();

        $this->registerArgument(self::ARGUMENT_BOLD_ROWS, 'string', 'Bold rows');
        $this->registerArgument(self::ARGUMENT_ROW_INDEX, 'int', 'Row index');
    }

    /**
     * Check if given table row is marked as bold.
     *
     * @return string
     */
    public function render(): string
    {
        /* Get argument values */
        $boldRows = $this->arguments[self::ARGUMENT_BOLD_ROWS] ?? '';
        $rowIndex = $this->arguments[self::ARGUMENT_ROW_INDEX] ?? 0;

        /* row numbers to array */
        $rowNums = explode(',', $boldRows);

        /* iterate through row numbers */
        foreach ($rowNums as $rowNum) {
            /* parse to int so the following condition can be met */
            $rowNum = (int) $rowNum;

            /* if row num equals current index (+1).. */
            if ($rowNum === ($rowIndex + 1)) {
                /* ..row is bold */
                return '1';
            }
        }

        return '0';
    }
}
