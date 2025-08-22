<?php

namespace ApiExhibitionManager\RedirectToRestApiOnFrontend\Redirector;

use ApiExhibitionManager\RedirectToRestApiOnFrontend\Redirector\Exiter\ExiterInterface;
use ApiExhibitionManager\RedirectToRestApiOnFrontend\Redirector\HeaderDispatcher\HeaderDispatcherInterface;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

class RedirectorTest extends TestCase
{
    #[TestDox('class can be instantiated')]
    public function testClassCanBeInstantiated(): void
    {
        $exiterMock           = $this->createMock(ExiterInterface::class);
        $headerDispatcherMock = $this->createMock(HeaderDispatcherInterface::class);
        $redirector           = new Redirector($headerDispatcherMock, $exiterMock);
        $this->assertInstanceOf(Redirector::class, $redirector);
    }

    #[TestDox('calls header with location and status')]
    public function testRedirectCallsHeader(): void
    {
        $headerDispatcherMock = $this->createMock(HeaderDispatcherInterface::class);
        $exiterMock           = $this->createMock(ExiterInterface::class);
        $redirector           = new Redirector($headerDispatcherMock, $exiterMock);

        $headerDispatcherMock->expects($this->once())
            ->method('header')
            ->with($this->stringContains('Location: '), true, 302);

        $redirector->redirect('/new-location', 302);
    }
}
