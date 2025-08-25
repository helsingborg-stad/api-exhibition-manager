<?php

namespace ApiExhibitionManager\GutenbergDisabler;

use ApiExhibitionManager\CommonContracts\HookableInterface;
use WpService\Contracts\AddFilter;

class GutenbergDisabler implements HookableInterface
{
    public function __construct(private AddFilter $wpService)
    {
    }

    public function addHooks(): void
    {
        $this->wpService->addFilter('use_block_editor_for_post_type', [$this, 'returnFalse']);
    }

    public function returnFalse(): false
    {
        return false;
    }
}
