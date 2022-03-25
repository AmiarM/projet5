<?php

namespace App\Libs;

class Validator
{
    private $datas;
    private $errors = [];
    private static $fields = [];

    public function __construct($datas)
    {
        $this->datas = $datas;
    }

    public function validate(array $rules)
    {
        foreach ($rules as $name => $rulesArray) {
            if (array_key_exists($name, $this->datas)) {
                foreach ($rulesArray as $rule) {
                    switch ($rule) {
                        case 'required':
                            $this->required($name, $this->datas[$name]);
                            break;
                        case substr($rule, 0, 3) === 'min':
                            $this->min($name, $this->datas[$name], $rule);
                            break;
                    }
                }
            }
        }
        return $this->getErrors();
    }
    public function required($name, $value)
    {
        $value = trim(strip_tags($value));
        if (!isset($value) || is_null($value) || empty($value)) {
            $this->addErrors($name, "{$name} est requis");
        }
    }

    public function min($name, $value, $rule)
    {
        preg_match_all('/(\d+)/', $rule, $matches);
        $limit = (int) $matches[0][0];
        if (strlen($value) < $limit) {
            $this->addErrors($name, "{$name} doit comprendre un minimum de {$limit} caractères");
        }
    }
    private function addErrors($name, $value)
    {
        $this->errors[$name][] = $value;
    }
    private function getErrors()
    {
        return $this->errors;
    }
}
