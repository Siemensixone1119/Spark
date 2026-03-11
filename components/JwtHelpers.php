<?php

namespace app\components;

use Yii;
use yii\web\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use yii\base\Exception;

class JwtHelpers
{
  public static function getUserId()
  {
    $header = Yii::$app->request->headers->get('Authorization');

    if (empty($header)) {
      throw new Exception('Заголовок авторизации не был получен');
    }

    if (!preg_match('/^Bearer\s+(.*)$/', $header, $matches)) {
      throw new Exception('Неверный заголовок авторизации');
    }

    $token = $matches[1];

    // if (!str_starts_with($header, 'Bearer ')) {
    //   throw new Exception('Неверный заголовок авторизации');
    // }

    // $token = trim(substr($header, 7));


    $decode = JWT::decode($token, new Key(Yii::$app->params['jwtSecret'], 'HS256'));

    if (($decode->iss ?? null) !== Yii::$app->params['jwtIssuer']) {
      throw new Exception('Неверный issuer');
    }
    if (($decode->aud ?? null) !== Yii::$app->params['jwtAudience']) {
      throw new Exception('Неверный аудитория');
    }

    if (!isset($decode->sub)) {
      throw new Exception('Токен не содержит subject');
    }

    return (int) $decode->sub;
  }
}
