<?php

namespace ApiExhibitionManager\RedirectToRestApiOnFrontend\Redirector\HeaderDispatcher;

class HeaderDispatcher
{
    /**
     * @inheritDoc
     */
    public function header(string $header, bool $replace = true, int $responseCode = 0): void
    {
        header($header, $replace, $responseCode);
    }
}
