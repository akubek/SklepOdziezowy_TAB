<?php

// src/controllers/ErrorController.php

class ErrorController
{
    public function notFound()
    {
        http_response_code(404);
        renderView('errors/404', ['title' => 'Nie znaleziono strony']);
    }

    public function internalError()
    {
        http_response_code(500);
        renderView('errors/500', ['title' => 'Błąd serwera']);
    }
}
