<?php

namespace app\controllers;

use Yii;
use app\services\UserService;
use app\services\UserSessionService;
use app\components\Pagination;
use yii\web\Cookie;
use app\components\DeviceParser;
use app\constants\ApiErrorCode;
use app\exceptions\BadRequestApiException;
use app\exceptions\UnauthorizedApiException;
use app\exceptions\UnprocessableEntityApiException;

class AuthController extends BaseApiController
{
  protected array $publicActions = ['login', 'register', 'refresh'];

  public function actionRegister(): array
  {
    $data = Yii::$app->request->getBodyParams();
    $userService = new UserService;
    $session = new UserSessionService;
    $auth = Yii::$app->authManager;
    $role = $auth->getRole('user');
    $user_agent = (string)Yii::$app->request->userAgent;
    $ip_address = (string)Yii::$app->request->userIP;

    $username = trim((string)($data['username'] ?? ''));
    $email = trim((string)($data['email'] ?? ''));
    $password = trim((string)($data['password'] ?? ''));

    if ($username === '' || $email === '' || $password === '') {
      throw new UnprocessableEntityApiException(ApiErrorCode::MISSING_PARAMETER, 'Не переданы параметры регистрации пользователя');
    }

    $userService->registerUser($username, $email, $password);

    $jwt_data = $userService->authUser($email, $password);

    $user_id = $jwt_data['id'];
    $refresh_data = $session->createSession($user_id, $user_agent, $ip_address);

    $cookie = new Cookie([
      'name' => 'refresh_token',
      'value' => $refresh_data['refresh_token'],
      'httpOnly' => true,
      'secure' => false,
      'sameSite' => Cookie::SAME_SITE_LAX,
      'path' => '/',
      'expire' => $refresh_data['expires_at']
    ]);

    Yii::$app->response->cookies->add($cookie);

    $auth->assign($role, $user_id);

    Yii::$app->response->statusCode = 201;
    return [
      'access_token' =>  $jwt_data['access_token'],
      'access_expires_in' => $jwt_data['expires_in'],
    ];
  }

  public function actionLogin(): array
  {
    $data = Yii::$app->request->getBodyParams();
    $userService = new UserService;
    $session = new UserSessionService;

    $user_agent = (string)Yii::$app->request->userAgent;
    $ip_address = (string)Yii::$app->request->userIP;

    $email = trim((string)($data['email'] ?? ''));
    $password = trim((string)($data['password'] ?? ''));

    if ($email === '' || $password === '') {
      throw new UnprocessableEntityApiException(ApiErrorCode::MISSING_PARAMETER, 'Не переданы параметры авторизации пользователя');
    }

    $jwt_data = $userService->authUser($email, $password);

    $userId = $jwt_data['id'];
    $refresh_data = $session->createSession($userId, $user_agent, $ip_address);

    $cookie = new Cookie([
      'name' => 'refresh_token',
      'value' => $refresh_data['refresh_token'],
      'httpOnly' => true,
      'secure' => false,
      'sameSite' => Cookie::SAME_SITE_LAX,
      'path' => '/',
      'expire' => $refresh_data['expires_at']
    ]);

    Yii::$app->response->cookies->add($cookie);

    Yii::$app->response->statusCode = 200;
    return [
      'access_token' =>  $jwt_data['access_token'],
      'access_expires_in' => $jwt_data['expires_in'],
    ];
  }

  public function actionLogout(): void
  {
    $sessionService = new UserSessionService;

    $refresh_token = trim((string)Yii::$app->request->cookies->getValue('refresh_token'));

    if (!$refresh_token) {
      throw new UnauthorizedApiException(ApiErrorCode::TOKEN_MISSING, 'Refresh токен не передан');
    }

    $sessionService->removeSessionByToken($refresh_token);
    Yii::$app->response->cookies->remove('refresh_token');

    Yii::$app->response->statusCode = 204;
  }

  public function actionLogoutAll(): void
  {
    $sessionService = new UserSessionService;
    $user_id = (int)Yii::$app->user->id;

    $sessionService->removeAllSessions($user_id);
    Yii::$app->response->cookies->remove('refresh_token');

    Yii::$app->response->statusCode = 204;
  }

  public function actionLogoutSession(int $id): void
  {
    $sessionService = new UserSessionService;

    $refresh_token = ((string)Yii::$app->request->cookies->getValue('refresh_token'));
    $session = null;

    if ($id <= 0) {
      throw new BadRequestApiException(ApiErrorCode::MISSING_PARAMETER, 'session_id не передан');
    }

    if ($refresh_token) {
      $session = $sessionService->getSession($refresh_token);
    }

    $sessionService->removeSessionById($id);

    if ($session && $session->id == $id) {
      Yii::$app->response->cookies->remove('refresh_token');
    }

    Yii::$app->response->statusCode = 204;
  }

  public function actionRefresh(): array
  {
    $sessionService = new UserSessionService;
    $userService = new UserService;

    $refresh_token = trim((string)Yii::$app->request->cookies->getValue('refresh_token'));
    $user_agent = trim((string)Yii::$app->request->userAgent);
    $ip_address = trim((string)Yii::$app->request->userIP);

    if (!$refresh_token) {
      throw new UnauthorizedApiException(ApiErrorCode::TOKEN_MISSING, 'Refresh токен не передан');
    }

    $new_session = $sessionService->refreshByToken($refresh_token, $user_agent, $ip_address);

    $cookie = new Cookie([
      'name' => 'refresh_token',
      'value' => $new_session['refresh_token'],
      'httpOnly' => true,
      'secure' => false,
      'sameSite' => Cookie::SAME_SITE_LAX,
      'path' => '/',
      'expire' => $new_session['expires_at']
    ]);

    Yii::$app->response->cookies->add($cookie);

    $access_token = $userService->generateToken($new_session['user_id']);

    Yii::$app->response->statusCode = 200;
    return [
      'access_token' => $access_token,
      'access_expires_in' => Yii::$app->params['jwtExpire']
    ];
  }

  public function actionSessions(): array
  {
    $p = Pagination::fromRequest(2, 3);

    $sessionService = new UserSessionService;
    [$sessions, $total] = $sessionService->getSessions($p['offset'], $p['limit']);
    $refreshToken = trim((string)Yii::$app->request->cookies->getValue('refresh_token'));

    $current = $sessionService->getCurrentSession($refreshToken);

    foreach ($sessions as &$session) {
      $session['is_current'] = $current && $session['id'] === $current['id'];
      $session['device_name'] = DeviceParser::getDeviceName($session['user_agent']);
    }

    Yii::$app->response->statusCode = 200;
    return [
      'data' => $sessions,
      'meta' => Pagination::meta($p['page'], $p['per_page'], $total)
    ];
  }

  public function actionChangePassword(): void
  {
    $data = Yii::$app->request->getBodyParams();
    $userService = new UserService;

    $old_password = trim((string)($data['old_password'] ?? null));
    $new_password = trim((string)($data['new_password'] ?? null));
    $new_password_repeat = trim((string)($data['new_password_repeat'] ?? null));

    if ($old_password === '' || $new_password === '' || $new_password_repeat === '') {
      throw new UnprocessableEntityApiException(ApiErrorCode::MISSING_PARAMETER, 'Не переданы параметры смены пароля пользователя');
    }

    $userService->changePassword($old_password, $new_password, $new_password_repeat);

    Yii::$app->response->statusCode = 204;
  }
}
