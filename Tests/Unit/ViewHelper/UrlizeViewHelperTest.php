<?php

declare(strict_types=1);

namespace Remind\RmndViewHelper\Tests\Unit\ViewHelper;

use Remind\RmndViewHelper\ViewHelper\UrlizeViewHelper;
use TYPO3\TestingFramework\Fluid\Unit\ViewHelpers\ViewHelperBaseTestcase;

/**
 * Description of UrlizeViewHelperTest
 */
class UrlizeViewHelperTest extends ViewHelperBaseTestcase
{
    protected GetMimeTypeViewHelper|MockObject $viewHelper = null;

    public function testMockRenderEmptyArgumentReturnsEmptyString(): void
    {
        $this->viewHelper
            ->expects($this->once())
            ->method('render')
            ->willReturn('');

        $this->assertSame('', $this->viewHelper->render());
    }

    public function testMockRenderInputArgumentReturnsUrl(): void
    {
        $this->viewHelper
            ->expects($this->once())
            ->method('render')
            ->willReturn('aeueoe');

        $this->assertSame('aeueoe', $this->viewHelper->render());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->viewHelper = $this->getMockBuilder(UrlizeViewHelper::class)
            ->setMethods(['render'])
            ->getMock();

        $this->injectDependenciesIntoViewHelper($this->viewHelper);

        $this->viewHelper->initializeArguments();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->viewHelper = null;
        unset($this->viewHelper);
    }
}
