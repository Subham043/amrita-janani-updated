<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Symfony\Component\HttpKernel\Exception\HttpException;
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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, HttpException|Throwable $exception)
    {
        if ($exception instanceof ThrottleRequestsException && !$request->wantsJson()) {
            return redirect()->back()->with('error_status', $exception->getMessage());
        }else{
            return response()->json([
                'message' => $exception->getMessage(),
            ], $exception->getStatusCode());
        }
    }
}
