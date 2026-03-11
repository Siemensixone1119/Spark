<?php

namespace app\exceptions;

class InternalServerApiException extends ApiHttpException
{
  public function __construct(string $error_code, string $message = 'Ошибка сервера', $details = null)
  {
    parent::__construct(500, $error_code, $message, $details);
  }
}
