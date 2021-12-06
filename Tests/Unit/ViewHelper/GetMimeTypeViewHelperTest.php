<?php

declare(strict_types=1);

namespace Remind\RmndViewHelper\Tests\Unit\ViewHelper;

use PHPUnit\Framework\MockObject\MockObject;
use Remind\RmndViewHelper\ViewHelper\GetMimeTypeViewHelper;
use TYPO3\TestingFramework\Fluid\Unit\ViewHelpers\ViewHelperBaseTestcase;

/**
 * Description of GetMimeTypeViewHelperTest
 */
class GetMimeTypeViewHelperTest extends ViewHelperBaseTestcase
{
    /**
     * @var GetMimeTypeViewHelper|MockObject
     */
    protected $viewHelper = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->viewHelper = $this->getMockBuilder(GetMimeTypeViewHelper::class)
            ->setMethods(['render'])
            ->getMock();

        $this->injectDependenciesIntoViewHelper($this->viewHelper);

        $this->viewHelper->initializeArguments();
    }

    public function testMockRenderEmptyFileArgumentReturnsEmptyString()
    {
        $this->viewHelper
            ->expects($this->once())
            ->method('render')
            ->willReturn('');

        $this->assertSame('', $this->viewHelper->render());
    }

    public function testMockRenderFileArgumentReturnsCorrectMimeType()
    {
        $this->viewHelper
            ->expects($this->once())
            ->method('render')
            ->willReturn('text/x-php');

        $this->assertSame('text/x-php', $this->viewHelper->render());
    }
}
