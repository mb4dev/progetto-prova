<?php

interface Middleware {
    /**
     * Esegue il controllo del middleware.
     * @return Response|null Ritorna una Response se la richiesta deve essere interrotta (es. 401), altrimenti null.
     */
    public function handle(): ?Response;
}
