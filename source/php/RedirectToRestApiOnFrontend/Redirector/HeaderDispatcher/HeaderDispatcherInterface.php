<?php

namespace ApiExhibitionManager\RedirectToRestApiOnFrontend\Redirector\HeaderDispatcher;

interface HeaderDispatcherInterface
{
    /**
     * Send a raw HTTP header
     *
     * @param string $header The header string to send
     * @param bool $replace Whether to replace a previous similar header, or add a second header of the same type
     * @param int $responseCode Forces the HTTP response code to the specified value
     */
    public function header(string $header, bool $replace = true, int $responseCode = 0): void;
}
