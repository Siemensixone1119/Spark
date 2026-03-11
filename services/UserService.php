<?php

namespace app\services;

use app\models\User;
use app\constants\ApiErrorCode;
use app\exceptions\BadRequestApiException;
use app\exceptions\NotFoundApiException;
use app\exceptions\ConflictApiException;
use app\exceptions\UnprocessableEntityApiException;
use app\exceptions\InternalServerApiException;
use Firebase\JWT\JWT;
use Throwable;
use Yii;

class UserService
{
  public function registerUser(string $username, string $email, string $password): void
  {
    $username = trim($username);
    $email = trim($email);
    $password = trim($password);

    if ($username === '' || $email === '' || $password === '') {
      throw new BadRequestApiException(
        ApiErrorCode::MISSING_PARAMETER,
        'username, email и password обязательны'
      );
    }

    if (User::find()->where(['email' => $email])->exists()) {
      throw new ConflictApiException(
        ApiErrorCode::EMAIL_ALREADY_EXISTS,
        'Email уже используется'
      );
    }

    if (User::find()->where(['username' => $username])->exists()) {
      throw new ConflictApiException(
        ApiErrorCode::USERNAME_ALREADY_EXISTS,
        'Username уже используется'
      );
    }

    $transaction = Yii::$app->db->beginTransaction();
    try {
      $user = new User();
      $user->username = $username;
      $user->email = $email;

      $user->setPassword($password);
      $user->generateAuthKey();

      if (!$user->save()) {
        throw new InternalServerApiException(
          ApiErrorCode::USER_CREATE_FAILED,
          'Ошибка при создании пользователя'
        );
      }

      $profileService = new ProfileService();
      $profileService->createProfile((int)$user->id);

      $transaction->commit();
    } catch (Throwable $e) {
      $transaction->rollBack();
      throw $e;
    }
  }

  public function authUser(string $email, string $password): array
  {
    $email = trim($email);
    $password = trim($password);

    if ($email === '' || $password === '') {
      throw new BadRequestApiException(
        ApiErrorCode::MISSING_PARAMETER,
        'email и password обязательны'
      );
    }

    $user = User::findOne(['email' => $email]);

    if (!$user) {
      throw new NotFoundApiException(
        ApiErrorCode::USER_NOT_FOUND,
        'Пользователь не найден'
      );
    }

    if (!$user->validatePassword($password)) {
      throw new BadRequestApiException(
        ApiErrorCode::PASSWORD_INVALID,
        'Неверный пароль'
      );
    }

    $token = $this->generateToken((int)$user->id);

    return [
      'access_token' => $token,
      'id' => $user->id,
      'expires_in' => Yii::$app->params['jwtExpire'],
    ];
  }

  public function changePassword(string $old_password, string $new_password, string $new_password_repeat): void
  {
    $id = (int)Yii::$app->user->id;

    $old_password = trim($old_password);
    $new_password = trim($new_password);
    $new_password_repeat = trim($new_password_repeat);

    $user = User::findOne($id);

    if (!$user) {
      throw new NotFoundApiException(
        ApiErrorCode::USER_NOT_FOUND,
        'Пользователь не найден'
      );
    }

    if (!$user->validatePassword($old_password)) {
      throw new BadRequestApiException(
        ApiErrorCode::PASSWORD_INVALID,
        'Неверный пароль'
      );
    }

    if ($new_password === '') {
      throw new UnprocessableEntityApiException(
        ApiErrorCode::PASSWORD_EMPTY,
        'Передан пустой пароль'
      );
    }

    if ($new_password !== $new_password_repeat) {
      throw new BadRequestApiException(
        ApiErrorCode::PASSWORD_MISMATCH,
        'Пароли не совпадают'
      );
    }

    $user->setPassword($new_password);

    if (!$user->save()) {
      throw new InternalServerApiException(
        ApiErrorCode::PASSWORD_CHANGE_FAILED,
        'Ошибка при смене пароля'
      );
    }
  }

  public function changeStatus(int $status): void
  {
    $id = (int)Yii::$app->user->id;
    $user = User::findOne(['id' => $id]);

    if (!$user) {
      throw new NotFoundApiException(
        ApiErrorCode::USER_NOT_FOUND,
        'Пользователь не найден'
      );
    }

    $user->status = $status;

    if (!$user->save()) {
      throw new InternalServerApiException(
        ApiErrorCode::USER_STATUS_CHANGE_FAILED,
        'Ошибка при смене статуса'
      );
    }
  }

  public function getUser(?string $email = null, ?string $username = null): array
  {
    if ($email !== null) {
      $email = trim($email);
      if ($email === '') {
        throw new BadRequestApiException(
          ApiErrorCode::INVALID_PARAMETER,
          'email не может быть пустым'
        );
      }
      $user = User::findOne(['email' => $email]);
    } elseif ($username !== null) {
      $username = trim($username);
      if ($username === '') {
        throw new BadRequestApiException(
          ApiErrorCode::INVALID_PARAMETER,
          'username не может быть пустым'
        );
      }
      $user = User::findOne(['username' => $username]);
    } else {
      throw new BadRequestApiException(
        ApiErrorCode::MISSING_PARAMETER,
        'Параметры не переданы'
      );
    }

    if (!$user) {
      throw new NotFoundApiException(
        ApiErrorCode::USER_NOT_FOUND,
        'Пользователь не найден'
      );
    }

    return [
      'username' => $user->username,
      'status' => $user->status,
    ];
  }

  public function generateToken(int $user_id): string
  {
    $payload = [
      'iss' => 'spark-server',
      'aud' => 'spark-client',
      'iat' => time(),
      'exp' => time() + Yii::$app->params['jwtExpire'],
      'sub' => $user_id,
    ];

    return JWT::encode($payload, Yii::$app->params['jwtSecret'], 'HS256');
  }
}
