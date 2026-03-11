<?php

namespace app\exceptions;

class UnprocessableEntityApiException extends ApiHttpException
{
  public function __construct(string $error_code, string $message = 'Некоррректный запрос', $details = null)
  {
    parent::__construct(422, $error_code, $message, $details);
  }
}
