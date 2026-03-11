<?php

namespace app\controllers;

use app\models\Profile;
use Yii;
use app\services\ProfileService;

class ProfileController extends BaseApiController
{
  public function actionView(): array
  {
    $profileService = new ProfileService();
    $profile = $profileService->getProfile();

    Yii::$app->response->statusCode = 200;
    return $profile;
  }

  public function actionUpdate(): array
  {
    $data = Yii::$app->request->getBodyParams();
    $profileService = new ProfileService();

    $display_name = $data['display_name'] ?? null;
    $bio = $data['bio'] ?? null;
    $avatar = $data['avatar'] ?? null;
    $birth_date = $data['birth_date'] ?? null;

    $profile = $profileService->updateProfile($display_name, $bio, $avatar, $birth_date);

    Yii::$app->response->statusCode = 200;
    return $profile;
  }
}
