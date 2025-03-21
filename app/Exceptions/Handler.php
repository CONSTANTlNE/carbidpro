<?php

namespace App\Exceptions;

use Illuminate\Session\TokenMismatchException;
use App\Mail\ExceptionOccurred;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
        $this->reportable(function (Throwable $exception) {
            try {
                Mail::to(config('carbiddata.developerMail'))->send(new ExceptionOccurred($exception));
            } catch (\Exception $mailException) {
                Log::error('Failed to send exception email: '.$mailException->getMessage());
            }
        });
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof TokenMismatchException) {
            return redirect('/');
        }

        return parent::render($request, $e);
    }

}
