<?php

namespace app\exceptions;

class TooManyRequestsApiException extends ApiHttpException
{
  public function __construct(string $error_code, string $message = 'Слишком много запросов', $details = null)
  {
    parent::__construct(429, $error_code, $message, $details);
  }
}
