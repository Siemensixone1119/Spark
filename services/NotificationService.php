<?php

namespace app\services;

use app\models\Notification;
use app\constants\ApiErrorCode;
use app\exceptions\BadRequestApiException;
use app\exceptions\NotFoundApiException;
use app\exceptions\InternalServerApiException;
use Yii;

class NotificationService
{
  public function createNotification(int $user_id, string $type, array $payload): void
  {
    if ($user_id <= 0) {
      throw new BadRequestApiException(
        ApiErrorCode::INVALID_PARAMETER,
        'Некорректный user_id'
      );
    }

    $type = trim($type);
    if ($type === '') {
      throw new BadRequestApiException(
        ApiErrorCode::MISSING_PARAMETER,
        'Не передан type'
      );
    }

    $notification = new Notification();
    $notification->user_id = $user_id;
    $notification->type = $type;
    $notification->payload = $payload;

    if (!$notification->save()) {
      throw new InternalServerApiException(
        ApiErrorCode::NOTIFICATION_CREATE_FAILED,
        'Ошибка при создании уведомления'
      );
    }
  }

  public function markAsRead(int $notification_id): void
  {
    $user_id = (int)Yii::$app->user->id;

    $notification = Notification::findOne([
      'id' => $notification_id,
      'user_id' => $user_id,
    ]);

    if (!$notification) {
      throw new NotFoundApiException(
        ApiErrorCode::NOTIFICATION_NOT_FOUND,
        'Уведомление не найдено'
      );
    }

    $notification->is_read = 1;

    if (!$notification->save(false, ['is_read'])) {
      throw new InternalServerApiException(
        ApiErrorCode::NOTIFICATION_UPDATE_FAILED,
        'Ошибка при обновлении уведомления'
      );
    }
  }

  public function hasNotification(int $notification_id, int $user_id): bool
  {
    return (bool)Notification::find()->where([
      'id' => $notification_id,
      'user_id' => $user_id,
    ])->exists();
  }

  public function markAllAsRead(): int
  {
    $user_id = (int)Yii::$app->user->id;

    return (int)Notification::updateAll(
      ['is_read' => 1],
      ['user_id' => $user_id, 'is_read' => 0]
    );
  }

  public function getUserNotifications(int $offset, int $limit): array
  {
    $user_id = (int)Yii::$app->user->id;

    $query = Notification::find()
      ->where(['user_id' => $user_id]);

    $total = (int)(clone $query)->count();
    $notifications = $query
      ->orderBy(['created_at' => SORT_DESC])
      ->offset($offset)
      ->limit($limit)
      ->asArray()
      ->all();

    return [$notifications, $total];
  }

  public function getUnreadNotification(int $offset, int $limit): array
  {
    $user_id = (int)Yii::$app->user->id;

    $query = Notification::find()
      ->where(['user_id' => $user_id, 'is_read' => 0]);

    $total = (int)(clone $query)->count();
    $notifications = $query
      ->orderBy(['created_at' => SORT_DESC])
      ->offset($offset)
      ->limit($limit)
      ->asArray()
      ->all();

    return [$notifications, $total];
  }

  public function getUnreadNotificationCount(): int
  {
    $user_id = (int)Yii::$app->user->id;

    return Notification::find()
      ->where(['user_id' => $user_id, 'is_read' => 0])
      ->orderBy(['created_at' => SORT_DESC])
      ->count();
  }

  public function hasUnread(): bool
  {
    $user_id = (int)Yii::$app->user->id;

    return (bool)Notification::find()
      ->where(['user_id' => $user_id, 'is_read' => 0])
      ->exists();
  }
}
