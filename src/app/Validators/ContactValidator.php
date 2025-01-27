<?php

declare (strict_types = 1);

namespace App\Validators;

use App\Modules\Entities\Contact;

class ContactValidator
{
    private $errors = [];
    private Contact $contactEntity;

    public function __construct(private $data, private $contact)
    {
        $this->data   = $data;
        $this->contact = $contact;
        $this->contactEntity = new Contact();
    }

    public function validate()
    {
        $this->validateFullname();
        $this->validateEmail();
        $this->validateDuplicateEmail();
        return empty($this->errors);
    }

    private function validateFullname()
    {
        $name = trim($this->data['name'] ?? '');
        $surname = trim($this->data['surname'] ?? '');

        if (empty($name) || empty($surname)) {
            $this->errors['fullname'][] = "The name and surname is required.";
            return;
        }
    }

    private function validateEmail() {
        $email = trim($this->data['email'] ?? '');
        $regex = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
        $isValidEmail = preg_match($regex, $email) === 1;

        if(! $isValidEmail) {
            $this->errors['email'][] = "The email format is invalid.";
            return;
        }
    }

    private function validateDuplicateEmail() {
        $emailAlreadyExists = $this->contactEntity->getContactsByEmail($this->data['email']);
        if(count($emailAlreadyExists) > 0) {
            $this->errors['email'][] = "Email already exists";
            return;
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
