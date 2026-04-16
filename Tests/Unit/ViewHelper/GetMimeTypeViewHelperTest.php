<?php

declare(strict_types=1);

namespace Remind\RmndViewHelper\Tests\Unit\ViewHelper;

use Remind\RmndViewHelper\ViewHelper\GetMimeTypeViewHelper;
use TYPO3\TestingFramework\Fluid\Unit\ViewHelpers\ViewHelperBaseTestcase;

/**
 * Description of GetMimeTypeViewHelperTest
 */
class GetMimeTypeViewHelperTest extends ViewHelperBaseTestcase
{
    protected GetMimeTypeViewHelper|MockObject $viewHelper = null;

    public function testMockRenderEmptyFileArgumentReturnsEmptyString(): void
    {
        $this->viewHelper
            ->expects($this->once())
            ->method('render')
            ->willReturn('');

        $this->assertSame('', $this->viewHelper->render());
    }

    public function testMockRenderFileArgumentReturnsCorrectMimeType(): void
    {
        $this->viewHelper
            ->expects($this->once())
            ->method('render')
            ->willReturn('text/x-php');

        $this->assertSame('text/x-php', $this->viewHelper->render());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->viewHelper = $this->getMockBuilder(GetMimeTypeViewHelper::class)
            ->setMethods(['render'])
            ->getMock();

        $this->injectDependenciesIntoViewHelper($this->viewHelper);

        $this->viewHelper->initializeArguments();
    }
}
