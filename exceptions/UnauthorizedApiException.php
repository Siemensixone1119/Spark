<?php

namespace app\exceptions;

class UnauthorizedApiException extends ApiHttpException
{
  public function __construct(string $error_code, string $message = 'Нет авторизации', $details = null)
  {
    parent::__construct(401, $error_code, $message, $details);
  }
}
