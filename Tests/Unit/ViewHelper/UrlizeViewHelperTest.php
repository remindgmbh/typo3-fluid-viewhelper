<?php

declare(strict_types=1);

namespace Remind\RmndViewHelper\Tests\Unit\ViewHelper;

use PHPUnit\Framework\MockObject\MockObject;
use Remind\RmndViewHelper\ViewHelper\UrlizeViewHelper;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Description of UrlizeViewHelperTest
 */
class UrlizeViewHelperTest extends UnitTestCase
{
    protected UrlizeViewHelper&MockObject $viewHelper;

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
            ->onlyMethods(['render'])
            ->getMock();

        $this->viewHelper->initializeArguments();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->viewHelper);
    }
}
