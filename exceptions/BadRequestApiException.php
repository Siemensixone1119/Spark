<?php

namespace app\exceptions;

class BadRequestApiException extends ApiHttpException
{
  public function __construct(string $error_code, string $message = 'Некоррректный запрос', $details = null)
  {
    parent::__construct(400, $error_code, $message, $details);
  }
}
