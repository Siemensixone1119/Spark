<?php

namespace app\controllers;

use app\components\Pagination;
use app\constants\ApiErrorCode;
use app\exceptions\BadRequestApiException;
use Yii;
use app\services\FollowService;

class FollowController extends BaseApiController
{
  public function actionFollow(int $user_id): array
  {
    $this->assertUserId($user_id);

    $followService = new FollowService();
    $follow = $followService->follow($user_id);

    Yii::$app->response->statusCode = 201;
    return $follow;
  }

  public function actionUnfollow(int $user_id): void
  {
    $this->assertUserId($user_id);

    $followService = new FollowService();
    $followService->unfollow($user_id);

    Yii::$app->response->statusCode = 204;
  }

  public function actionIsFollowing(int $user_id): array
  {
    $this->assertUserId($user_id);

    $followService = new FollowService();
    $following = $followService->isFollowing($user_id);

    Yii::$app->response->statusCode = 200;
    return ['is_following' => $following];
  }

  public function actionFollowers(int $user_id): array
  {
    $this->assertUserId($user_id);

    $p = Pagination::fromRequest(20, 100);

    $followService = new FollowService();
    [$followers, $total] = $followService->getFollowers($user_id, $p['offset'], $p['limit']);

    Yii::$app->response->statusCode = 200;
    return [
      'data' => $followers,
      'meta' => Pagination::meta($p['page'], $p['per_page'], $total),
    ];
  }

  public function actionFollowing(int $user_id): array
  {
    $this->assertUserId($user_id);

    $p = Pagination::fromRequest(20, 100);

    $followService = new FollowService();
    [$following, $total] = $followService->getFollowing($user_id, $p['offset'], $p['limit']);

    Yii::$app->response->statusCode = 200;
    return [
      'data' => $following,
      'meta' => Pagination::meta($p['page'], $p['per_page'], $total),
    ];
  }

  private function assertUserId(int $user_id): void
  {
    if ($user_id <= 0) {
      throw new BadRequestApiException(
        ApiErrorCode::INVALID_PARAMETER,
        'Некорректный id'
      );
    }
  }
}
