<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Handler extends ExceptionHandler
{
    /**
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];


    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        // التعامل مع استثناءات ModelNotFoundException
        if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'message' => 'العنصر المطلوب غير موجود.',
            ], 404);
        }

        // التعامل مع استثناءات NotFoundHttpException
        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'message' => 'الصفحة غير موجودة.',
            ], 404);
        }


        return parent::render($request, $exception);
    }
}
