<?php

declare (strict_types = 1);

namespace App\Modules\Entities;

class Client
{
    private string $name;
    private string $clientCode;

    public function __construct(string $name, string $clientCode)
    {
        $this->name       = $name;
        $this->clientCode = $clientCode;
    }

    public function create(string $name, string $clientCode): Client
    {
        $this->name       = $name;
        $this->clientCode = $clientCode;
        return $this;
    }
}
