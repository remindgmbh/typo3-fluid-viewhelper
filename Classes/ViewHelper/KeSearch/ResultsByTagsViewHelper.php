<?php

declare(strict_types=1);

namespace Remind\RmndViewHelper\ViewHelper\KeSearch;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

use function count;
use function explode;
use function is_array;
use function str_replace;

/**
 * ResultsByTagsViewHelper
 */
class ResultsByTagsViewHelper extends AbstractViewHelper
{
    /**
     * @var string
     */
    public const ARGUMENT_RESULT = 'result';

    /**
     * @var string
     */
    public const FIELD_TAGS = 'tags';

    /**
     * @var string
     */
    public const ARGUMENT_AS = 'as';

    /**
     * @var string
     */
    public const ARGUMENT_LIMIT = 'limit';

    /**
     * Register arguments.
     *
     * @return void
     */
    public function initializeArguments(): void
    {
        parent::initializeArguments();

        $this->registerArgument(self::ARGUMENT_RESULT, 'array', 'Result');
        $this->registerArgument(self::ARGUMENT_AS, 'string', 'Name of the variable to assign to', true);
        $this->registerArgument(self::ARGUMENT_LIMIT, 'int', 'Limit for elements per tag', false, 0);
    }

    /**
     *
     * @return string
     */
    public function render(): string
    {
        $result = $this->arguments[self::ARGUMENT_RESULT] ?? [];
        $as = $this->arguments[self::ARGUMENT_AS] ?? '';
        $limit = $this->getLimit();

        if (empty($result) || empty($as)) {
            return '';
        }

        $splittedByTags = [];

        foreach ($result as $entry) {
            $tags = $this->getTags($entry);

            foreach ($tags as $tagname) {
                if (!is_array($splittedByTags[$tagname])) {
                    $splittedByTags[$tagname] = [];
                }

                if (count($splittedByTags[$tagname]) >= $limit) {
                    continue;
                }

                $splittedByTags[$tagname][] = $entry;
            }
        }

        $this->templateVariableContainer->add($as, $splittedByTags);

        return '';
    }

    /**
     *
     * @return int
     */
    protected function getLimit(): int
    {
        $limit = 99999;
        if (!empty($this->arguments[self::ARGUMENT_LIMIT])) {
            $limitArgument = (int) $this->arguments[self::ARGUMENT_LIMIT];
            if ($limitArgument > 0) {
                $limit = $limitArgument;
            }
        }

        return $limit;
    }

    /**
     *
     * @param array $row
     * @return array
     */
    protected function getTags(array $row): array
    {
        if (empty($row[self::FIELD_TAGS])) {
            return [];
        }

        $tagField = str_replace([' ', '#'], '', $row[self::FIELD_TAGS]);

        if (empty($tagField)) {
            return [];
        }

        $tags = explode(',', $tagField);

        return $tags;
    }
}
