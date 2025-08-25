<?php

namespace ApiExhibitionManager\RedirectToRestApiOnFrontend;

use ApiExhibitionManager\CommonContracts\HookableInterface;
use ApiExhibitionManager\RedirectToRestApiOnFrontend\Redirector\RedirectorInterface;
use ApiExhibitionManager\RedirectToRestApiOnFrontend\RequestUriProvider\RequestUriProviderInterface;
use WpService\Contracts\{AddAction, GetRestUrl, IsAdmin, IsLogin};

/**
 * Class RedirectToRestApiOnFrontend
 *
 * Redirects users to the WordPress REST API URL if they are not in the admin area
 * and are not already visiting the REST API or any of its sub-routes.
 *
 * @package ExhibitionManager
 */
class RedirectToRestApiOnFrontend implements HookableInterface
{
    /**
     * RedirectToRestApiOnFrontend constructor.
     */
    public function __construct(
        private GetRestUrl&IsAdmin&AddAction&IsLogin $wpService,
        private RequestUriProviderInterface $requestUriProvider,
        private RedirectorInterface $redirector
    ) {
    }

    public function addHooks(): void
    {
        $this->wpService->addAction('init', [$this, 'maybeRedirect']);
    }

    /**
     * Executes the redirect logic.
     *
     * @return void
     */
    public function maybeRedirect(): void
    {

        if ($this->wpService->isAdmin() || $this->wpService->isLogin()) {
            return;
        }

        $requestUri  = $this->requestUriProvider->getRequestUri();
        $restApiBase = $this->wpService->getRestUrl();

        // Normalize both URIs for comparison
        $normalizedRequestUri  = rtrim($requestUri, '/');
        $parsedUrl             = parse_url($requestUri, PHP_URL_PATH) ?: '';
        $normalizedRestApiBase = rtrim($parsedUrl, '/');

        // If already visiting REST API or its sub-routes, do nothing
        if (strpos($normalizedRequestUri, $normalizedRestApiBase) === 0) {
            return;
        }

        $this->redirector->redirect($restApiBase);
    }
}
