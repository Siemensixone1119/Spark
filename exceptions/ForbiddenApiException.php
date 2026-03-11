<?php

namespace app\exceptions;

class ForbiddenApiException extends ApiHttpException
{
  public function __construct(string $error_code, string $message = 'Нет прав', $details = null)
  {
    parent::__construct(403, $error_code, $message, $details);
  }
}
