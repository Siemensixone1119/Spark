<?php

namespace app\services;

use app\models\UserSession;
use app\constants\ApiErrorCode;
use app\exceptions\BadRequestApiException;
use app\exceptions\NotFoundApiException;
use app\exceptions\ConflictApiException;
use app\exceptions\UnprocessableEntityApiException;
use app\exceptions\InternalServerApiException;
use Throwable;
use Yii;

class UserSessionService
{
  public function createSession(int $user_id, ?string $user_agent = null, ?string $ip_address = null, ?int $rotated_from = null): array
  {
    if ($user_id <= 0) {
      throw new BadRequestApiException(
        ApiErrorCode::INVALID_PARAMETER,
        'Некорректный user_id'
      );
    }

    $session = new UserSession();
    $session->generateRefreshToken();

    $session->user_id = $user_id;
    $session->expires_at = time() + Yii::$app->params['refreshExpire'];
    $session->user_agent = $user_agent;
    $session->ip_address = $ip_address;
    $session->last_activity = time();
    $session->rotated_from = $rotated_from;

    if (!$session->save()) {
      throw new InternalServerApiException(
        ApiErrorCode::SESSION_CREATE_FAILED,
        'Не удалось создать сессию. Попробуйте позже.'
      );
    }

    return [
      'refresh_token' => $session->refresh_token,
      'expires_at' => $session->expires_at,
      'user_agent' => $session->user_agent,
      'ip_address' => $session->ip_address,
    ];
  }

  public function refreshByToken(string $refresh_token, string $user_agent, string $ip_address): array
  {
    $refresh_token = trim($refresh_token);

    if ($refresh_token === '') {
      throw new BadRequestApiException(
        ApiErrorCode::TOKEN_MISSING,
        'Refresh-токен не передан'
      );
    }

    $transaction = Yii::$app->db->beginTransaction();

    try {
      $session = $this->getSession($refresh_token);
      $rotated_from = (int)$session->id;

      if ($session->revoked_at !== null) {
        $this->removeAllSessions((int)$session->user_id);
        throw new ConflictApiException(
          ApiErrorCode::SESSION_COMPROMISED,
          'Обнаружено повторное использование refresh-токена. Все сессии пользователя отозваны.'
        );
      }

      if ((int)$session->expires_at < time()) {
        throw new UnprocessableEntityApiException(
          ApiErrorCode::REFRESH_EXPIRED,
          'Срок действия refresh-токена истёк. Выполните вход заново.'
        );
      }

      if ((string)$session->user_agent !== (string)$user_agent) {
        $this->removeAllSessions((int)$session->user_id);
        throw new ConflictApiException(
          ApiErrorCode::SESSION_COMPROMISED,
          'Параметры устройства не совпадают (user-agent). Возможна кража refresh-токена. Все сессии отозваны.'
        );
      }

      $session->revoked_at = time();
      $session->save(false);

      $new = $this->createSession((int)$session->user_id, $user_agent, $ip_address, $rotated_from);

      $transaction->commit();

      return [
        'refresh_token' => $new['refresh_token'],
        'expires_at' => $new['expires_at'],
        'user_id' => (int)$session->user_id,
      ];
    } catch (Throwable $e) {
      $transaction->rollBack();
      throw $e;
    }
  }

  public function getSession(string $refresh_token): UserSession
  {
    $session = UserSession::findOne(['refresh_token' => $refresh_token]);

    if (!$session) {
      throw new NotFoundApiException(
        ApiErrorCode::SESSION_NOT_FOUND,
        'Сессия не найдена или токен недействителен'
      );
    }

    return $session;
  }

  public function getSessions(): array
  {
    $user_id = (int)Yii::$app->user->id;

    $query = UserSession::find()
      ->select(['id', 'created_at', 'expires_at', 'user_agent', 'ip_address'])
      ->where(['user_id' => $user_id]);

    $total = (int)(clone $query)->count();
    $sessions = $query
      ->orderBy(['created_at' => SORT_DESC])
      ->asArray()
      ->all();

    return [$sessions, $total];
  }

  public function getCurrentSession(string $refresh_token): ?array
  {
    $refresh_token = trim($refresh_token);

    if ($refresh_token === '') {
      return null;
    }

    $currentSession = UserSession::findOne(['refresh_token' => $refresh_token]);

    if (!$currentSession) {
      return null;
    }

    return [
      'id' => $currentSession->id,
      'created_at' => $currentSession->created_at,
      'expires_at' => $currentSession->expires_at,
      'user_agent' => $currentSession->user_agent,
      'ip_address' => $currentSession->ip_address,
    ];
  }

  public function removeSessionByToken(string $refresh_token): void
  {
    $refresh_token = trim($refresh_token);

    if ($refresh_token === '') {
      return;
    }

    $session = UserSession::findOne(['refresh_token' => $refresh_token]);

    if (!$session) {
      return;
    }

    if (!$session->delete()) {
      throw new InternalServerApiException(
        ApiErrorCode::SESSION_DELETE_FAILED,
        'Не удалось удалить сессию'
      );
    }
  }

  public function removeSessionById(int $session_id): void
  {
    $user_id = (int)Yii::$app->user->id;

    $session = UserSession::findOne(['id' => $session_id, 'user_id' => $user_id]);

    if (!$session) {
      return;
    }

    if (!$session->delete()) {
      throw new InternalServerApiException(
        ApiErrorCode::SESSION_DELETE_FAILED,
        'Не удалось удалить сессию'
      );
    }
  }

  public function removeAllSessions(int $user_id): int
  {
    return (int)UserSession::deleteAll(['user_id' => $user_id]);
  }

  public function touchByToken(string $refresh_token): void
  {
    UserSession::updateAll(['last_activity' => time()], ['refresh_token' => $refresh_token]);
  }
}
