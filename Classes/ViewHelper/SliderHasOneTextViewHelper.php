<?php

declare(strict_types=1);

namespace Remind\RmndViewHelper\ViewHelper;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class SliderHasOneTextViewHelper extends AbstractViewHelper
{
    /**
     * Argument name for slides.
     * @var array
     */
    public const ARGUMENT_SLIDES = 'slides';

    /**
     * Register arguments.
     *
     * @return void
     */
    public function initializeArguments(): void
    {
        parent::initializeArguments();

        $this->registerArgument(self::ARGUMENT_SLIDES, 'array', 'The slides of the slider');
    }

    /**
     * Return headline of slide if only one slide has a headline
     *
     * @return string
     */
    public function render(): string
    {
        /* Get argument value */
        $slides = $this->arguments[self::ARGUMENT_SLIDES] ?? [];
        $textSlides = [];

        foreach ($slides as $item) {
            /* If slide has a headline add it to array */
            if ($item['slide']['headline'] != '') {
                $textSlides[] = $item['slide']['headline'];
            }
        }

        /* If one headline in array return headline */
        if (count($textSlides) == 1) {
            return $textSlides[0];
        }

        /* Else return none */
        return '0';
    }
}
