<?php

namespace ApiExhibitionManager\RedirectToRestApiOnFrontend\Redirector\Exiter;

class Exiter implements ExiterInterface
{
    /**
     * @inheritDoc
     */
    public function exit(): void
    {
        exit;
    }
}
