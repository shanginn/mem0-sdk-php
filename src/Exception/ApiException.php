<?php

declare(strict_types=1);

namespace Mem0\Exception;

class ApiException extends Mem0Exception
{
    public function __construct(string $message = '', int $statusCode = 0, public mixed $errorData = null)
    {
        parent::__construct($message, $statusCode);
    }

    public function getErrorData(): mixed
    {
        return $this->errorData;
    }
}