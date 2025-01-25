<?php

declare(strict_types=1);

namespace App;

use App\Exceptions\ViewNotFoundException;

define('LAYOUT_VIEW', VIEW_PATH . '/layouts/layout.php');

class View
{
    public function __construct(
        protected string $view,
        protected array $params = []
    ) {
    }

    public static function make(string $view, array $params = []): static
    {
        return new static($view, $params);
    }

    public function render(): string
    {
        $viewPath = VIEW_PATH . '/' . $this->view . '.php';

        if (! file_exists($viewPath)) {
            throw new ViewNotFoundException();
        }

        foreach($this->params as $key => $value) {
            $$key = $value;
        }

        ob_start();

        include LAYOUT_VIEW;

        $layout = (string) ob_get_clean();

        ob_start();

        include $viewPath;

        $viewContentOutput = (string) ob_get_clean();

        $pageContent = str_replace('{{content}}', $viewContentOutput, $layout);

        return $pageContent;
    }

    public function __toString(): string
    {
        return $this->render();
    }

    public function __get(string $name)
    {
        return $this->params[$name] ?? null;
    }
}