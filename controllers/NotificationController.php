<?php

namespace app\controllers;

use app\components\Pagination;
use Yii;
use app\services\NotificationService;
use app\constants\ApiErrorCode;
use app\exceptions\BadRequestApiException;

class NotificationController extends BaseApiController
{
  public function actionIndex(): array
  {
    $p = Pagination::fromRequest(2, 3);

    $notification_service = new NotificationService();
    [$notifications, $total] = $notification_service->getUserNotifications($p['offset'], $p['limit']);

    Yii::$app->response->statusCode = 200;
    return [
      'data' => $notifications,
      'total' => Pagination::meta($p['page'], $p['per_page'], $total)
    ];
  }

  public function actionUnread(): array
  {
    $p = Pagination::fromRequest(2, 3);

    $notification_service = new NotificationService();
    [$notifications, $total] = $notification_service->getUnreadNotification($p['offset'], $p['limit']);

    Yii::$app->response->statusCode = 200;
    return [
      'data' => $notifications,
      'total' => Pagination::meta($p['page'], $p['per_page'], $total)
    ];
  }

  public function actionUnreadCount(): int
  {
    $notification_service = new NotificationService();
    $notifications_count = $notification_service->getUnreadNotificationCount();

    Yii::$app->response->statusCode = 200;
    return $notifications_count;
  }

  public function actionMarkRead(): array
  {
    $notification_service = new NotificationService();
    $data = Yii::$app->request->getBodyParams();

    $notification_id = isset($data['notification_id']) ? (int)$data['notification_id'] : 0;
    $is_all_read = !empty($data['read_all']);

    if ($notification_id <= 0 && !$is_all_read) {
      throw new BadRequestApiException(
        ApiErrorCode::MISSING_PARAMETER,
        'не переданы параметры запроса'
      );
    }

    if ($is_all_read) {
      $updated = $notification_service->markAllAsRead();

      Yii::$app->response->statusCode = 200;
      return ['updated' => $updated];
    }

    $notification_service->markAsRead($notification_id);

    Yii::$app->response->statusCode = 200;
    return ['updated' => 1];
  }
}
