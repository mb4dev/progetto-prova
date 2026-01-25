<?php

interface Middleware {
    public function handle(): ?Response;
}
