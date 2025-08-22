<?php

namespace ApiExhibitionManager\PostTypeRegistrar;

use ApiExhibitionManager\CommonContracts\HookableInterface;
use WpService\Contracts\__;
use WpService\Contracts\AddAction;
use WpService\Contracts\RegisterPostType;

class PostTypeRegistrar implements HookableInterface
{
    public function __construct(
        private string $postType,
        private string $singularLabel,
        private string $pluralLabel,
        private AddAction&RegisterPostType $wpService,
    ) {
        $this->addHooks();
    }

    public function addHooks(): void
    {
        $this->wpService->addAction('init', [$this, 'registerPostType']);
    }

    public function registerPostType(): void
    {
        $this->wpService->registerPostType($this->postType, $this->getArgs());
    }

    private function getArgs(): array
    {
        return [
            'labels'       => $this->getLabels(),
            'public'       => true,
            'show_in_rest' => true
        ];
    }

    private function getLabels(): array
    {
        return [
            'name'          => $this->pluralLabel,
            'singular_name' => $this->singularLabel
        ];
    }
}
