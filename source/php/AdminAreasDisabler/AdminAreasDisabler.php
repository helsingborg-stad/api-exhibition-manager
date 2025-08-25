<?php

namespace ApiExhibitionManager\AdminAreasDisabler;

use ApiExhibitionManager\CommonContracts\HookableInterface;
use WpService\Contracts\AddAction;
use WpService\Contracts\RemoveMenuPage;
use WpService\Contracts\WpRedirect;

class AdminAreasDisabler implements HookableInterface
{
    public function __construct(private AddAction&RemoveMenuPage&WpRedirect $wpService)
    {
    }

    public function addHooks(): void
    {
        $this->wpService->addAction('admin_menu', [$this, 'removeAdminMenus']);
        $this->wpService->addAction('admin_init', [$this, 'redirectDashboard']);
    }

    public function removeAdminMenus(): void
    {
        $this->wpService->removeMenuPage('index.php');
        $this->wpService->removeMenuPage('edit.php');
        $this->wpService->removeMenuPage('edit.php?post_type=page');
        $this->wpService->removeMenuPage('edit-comments.php');
        $this->wpService->removeMenuPage('themes.php');
    }

    public function redirectDashboard(): void
    {
        if ($this->isDashboard()) {
            $this->wpService->wpRedirect(admin_url('edit.php?post_type=exhibition'));
            exit;
        }
    }

    private function isDashboard(): bool
    {
        if (!is_admin()) {
            return false;
        }

        if (!isset($_SERVER['REQUEST_URI']) || !is_string($_SERVER['REQUEST_URI'])) {
            return false;
        }

        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
        if (str_ends_with($_SERVER['REQUEST_URI'], '/wp-admin/index.php')) {
            return true;
        }

        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
        if (str_ends_with($_SERVER['REQUEST_URI'], '/wp-admin/')) {
            return true;
        }

        return false;
    }
}
