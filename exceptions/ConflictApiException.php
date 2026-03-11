<?php

namespace app\exceptions;

class ConflictApiException extends ApiHttpException
{
  public function __construct(string $error_code, string $message = 'Конфликт', $details = null)
  {
    parent::__construct(409, $error_code, $message, $details);
  }
}
