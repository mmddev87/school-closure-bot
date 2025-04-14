<?php

namespace Bootstrap;

use Presentation\Web\Controllers\CheckClosureController;

class WebBootstrapper
{
    public function run() {
        if (str_starts_with($_SERVER['REQUEST_URI'], '/api/checkClosure')) {
            header('Content-Type: application/json');
            echo json_encode((new CheckClosureController)($_GET['city'] ?? null), JSON_UNESCAPED_UNICODE);
        } elseif ($_SERVER['REQUEST_URI'] == '/') {
            require_once BASE_PATH . '/src/Presentation/Web/Views/index.php';
        } else {
            http_response_code(404);
        }
    }
}
