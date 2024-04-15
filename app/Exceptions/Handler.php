<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Throwable
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if($exception instanceof ValidationException && $request->expectsJson())
        {
            return response()->json([
                'status'     =>  422,
                'message'    =>  current($exception->errors())[0],
                'data'       =>  (Object) []                
            ]);
        }
        
        return parent::render($request, $exception);
    }

    /*
* If access token or session has expired then to handle unauthenticated request this method
* redirects on the respected page
*/
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        // If request is expecting json data (api) and access token is expired or invalid then
        if ($request->expectsJson()) {
            return response()->json([
                'statusCode' => 401,
                'status' => $exception->getMessage(),
                'message' => 'Your token has been expired. Please login again.'
            ], 401);
        }
        // If simple redirect session base web pages then
        $guard = @$exception->guards()[0];
        switch ($guard) {
            case "web":
                $redirectRoute = 'login';
                break;
            case "admin":
                $redirectRoute = 'admin.login';
                break;
            default:
                $redirectRoute = '/';
                break;
        }
        return redirect()->guest(route($redirectRoute));
    }


    

    // public function convertValidationExceptionToResponse(ValidationException $e, $request) 
    // {
    //     if($e instanceof ValidationException && $request->expectsJson())
    //     {
    //         return response()->json([
    //             'status'     =>  422,
    //             'message'    =>  current($e->errors())[0],
    //             'data'       =>  (Object) []                
    //         ]);
    //     }
    //     else
    //     {
    //        return parent::convertValidationExceptionToResponse($e, $request);
    //     }
   
    // }
}
