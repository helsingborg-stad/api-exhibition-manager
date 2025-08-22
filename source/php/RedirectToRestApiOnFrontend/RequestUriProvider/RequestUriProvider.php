<?php

namespace ApiExhibitionManager\RedirectToRestApiOnFrontend\RequestUriProvider;

use WpService\Contracts\SanitizeTextField;
use WpService\Contracts\WpUnslash;

class RequestUriProvider implements RequestUriProviderInterface
{
    public function __construct(private SanitizeTextField&WpUnslash $wpService)
    {
    }

    public function getRequestUri(): string
    {
        if (!isset($_SERVER['REQUEST_URI'])) {
            return '';
        }

        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
        $unslashedUri = $this->wpService->wpUnslash($_SERVER['REQUEST_URI']);

        return $this->wpService->sanitizeTextField($unslashedUri);
    }
}
