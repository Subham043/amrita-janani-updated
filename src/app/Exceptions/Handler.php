<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use Illuminate\Support\Facades\View;
use App\Support\Types\UserType;

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
        }elseif ($exception instanceof ThrottleRequestsException && $request->wantsJson()){
            return response()->json([
                'message' => $exception->getMessage(),
            ], $exception->getStatusCode());
        }

        if ($this->isHttpException($exception) && !$request->wantsJson()) {
            return $this->customRender(
                $exception,
                $exception->getStatusCode(),
                $exception->getMessage(),
                $exception->getHeaders()
            );
        }

        if ($exception instanceof MethodNotAllowedHttpException && !$request->wantsJson()) {
            return $this->customRender(
                $exception,
                Response::HTTP_METHOD_NOT_ALLOWED,
                $exception->getMessage()
            );
        }

        if ($exception instanceof ModelNotFoundException && !$request->wantsJson()) {
            return $this->customRender(
                $exception,
                Response::HTTP_NOT_FOUND,
                'No data found'
            );
        }

        if ($exception instanceof NotFoundHttpException && !$request->wantsJson()) {
            return $this->customRender(
                $exception,
                Response::HTTP_NOT_FOUND,
                $exception->getMessage(),
                $exception->getHeaders()
            );
        }

        return parent::render($request, $exception);
    }

    private function customRender($exception, $status_code, $message, $headers = []){
        if(request()->is('admin/*')){
            if(Auth::check()){
                View::share('common', [
                    'user_type' => UserType::lists()
                ]);
                return $this->sendErrorResponse($exception, $status_code, $message, $headers, 'errors.admin.authenticated_error');
            }else{
                return $this->sendErrorResponse($exception, $status_code, $message, $headers, 'errors.admin.unauthenticated_error');
            }
        }else{
            return $this->sendErrorResponse(
                $exception,
                $status_code,
                $message,
                $headers,
                'errors.error',
                [
                    'breadcrumb' => $message
                ],
            );
        }
    }

    private function sendErrorResponse($exception, $status_code, $message, $headers, $view, $data = []){
        return response()
            ->view($view,
                [
                    ...$data,
                    'exception' => $exception,
                    'status_code' => $status_code,
                    'message' => $message
                ],
                $status_code,
                $headers
            );
    }
}
