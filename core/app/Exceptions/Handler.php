<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\URL;
use Throwable;
use Illuminate\Auth\AuthenticationException;

class Handler extends ExceptionHandler
{

    protected $dontReport = [
        //
    ];


    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
    
    protected function unauthenticated($request, AuthenticationException $exception)

    {

        return $this->shouldReturnJson($request, $exception)

                    ? response()->json(['message' => $exception->getMessage()], 401)

                    : redirect()->guest($exception->redirectTo() ?? route('landlord.user.login'));

    }


}
