<?php

namespace ApiExhibitionManager\RedirectToRestApiOnFrontend\RequestUriProvider;

interface RequestUriProviderInterface
{
    public function getRequestUri(): string;
}
