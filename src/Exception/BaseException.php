<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;

abstract class BaseException extends Exception
{
    protected mixed $data = [];

    public function __construct(string $message = "", mixed $data = [], int $code = 0, Exception $previous = null)
    {
        $this->data = $data;
        parent::__construct($message, $code, $previous);
    }

    public function setData(string $key, mixed $value): void
    {
        $this->data[$key] = $value;
    }

    public function getData(): mixed
    {
        if (empty($this->data)) {
            return $this->data;
        }
        return json_decode(json_encode($this->data), true);
    }
}