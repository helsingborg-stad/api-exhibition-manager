<?php

namespace ApiExhibitionManager\RedirectToRestApiOnFrontend\Redirector;

interface RedirectorInterface
{
    /**
     * Redirects to another page.
     *
     * @param string $location The path or URL to redirect to.
     * @param int $status Optional. HTTP response status code to use. Default '302' (Moved Temporarily).
     */
    public function redirect(string $location, int $status = 302): void;
}
