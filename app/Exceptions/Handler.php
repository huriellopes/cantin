<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as HTTPResponse;
use Throwable;
use Exception;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Exception|Throwable $e): Response|JsonResponse|HTTPResponse
    {
        if ($e instanceof ValidationException) {
            if (app()->isLocal()) {
                return response()->json([
                    'success' => false,
                    'status' => HTTPResponse::HTTP_BAD_REQUEST,
                    'error' => [
                        'type' => 'Api',
                        'code' => HTTPResponse::HTTP_BAD_REQUEST,
                        'details' => [
                            'message' => $e->validator->errors()->first()
                        ]
                    ],
                    'trace' => $e->getTrace()
                ], 400);
            }

            return response()->json([
                'success' => false,
                'status' => HTTPResponse::HTTP_BAD_REQUEST,
                'error' => [
                    'type' => 'Api',
                    'code' => HTTPResponse::HTTP_BAD_REQUEST,
                    'details' => [
                        'message' => $e->validator->errors()->first()
                    ]
                ]
            ], 400);
        }

        return parent::render($request, $e);
    }
}
