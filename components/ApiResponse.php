<?php

namespace app\components;

use Yii;
use yii\web\Response;
use yii\web\HttpException;
use app\constants\ApiErrorCode;
use app\exceptions\ApiHttpException;

class ApiResponse extends Response
{
  public function init(): void
  {
    parent::init();

    $this->format = self::FORMAT_JSON;

    $this->on(self::EVENT_BEFORE_SEND, function () {
      $e = Yii::$app->errorHandler->exception;

      if ($e !== null) {
        // дефолты (обязательно!)
        $status = $this->statusCode ?: 500;
        $errorCode = ApiErrorCode::INTERNAL_ERROR;
        $details = null;

        // приводим к нашим исключениям
        if ($e instanceof ApiHttpException) {
          $status = $e->statusCode;
          $errorCode = $e->getErrorCode();
          $details = $e->getDetails();
        } elseif ($e instanceof HttpException) {
          $status = $e->statusCode;
        }

        $payload = [
          'success' => false,
          'error_code' => $errorCode,
          'message' => $e->getMessage() ?: 'Ошибка',
        ];

        if (!empty($details)) {
          $payload['details'] = $details;
        }

        if (defined('YII_ENV_DEV') && YII_ENV_DEV) {
          $payload['debug'] = [
            'type' => get_class($e),
            'code' => $e->getCode(),
            // если нужно — раскомментируй:
            // 'file' => $e->getFile(),
            // 'line' => $e->getLine(),
            // 'trace' => explode("\n", $e->getTraceAsString()),
          ];
        }

        // лог — очень полезно, даже если debug выключен
        Yii::error($e, 'api-exception');

        $this->statusCode = $status;
        $this->data = $payload;
        return;
      }

      // успехи оборачиваем единообразно
      if (is_array($this->data)) {
        if (array_key_exists('success', $this->data)) {
          return;
        }
        if (array_key_exists('data', $this->data) || array_key_exists('meta', $this->data)) {
          $this->data = ['success' => true] + $this->data;
          return;
        }
      }

      if ($this->statusCode === 204) {
        $this->data = null;
        return;
      }

      $this->data = [
        'success' => true,
        'data' => $this->data,
      ];
    });
  }
}
