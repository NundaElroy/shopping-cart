<?php
namespace App\Exceptions;

use CodeIgniter\Exceptions\ExceptionInterface;

class ValidationException extends \RuntimeException implements ExceptionInterface
{
    protected $errors = [];

    public function __construct(array $errors = [], string $message = null, int $code = 0, \Throwable $previous = null)
    {
        $this->errors = $errors;

        parent::__construct($message, $code, $previous);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}