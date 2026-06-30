<?php

declare(strict_types=1);

namespace App\Traits;

use Symfony\Component\HttpFoundation\Response;
use Throwable;

trait Requests
{
    use Utils;

    /**
     * @throws Throwable
     */
    public function shootExeception(Throwable $exception, ?string $messageLog = null): void
    {
        if ($messageLog) {
            $this->logSystem('single', $messageLog, 'error', null, $exception);
        }

        $this->logSystem('single', Response::HTTP_BAD_REQUEST, 'error', null, $exception);

        throw $exception;
    }
}
