<?php

namespace ApiExhibitionManager\PostTypeRegistrar;

use ApiExhibitionManager\CommonContracts\HookableInterface;
use WpService\Contracts\__;
use WpService\Contracts\AddAction;
use WpService\Contracts\RegisterPostType;

class PostTypeRegistrar implements HookableInterface
{
    /**
     * @param string $postType
     * @param string $singularLabel
     * @param string $pluralLabel
     * @param AddAction&RegisterPostType&__ $wpService
     * @param array<string, mixed> $postTypeArgs
     */
    public function __construct(
        private string $postType,
        private string $singularLabel,
        private string $pluralLabel,
        private AddAction&RegisterPostType&__ $wpService,
        private array $postTypeArgs = []
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

    /**
     * Returns the arguments for registering the post type.
     *
     * @return array<string, mixed>
     */
    private function getArgs(): array
    {
        return array_merge(
            [
            'labels'       => $this->getLabels(),
            'public'       => true,
            'show_in_rest' => true
            ],
            $this->postTypeArgs
        );
    }

    /**
     * Returns the labels array for the custom post type.
     *
     * @return array<string, string>
     */
    private function getLabels(): array
    {
        return [
            'name'                  => $this->pluralLabel,
            'singular_name'         => $this->singularLabel,
            'menu_name'             => $this->pluralLabel,
            'name_admin_bar'        => $this->singularLabel,
            'add_new'               => $this->wpService->__('Add New', 'api-exhibition-manager'),
            'add_new_item'          => sprintf($this->wpService->__('Add New %s', 'api-exhibition-manager'), $this->singularLabel),
            'new_item'              => sprintf($this->wpService->__('New %s', 'api-exhibition-manager'), $this->singularLabel),
            'edit_item'             => sprintf($this->wpService->__('Edit %s', 'api-exhibition-manager'), $this->singularLabel),
            'view_item'             => sprintf($this->wpService->__('View %s', 'api-exhibition-manager'), $this->singularLabel),
            'all_items'             => sprintf($this->wpService->__('All %s', 'api-exhibition-manager'), $this->pluralLabel),
            'search_items'          => sprintf($this->wpService->__('Search %s', 'api-exhibition-manager'), $this->pluralLabel),
            'parent_item_colon'     => sprintf($this->wpService->__('Parent %s:', 'api-exhibition-manager'), $this->pluralLabel),
            'not_found'             => sprintf($this->wpService->__('No %s found.', 'api-exhibition-manager'), $this->pluralLabel),
            'not_found_in_trash'    => sprintf($this->wpService->__('No %s found in Trash.', 'api-exhibition-manager'), $this->pluralLabel),
            'insert_into_item'      => sprintf($this->wpService->__('Insert into %s', 'api-exhibition-manager'), $this->singularLabel),
            'uploaded_to_this_item' => sprintf($this->wpService->__('Uploaded to this %s', 'api-exhibition-manager'), $this->singularLabel),
            'filter_items_list'     => sprintf($this->wpService->__('Filter %s list', 'api-exhibition-manager'), $this->pluralLabel),
        ];
    }
}
