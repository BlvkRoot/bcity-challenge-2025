<?php
declare (strict_types = 1);

namespace App\Modules\Controllers;

class Controller
{
    public function jsonResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        return json_encode($data);
    }
}
