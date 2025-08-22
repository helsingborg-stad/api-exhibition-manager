<?php

namespace ApiExhibitionManager\RedirectToRestApiOnFrontend\Redirector\Exiter;

/**
 * Interface for terminating script execution.
 */
interface ExiterInterface
{
    /**
     * Terminates the script execution.
     *
     * This method should be called to stop further script execution after a redirect or other critical operations.
     *
     * @return void
     */
    public function exit(): void;
}
