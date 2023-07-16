<?php

namespace App\Exceptions;

use Error;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

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
        $this->renderable(function (AuthenticationException $e, $request){
            if (!$request->is('api/*')) {
                return null;
            }

            return response()->json([
                'message' => $e->getMessage(),
                'code' => 403,
            ], 403);
        });

        $this->renderable(function (Throwable $e, $request){
            if (!$request->is('api/*')) {
                return null;
            }

            if($e instanceof HttpExceptionInterface){
                $status = $e->getStatusCode();
            }
            else {
                $status = $e->getCode();
                $httpStatus = 200;
            }

            return response()->json([
                'message' => $e->getMessage(),
                'code' => $status,
            ], (isset($httpStatus)) ? $httpStatus : $status);
        });


    }
}
