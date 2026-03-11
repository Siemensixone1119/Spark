<?php

namespace app\controllers;

use Yii;
use app\services\UserService;

class UserController extends BaseApiController
{
  public function actionView(): array
  {
    $userService = new UserService();

    $user = $userService->getUser(
      Yii::$app->user->identity?->email,
      Yii::$app->user->identity?->username
    );

    Yii::$app->response->statusCode = 200;
    return $user;
  }
}
