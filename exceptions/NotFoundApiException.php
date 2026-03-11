<?php

namespace app\exceptions;

class NotFoundApiException extends ApiHttpException
{
  public function __construct(string $error_code, string $message = 'Не найдено', $details = null)
  {
    parent::__construct(404, $error_code, $message, $details);
  }
}
