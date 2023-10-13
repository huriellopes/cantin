<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
trait Requests
{
    use Utils;

    /**
     * @param Throwable $exception
     * @param string|null $messageLog
     * @throws Throwable
     */
    public function shootExeception(Throwable $exception, string $messageLog = null)
    {
        if ($messageLog) {
            $this->logSystem('single', $messageLog, 'error', null, $exception);
        }

        $this->logSystem('single', Response::HTTP_BAD_REQUEST, 'error', null, $exception);

        throw $exception;
    }
}
