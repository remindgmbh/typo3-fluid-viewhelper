<?php

declare(strict_types=1);

namespace Remind\RmndViewHelper\ViewHelper;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

use function sprintf;

/**
 * RobotsTagViewHelper
 */
class RobotsTagViewHelper extends AbstractViewHelper
{
    /**
     * @var string
     */
    public const ARGUMENT_INDEX_PARAM = 'indexParam';

    /**
     * @var string
     */
    public const ARGUMENT_FOLLOW_PARAM = 'followParam';

    /**
     * @var string
     */
    protected const ARGUMENT_EXT_KEY = 'extKey';

    /**
     * @var string
     */
    public const ROBOTS_TAG_KEY = 'robots';

    /**
     * @var string
     */
    public const ROBOTS_PARAM_INDEX = 'index';

    /**
     * @var string
     */
    public const ROBOTS_PARAM_FOLLOW = 'follow';

    /**
     * @var string
     */
    public const ROBOTS_PARAM_NOINDEX = 'noindex';

    /**
     * @var string
     */
    public const ROBOTS_PARAM_NOFOLLOW = 'nofollow';

    /**
     *
     * @return void
     */
    public function initializeArguments(): void
    {
        parent::initializeArguments();

        $this->registerArgument(self::ARGUMENT_INDEX_PARAM, 'string', 'Index parameter');
        $this->registerArgument(self::ARGUMENT_FOLLOW_PARAM, 'string', 'Follow parameter');
        $this->registerArgument(self::ARGUMENT_EXT_KEY, 'string', 'Extension key (my_extension)');
    }

    /**
     * Renders robots tag.
     *
     * @return string
     */
    public function render(): string
    {
        $index = $this->getIndexParam();
        $follow = $this->getFollowParam();
        $key = $this->arguments[self::ARGUMENT_EXT_KEY] ?? '';

        if ($key === '') {
            return '';
        }

        $GLOBALS['TSFE']->additionalHeaderData[$key]
            = sprintf('<meta name="%s" content="%s, %s"/>', self::ROBOTS_TAG_KEY, $index, $follow);

        return '';
    }

    /**
     * Returns index parameter.
     *
     * @return string
     */
    public function getIndexParam(): string
    {
        /* Get index argument */
        $index = $this->arguments[self::ARGUMENT_INDEX_PARAM] ?? '';

        /* Return value by argument */
        return $index === self::ROBOTS_PARAM_INDEX ? self::ROBOTS_PARAM_INDEX : self::ROBOTS_PARAM_NOINDEX;
    }

    /**
     * Returns follow parameter.
     *
     * @return string
     */
    public function getFollowParam(): string
    {
        /* Get follow argument */
        $follow = $this->arguments[self::ARGUMENT_FOLLOW_PARAM] ?? '';

        /* Return value by argument */
        return $follow === self::ROBOTS_PARAM_FOLLOW ? self::ROBOTS_PARAM_FOLLOW : self::ROBOTS_PARAM_NOFOLLOW;
    }
}
