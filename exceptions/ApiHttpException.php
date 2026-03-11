<?php

namespace app\exceptions;

use yii\web\HttpException;

class ApiHttpException extends HttpException
{

  public readonly string $error_code;
  public readonly ?array $details;

  public function __construct(int $status, string $error_code, string $message = '', ?array $details = null)
  {
    $this->error_code = $error_code;
    $this->details = $details;

    parent::__construct($status, $message, 0, null);
  }

   public function getErrorCode(): string
  {
    return $this->error_code;
  }

  public function getDetails(): ?array
  {
    return $this->details;
  }
}
