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
            'name'                  => $this->pluralLabel,
            'singular_name'         => $this->singularLabel,
            'menu_name'             => $this->pluralLabel,
            'name_admin_bar'        => $this->singularLabel,
            'add_new'               => __('Add New', 'api-exhibition-manager'),
            'add_new_item'          => sprintf(__('Add New %s', 'api-exhibition-manager'), $this->singularLabel),
            'new_item'              => sprintf(__('New %s', 'api-exhibition-manager'), $this->singularLabel),
            'edit_item'             => sprintf(__('Edit %s', 'api-exhibition-manager'), $this->singularLabel),
            'view_item'             => sprintf(__('View %s', 'api-exhibition-manager'), $this->singularLabel),
            'all_items'             => sprintf(__('All %s', 'api-exhibition-manager'), $this->pluralLabel),
            'search_items'          => sprintf(__('Search %s', 'api-exhibition-manager'), $this->pluralLabel),
            'parent_item_colon'     => sprintf(__('Parent %s:', 'api-exhibition-manager'), $this->pluralLabel),
            'not_found'             => sprintf(__('No %s found.', 'api-exhibition-manager'), $this->pluralLabel),
            'not_found_in_trash'    => sprintf(__('No %s found in Trash.', 'api-exhibition-manager'), $this->pluralLabel),
            'insert_into_item'      => sprintf(__('Insert into %s', 'api-exhibition-manager'), $this->singularLabel),
            'uploaded_to_this_item' => sprintf(__('Uploaded to this %s', 'api-exhibition-manager'), $this->singularLabel),
            'filter_items_list'     => sprintf(__('Filter %s list', 'api-exhibition-manager'), $this->pluralLabel),
        ];
    }
}
