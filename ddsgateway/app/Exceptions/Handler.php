<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use GuzzleHttp\Exception\ClientException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponser;

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
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
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    
    public function render($request, Throwable $exception)
    {
        //Find what error is
        //return response()->json(['class' => get_class($exception),'message' => $exception->getMessage(),]);
        
        //Duplicate username
        if ($exception instanceof UniqueConstraintViolationException) {
            return response()->json(['error' => 'Duplicate entry. Username already exists.'], 409);
        }

         // http not found    
        if ($exception instanceof HttpException) {
            $code = $exception->getStatusCode();
            $message = Response::$statusTexts[$code];
            return $this->errorResponse($message, $code);
        }

        // instance not found
        if ($exception instanceof ModelNotFoundException) {
            $model = strtolower(class_basename($exception->getModel()));
            return $this->errorResponse("Does not exist any instance of {$model} with the given id", Response::HTTP_NOT_FOUND);
        }

        // validation exception
        if ($exception instanceof ValidationException) {
            $errors = $exception->validator->errors()->getMessages();
            return $this->errorResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    
        // access to forbidden 
        if ($exception instanceof AuthorizationException) {
            return $this->errorResponse($exception->getMessage(), Response::HTTP_FORBIDDEN);
        }

        // unauthorized access
        if ($exception instanceof AuthenticationException) {
            return $this->errorResponse($exception->getMessage(), Response::HTTP_UNAUTHORIZED);
        }
        
        //client request error handling
        if ($exception instanceof ClientException) {
            $body = (string) $exception->getResponse()->getBody();
            if (str_contains($body, 'Duplicate entry')) {
                return $this->errorResponse('Duplicate entry. Username already exists.',Response::HTTP_CONFLICT);
            }
            return $this->errorResponse($body, $exception->getCode());
        }

        /*
        // duplicate entry / database errors
        if ($exception instanceof QueryException) {
            $errorCode = $exception->errorInfo[1];
            // 1062 = duplicate entry (MySQL)
            if ($errorCode == 1062) 
            {
                return $this->errorResponse('Duplicate entry. Username already exists.', Response::HTTP_CONFLICT);
            }
            return $this->errorResponse('Database error.', Response::HTTP_INTERNAL_SERVER_ERROR);
            }    

        // if your are running in development environment 
        if (env('APP_DEBUG', false)) {
            return parent::render($request, $exception);
        }
        */
        
        return $this->errorResponse('Unexpected error. Try later', Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
