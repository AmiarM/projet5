<?php

namespace App\Libs;

class Request
{
    private $data;
    private $errors;

    public function __construct()
    {
        $this->data = $this->all();
    }

    public function all()
    {
        return $_POST;
    }

    public function get($fields)
    {
        return $_POST[$fields];
    }


    public function validate(array $rules): ?array
    {
        foreach ($rules as $name => $rulesArray) {
            if (array_key_exists($name, $this->data)) {
                foreach ($rulesArray as $rule) {
                    switch ($rule) {
                        case 'required':
                            $this->required($name, $this->data[$name]);
                            break;
                        case substr($rule, 0, 3) === 'min':
                            $this->min($name, $this->data[$name], $rule);
                            break;
                        case substr($rule, 0, 3) === 'max':
                            $this->max($name, $this->data[$name], $rule);
                            break;
                        default:
                            # code...
                            break;
                    }
                }
            }
        }
        if ($this->getErrors() != null) {
            header("mocation: /login");
        } else {
            return $this->all();
        }
    }

    private function required(string $name, string $value)
    {
        $value = trim($value);

        if (!isset($value) || is_null($value) || empty($value)) {
            $this->errors[$name][] = "{$name} est requis.";
        }
    }

    private function min(string $name, string $value, string $rule)
    {
        preg_match_all('/(\d+)/', $rule, $matches);
        $limit = (int) $matches[0][0];

        if (strlen($value) < $limit) {
            $this->errors[$name][] = "{$name} doit comprendre un minimum de {$limit} caractères";
        }
    }

    private function max(string $name, string $value, string $rule)
    {
        preg_match_all('/(\d+)/', $rule, $matches);
        $limit = (int) $matches[0][0];

        if (strlen($value) > $limit) {
            $this->errors[$name][] = "{$name} doit comprendre un maximum de {$limit} caractères";
        }
    }

    private function getErrors(): ?array
    {
        if (!empty($this->errors)) {
            $_SESSION['errors'] = $this->errors;
        } else {
            session_destroy();
        }
        return isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
    }
}
