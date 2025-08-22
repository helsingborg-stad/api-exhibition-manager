<?php

namespace ApiExhibitionManager\PostTypeRegistrar;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\MockObject\MockObject;
use WP_Post_Type;
use WpService\Contracts\AddAction;
use WpService\Contracts\RegisterPostType;

class PostTypeRegistrarTest extends TestCase
{
    #[TestDox('class can be instantiated')]
    public function testClassCanBeInstantiated(): void
    {
        $wpServiceMock = $this->getWpServiceMock();
        $registrar     = new PostTypeRegistrar('exhibition', 'Exhibition', 'Exhibitions', $wpServiceMock);

        $this->assertInstanceOf(PostTypeRegistrar::class, $registrar);
    }

    #[TestDox('attaches to the init hook')]
    public function testAttachesToInitHook(): void
    {
        ($wpServiceMock = $this->getWpServiceMock())
            ->expects($this->once())
            ->method('addAction')
            ->with('init');

        new PostTypeRegistrar('exhibition', 'Exhibition', 'Exhibitions', $wpServiceMock);
    }

    #[TestDox('uses WpService registerPostType to register post type')]
    public function testUsesWpServiceRegisterPostType(): void
    {
        ($wpServiceMock = $this->getWpServiceMock())
            ->expects($this->once())
            ->method('registerPostType')
            ->with('exhibition')
            ->willReturn(new WP_Post_Type([]));

        (new PostTypeRegistrar('exhibition', 'Exhibition', 'Exhibitions', $wpServiceMock))->registerPostType();
    }

    /**
     * @return AddAction&RegisterPostType&MockObject
     */
    private function getWpServiceMock(): AddAction&RegisterPostType&MockObject
    {
        return $this->createMockForIntersectionOfInterfaces([
            AddAction::class,
            RegisterPostType::class
        ]);
    }
}
