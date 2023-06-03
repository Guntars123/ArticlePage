<?php namespace App\Core;

use App\Repositories\User\UserRepository;

class Validator
{
    private UserRepository $userRepository;
    private array $errors = [];
    private array $fields = [];

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function validateRegisterForm(array $fields = []): void
    {

        $this->fields = $fields;

        foreach ($fields as $field => $value) {
            $methodName = 'validate' . ucfirst($field);

            if (method_exists($this, $methodName)) {
                $this->{$methodName}();
            }
        }

        if (count($this->errors) > 0) {
            $_SESSION['errors'] = $this->errors;
        }
    }

    private function validateEmail(): void
    {
        $email = $this->fields['email'];

        $userExists = $this->userRepository->checkEmail($email);

        if ($userExists) {
            $this->errors['email'][] = 'Email already in use.';
        }
    }

    private function validatePassword()
    {
        $password = $this->fields['password'];
        $passwordRepeat = $this->fields['password_repeat'];

        if ($password !== $passwordRepeat) {
            $this->errors['password'][] = 'Passwords does not match.';
        }
    }

    private function getErrors(): array
    {
        return $this->errors;
    }
}
