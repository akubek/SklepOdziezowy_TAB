<?php
// src/controllers/ErrorController.php

class ErrorController
{
    public function forbidden()
    {
        http_response_code(403);
        renderView('errors/403', ['title' => '403 - Brak dostępu']);
    }

    public function notFound()
    {
        http_response_code(404);
        renderView('errors/404', ['title' => '404 - Nie znaleziono strony']);
    }

    public function internalError()
    {
        http_response_code(500);
        renderView('errors/500', ['title' => '500 - Błąd serwera']);
    }

    public function conflict()
    {
        http_response_code(409);
        renderView('errors/409', ['title' => '409 - Konflikt danych']);
    }
}
