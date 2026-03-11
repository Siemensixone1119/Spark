<?php

namespace app\controllers;

use Yii;
use Throwable;
use app\components\JwtHelpers;
use app\constants\ApiErrorCode;
use app\exceptions\NotFoundApiException;
use app\exceptions\UnauthorizedApiException;
use yii\web\Controller;
use yii\web\Response;
use app\models\User;
use app\services\UserSessionService;

class BaseApiController extends Controller
{
  public $enableCsrfValidation = false;
  protected array $publicActions = ['login', 'register', 'refresh'];

  public function beforeAction($action)
  {
    Yii::$app->response->format = Response::FORMAT_JSON;

    if (in_array($action->id, $this->publicActions, true)) {
      return parent::beforeAction($action);
    }

    $sessionService = new UserSessionService();
    $refresh_token = trim((string)Yii::$app->request->cookies->getValue('refresh_token'));

    $userId = (int)JwtHelpers::getUserId();

    if ($userId <= 0) {
      throw new UnauthorizedApiException(
        ApiErrorCode::UNAUTHORIZED,
        'Требуется авторизация'
      );
    }

    $user = User::findIdentity($userId);

    if (!$user) {
      throw new NotFoundApiException(
        ApiErrorCode::USER_NOT_FOUND,
        'Пользователь не найден'
      );
    }

    Yii::$app->user->setIdentity($user);

    if ($refresh_token) {
      try {
        $sessionService->touchByToken($refresh_token);
      } catch (Throwable $e) {
      }
    }

    return parent::beforeAction($action);
  }
}
