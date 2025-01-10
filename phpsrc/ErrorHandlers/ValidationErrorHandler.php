<?php

namespace Lt\Regitra\ErrorHandlers;

use Lt\Regitra\Exceptions\ValidationException;
use Slim\Interfaces\ErrorRendererInterface;
use Throwable;

class ValidationErrorHandler implements ErrorRendererInterface
{
    public function __invoke(Throwable $exception, bool $displayErrorDetails): string
    {
        if ($exception instanceof ValidationException) {
            return json_encode([
                'error' => $exception->getMessage(),
                'class' => get_class($exception),
                'trace' => $exception->getTrace(),
            ]);
        }
        return 'Default handler';
    }
}