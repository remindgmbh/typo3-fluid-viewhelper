<?php

declare(strict_types=1);

namespace Remind\RmndViewHelper\Tests\Unit\ViewHelper;

use PHPUnit\Framework\MockObject\MockObject;
use Remind\RmndViewHelper\ViewHelper\UrlizeViewHelper;
use TYPO3\TestingFramework\Fluid\Unit\ViewHelpers\ViewHelperBaseTestcase;

/**
 * Description of UrlizeViewHelperTest
 */
class UrlizeViewHelperTest extends ViewHelperBaseTestcase
{
    /**
     * @var GetMimeTypeViewHelper|MockObject
     */
    protected $viewHelper = null;

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

    public function testMockRenderEmptyArgumentReturnsEmptyString()
    {
        $this->viewHelper
            ->expects($this->once())
            ->method('render')
            ->willReturn('');

        $this->assertSame('', $this->viewHelper->render());
    }

    public function testMockRenderInputArgumentReturnsUrl()
    {
        $this->viewHelper
            ->expects($this->once())
            ->method('render')
            ->willReturn('aeueoe');

        $this->assertSame('aeueoe', $this->viewHelper->render());
    }
}
