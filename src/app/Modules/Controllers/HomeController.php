<?php

declare(strict_types = 1);

namespace App\Modules\Controllers;

use App\View;

class HomeController
{
    public function index(): View
    {
        return View::make('index');
    }
}