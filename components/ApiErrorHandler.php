<?php

namespace app\components;

use app\exceptions\ApiHttpException;
use  Yii;
use yii\web\ErrorHandler;
use yii\web\HttpException;
use yii\web\Response;

class ApiErrorHandler extends ErrorHandler
{
  protected function renderException($exception): void
  {
    $response = Yii::$app->response;
    $response->format = Response::FORMAT_JSON;

    $status = 500;
    $error_code = 'INTERNAL_ERROR';
    $message = 'Внутренняя ошибка сервера';

    if ($exception instanceof ApiHttpException) {
      $status = $exception->statusCode;
      $error_code = $exception->error_code;
      $message = $exception->getMessage();
    } elseif ($exception instanceof HttpException) {
      $status = $exception->statusCode;
      $error_code = match ($status) {
        400 => 'BAD_REQUEST',
        401 => 'UNAUTHORIZED',
        403 => 'FORBIDDEN',
        404 => 'NOT_FOUND',
        422 => 'VALIDATION_ERROR',
        default => 'HTTP_ERROR',
      };
      $message = $exception->getMessage();
    }

    $response->statusCode = $status;
    $response->data = [
      'error_code' => $error_code,
      'error_message' => $message
    ];

    $response->send();
    Yii::$app->end();
  }
}
