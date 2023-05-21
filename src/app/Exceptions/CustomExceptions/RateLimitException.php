<?php

namespace App\Exceptions\CustomExceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RateLimitException extends Exception
{
    protected $status_code = 429;
    protected $message = "Too many attempts! Please try again later";

    public function __construct(string $message, int $status_code)
    {
        parent::__construct($message, $status_code);
        $this->status_code = $status_code;
        $this->message = $message;
    }

    public function showMessage()
    {
        return $this->message;
    }

    public function showStatusCode()
    {
        return $this->status_code;
    }

    /**
     * Report the exception.
     */
    public function report(): void
    {

    }

    /**
     * Render the exception into an HTTP response.
     */
    public function render(Request $request): Response|RedirectResponse|JsonResponse
    {
        if ($request->wantsJson()) {
            return response()->json([
                'message' => $this->showMessage(),
            ], $this->showStatusCode());
        }else{
            return redirect()->back()->with('error_status', $this->showMessage());
        }
    }


}
