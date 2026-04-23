<?php

// src/controllers/ErrorController.php

class ErrorController {
    public function notFound() {
        require BASE_PATH . '/views/errors/404.php';
        //todo use code below
        //http_response_code(404);
        //renderView('errors/404', ['title' => 'Nie znaleziono strony']);
    }

    public function internalError() {
        require BASE_PATH . '/views/errors/500.php';
        //todo use code below
        //http_response_code(500);
        //renderView('errors/500', ['title' => 'Błąd serwera']);
    }
}
