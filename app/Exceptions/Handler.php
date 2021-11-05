<?php

namespace App\Exceptions;

use App\Traits\ApiResponder;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    use ApiResponder;

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
     * @param Exception $exception
     * @return mixed|void
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($request->is('api/*')) {

            if ($exception instanceof ValidationException) {
                return $this->convertValidationExceptionToRestApiResponse($exception, $request);
            }

            if ($exception instanceof ModelNotFoundException) {
                $model = strtolower(class_basename($exception->getModel()));
                return $this->errorResponse(__('handler.ModelNotFoundException', ['model' => $model]), 404);
            }

            if ($exception instanceof AuthenticationException) {
                return $this->unauthenticatedApiResponse($request, $exception);
            }

            if ($exception instanceof AuthorizationException) {
                return $this->errorResponse(__('handler.AuthorizationException'), 403);
            }

            if ($exception instanceof NotFoundHttpException) {
                return $this->errorResponse(__('handler.NotFoundHttpException'), 404);
            }

            if ($exception instanceof MethodNotAllowedHttpException) {
                return $this->errorResponse(__('handler.MethodNotAllowedHttpException'), 405);
            }

            if ($exception instanceof HttpException) {
                return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
            }

            if ($exception instanceof QueryException) {
                $code = $exception->errorInfo[1];

                if ($code == 1451) {
                    return $this->errorResponse(__('handler.QueryException_1451'), 409);
                }
            }

            if (config('app.debug')) {
                return parent::render($request, $exception);
            }

            return $this->errorResponse(__('handler.Exception_500'), 500);
        }
        return parent::render($request, $exception);
    }

    /**
     * Create a response object from the given validation exception.
     *
     * @param ValidationException $exception
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    protected function convertValidationExceptionToRestApiResponse(ValidationException $exception, $request)
    {
        $errors = $exception->validator->errors()->getMessages();
        return $this->errorResponse($errors, 422);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     * @param $request
     * @param AuthenticationException $exception
     * @return \Illuminate\Http\JsonResponse
     */
    protected function unauthenticatedApiResponse($request, AuthenticationException $exception)
    {
        return $this->errorResponse(__('handler.Exception_401'), 401);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param Request $request
     * @param \Illuminate\Auth\AuthenticationException $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => __('handler.Exception_401')], 401);
        }

        return redirect()->guest(route('login'));
    }
}
