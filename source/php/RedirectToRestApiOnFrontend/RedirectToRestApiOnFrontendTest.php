<?php

namespace ApiExhibitionManager\RedirectToRestApiOnFrontend;

use PHPUnit\Framework\TestCase;
use ApiExhibitionManager\RedirectToRestApiOnFrontend\Redirector\RedirectorInterface;
use ApiExhibitionManager\RedirectToRestApiOnFrontend\RequestUriProvider\RequestUriProviderInterface;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\MockObject\MockObject;
use WpService\Contracts\GetRestUrl;
use WpService\Contracts\IsAdmin;
use WpService\Contracts\AddAction;
use WpService\Contracts\IsLogin;

class RedirectToRestApiOnFrontendTest extends TestCase
{
    #[TestDox('class can be instantiated')]
    public function testClassCanBeInstantiated(): void
    {
        $instance = new RedirectToRestApiOnFrontend(
            $this->getWpServiceMock(),
            $this->getRequestUriProviderMock(),
            $this->getRedirectorMock()
        );
        $this->assertInstanceOf(RedirectToRestApiOnFrontend::class, $instance);
    }

    #[TestDox('attaches to the init hook')]
    public function testAddsHooks(): void
    {
        $wpServiceMock = $this->getWpServiceMock();
        $wpServiceMock->expects($this->once())->method('addAction')->with('init');

        $instance = new RedirectToRestApiOnFrontend(
            $wpServiceMock,
            $this->getRequestUriProviderMock(),
            $this->getRedirectorMock()
        );

        $instance->addHooks();
    }

    #[TestDox('redirects to REST API URL')]
    public function testRedirectsToRestApiUrl(): void
    {
        $wpServiceMock          = $this->getWpServiceMock();
        $requestUriProviderMock = $this->getRequestUriProviderMock();
        $redirectorMock         = $this->getRedirectorMock();

        $instance = new RedirectToRestApiOnFrontend(
            $wpServiceMock,
            $requestUriProviderMock,
            $redirectorMock
        );

        // Simulate the conditions for the redirect
        $wpServiceMock->method('isAdmin')->willReturn(false);
        $wpServiceMock->method('isLogin')->willReturn(false);
        $requestUriProviderMock->method('getRequestUri')->willReturn('/some-random-page');
        $wpServiceMock->method('getRestUrl')->willReturn('https://example.com/wp-json/');

        $redirectorMock->expects($this->once())->method('redirect')->with('https://example.com/wp-json/');

        $instance->maybeRedirect();
    }

    #[TestDox('does not redirect if already visiting REST API')]
    public function testDoesNotRedirectIfAlreadyVisitingRestApi(): void
    {
        $wpServiceMock          = $this->getWpServiceMock();
        $requestUriProviderMock = $this->getRequestUriProviderMock();
        $redirectorMock         = $this->getRedirectorMock();

        $instance = new RedirectToRestApiOnFrontend(
            $wpServiceMock,
            $requestUriProviderMock,
            $redirectorMock
        );

        // Simulate the conditions for no redirect
        $wpServiceMock->method('isAdmin')->willReturn(false);
        $wpServiceMock->method('isLogin')->willReturn(false);
        $requestUriProviderMock->method('getRequestUri')->willReturn('/wp-json/');
        $wpServiceMock->method('getRestUrl')->willReturn('https://example.com/wp-json/');

        $redirectorMock->expects($this->never())->method('redirect');

        $instance->maybeRedirect();
    }

    #[TestDox('does not redirect if in admin')]
    public function testDoesNotRedirectIfInAdmin(): void
    {
        $wpServiceMock          = $this->getWpServiceMock();
        $requestUriProviderMock = $this->getRequestUriProviderMock();
        $redirectorMock         = $this->getRedirectorMock();

        $instance = new RedirectToRestApiOnFrontend(
            $wpServiceMock,
            $requestUriProviderMock,
            $redirectorMock
        );

        // Simulate the conditions for no redirect
        $wpServiceMock->method('isAdmin')->willReturn(true);
        $wpServiceMock->method('isLogin')->willReturn(false);
        $requestUriProviderMock->method('getRequestUri')->willReturn('/some-random-page');
        $wpServiceMock->method('getRestUrl')->willReturn('https://example.com/wp-json/');

        $redirectorMock->expects($this->never())->method('redirect');

        $instance->maybeRedirect();
    }

    #[TestDox('does not redirect if on login page')]
    public function testDoesNotRedirectIfOnLoginPage(): void
    {
        $wpServiceMock          = $this->getWpServiceMock();
        $requestUriProviderMock = $this->getRequestUriProviderMock();
        $redirectorMock         = $this->getRedirectorMock();

        $instance = new RedirectToRestApiOnFrontend(
            $wpServiceMock,
            $requestUriProviderMock,
            $redirectorMock
        );

        // Simulate the conditions for no redirect
        $wpServiceMock->method('isAdmin')->willReturn(false);
        $wpServiceMock->method('isLogin')->willReturn(true);
        $requestUriProviderMock->method('getRequestUri')->willReturn('/some-random-page');
        $wpServiceMock->method('getRestUrl')->willReturn('https://example.com/wp-json/');

        $redirectorMock->expects($this->never())->method('redirect');

        $instance->maybeRedirect();
    }

    private function getWpServiceMock(): GetRestUrl|IsAdmin|AddAction|IsLogin|MockObject
    {
        return $this->createMockForIntersectionOfInterfaces([
            GetRestUrl::class,
            IsAdmin::class,
            AddAction::class,
            IsLogin::class
        ]);
    }

    private function getRequestUriProviderMock(): RequestUriProviderInterface|MockObject
    {
        return $this->createMock(RequestUriProviderInterface::class);
    }

    private function getRedirectorMock(): RedirectorInterface|MockObject
    {
        return $this->createMock(RedirectorInterface::class);
    }
}
