<?php

namespace ApiExhibitionManager\RedirectToRestApiOnFrontend\Redirector;

use ApiExhibitionManager\RedirectToRestApiOnFrontend\Redirector\RedirectorInterface;
use ApiExhibitionManager\RedirectToRestApiOnFrontend\Redirector\Exiter\ExiterInterface;
use ApiExhibitionManager\RedirectToRestApiOnFrontend\Redirector\HeaderDispatcher\HeaderDispatcherInterface;

class Redirector implements RedirectorInterface
{
    public function __construct(
        private HeaderDispatcherInterface $headerDispatcher,
        private ExiterInterface $exiter
    ) {
    }

    public function redirect(string $location, int $status = 302): void
    {
        $this->headerDispatcher->header("Location: $location", true, $status);
        $this->exiter->exit();
    }
}
