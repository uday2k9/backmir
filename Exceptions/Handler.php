<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
        ModelNotFoundException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
   public function render($request, Exception $e)
    {
        // Show error pages

        if ($e instanceof ModelNotFoundException) {
            $e = new NotFoundHttpException($e->getMessage(), $e);
        }
	
    	if(config('app.debug') == false)
    	{
    		if ($e instanceof ModelNotFoundException) {
    		    $e = new NotFoundHttpException($e->getMessage(), $e);
    		}

    	 	else if ($e instanceof Exception) {
                
    		    return response()->view('errors.custom');
    		}
    	}
	    return parent::render($request, $e);
    }
    
    
     /* Prodipto public function render($request, Exception $e)
     {
         if ($e instanceof ModelNotFoundException) {
             $e = new NotFoundHttpException($e->getMessage(), $e);
         }
    
         if ($e instanceof \Illuminate\Session\TokenMismatchException) {            
             return redirect('/')->withErrors(['token_error' => 'Sorry, your session seems to have expired. Please try again.']);
         }else{
            return redirect('/error')->withErrors(['token_error' => 'Sorry, somthing is wrong. Please try later.']); 
            
         }
    
         return parent::render($request, $e);
     }*/
}
