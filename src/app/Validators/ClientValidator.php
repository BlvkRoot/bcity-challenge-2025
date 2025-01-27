<?php

declare (strict_types = 1);

namespace App\Validators;

class ClientValidator
{
    private $errors = [];

    public function __construct(private $data, private $client)
    {
        $this->data   = $data;
        $this->client = $client;
    }

    public function validate()
    {
        $this->validateName();
        return empty($this->errors);
    }

    private function validateName()
    {
        $name = trim($this->data['name'] ?? '');

        if (empty($name)) {
            $this->errors['name'][] = "The client name is required.";
            return;
        }

        if (strlen($name) > 255) {
            $this->errors['name'][] = "The client name cannot exceed 255 characters.";
            return;
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
