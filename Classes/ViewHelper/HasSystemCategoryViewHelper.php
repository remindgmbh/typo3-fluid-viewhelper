<?php

declare(strict_types=1);

namespace Remind\RmndViewHelper\ViewHelper;

use Remind\RmndUtil\Helper\SysCategoryHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Provides a fluid ViewHelper that checks if a record has given sys_categories.
 */
class HasSystemCategoryViewHelper extends AbstractViewHelper
{
    /**
     * @var string
     */
    public const ARGUMENT_RECORD_UID = 'recordUid';

    /**
     * @var string
     */
    public const ARGUMENT_TABLE = 'table';

    /**
     * @var string
     */
    public const ARGUMENT_CATEGORIES = 'categories';

    /**
     * Initialize arguments.
     *
     * @return void
     */
    public function initializeArguments(): void
    {
        parent::initializeArguments();

        $this->registerArgument(self::ARGUMENT_RECORD_UID, 'int', '');
        $this->registerArgument(self::ARGUMENT_TABLE, 'string', '');
        $this->registerArgument(self::ARGUMENT_CATEGORIES, 'array', '');
    }

    /**
     * Renders the ViewHelper.
     *
     * Checks if the record with <code>$recordUID</code> in table <code>$table</code>
     * has all of the given sys_category UIDs in <code>$category</code>
     *
     * @return string
     */
    public function render(): string
    {
        $recordUid = $this->arguments[self::ARGUMENT_RECORD_UID];
        $table = $this->arguments[self::ARGUMENT_TABLE];
        $categories = $this->argument[self::ARGUMENT_CATEGORIES];

        /* Use the helper class to handle sys_category things */
        $sys = new SysCategoryHelper($recordUid, $table);

        /* Return the result */
        return (string) $sys->hasCategories($categories);
    }
}
