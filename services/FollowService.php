<?php

namespace app\services;

use Yii;
use app\models\Follow;
use app\constants\ApiErrorCode;
use app\exceptions\BadRequestApiException;
use app\exceptions\ConflictApiException;
use app\exceptions\NotFoundApiException;
use app\exceptions\InternalServerApiException;
use app\exceptions\UnauthorizedApiException;

class FollowService
{
  public function follow(int $followed_id): array
  {
    $follower_id = (int)Yii::$app->user->id;

    if ($follower_id === $followed_id) {
      throw new BadRequestApiException(
        ApiErrorCode::INVALID_PARAMETER,
        'Нельзя подписаться на самого себя'
      );
    }

    $exists = Follow::find()->where([
      'follower_id' => $follower_id,
      'followed_id' => $followed_id,
    ])->exists();

    if ($exists) {
      throw new ConflictApiException(
        ApiErrorCode::CONFLICT,
        'Вы уже подписаны на этого пользователя'
      );
    }

    $follow = new Follow();
    $follow->follower_id = $follower_id;
    $follow->followed_id = $followed_id;

    $follower_id = (int)Yii::$app->user->id;
    if ($follower_id <= 0) {
      throw new UnauthorizedApiException(ApiErrorCode::UNAUTHORIZED, 'Нужно авторизоваться');
    }

    if (!$follow->save()) {
      throw new InternalServerApiException(
        ApiErrorCode::SAVE_FAILED,
        'Ошибка при попытке подписаться'
      );
    }

    return [
      'follower_id' => $follow->follower_id,
      'followed_id' => $follow->followed_id,
    ];
  }

  public function unfollow(int $followed_id): void
  {
    $follower_id = (int)Yii::$app->user->id;

    $follower_id = (int)Yii::$app->user->id;
    if ($follower_id <= 0) {
      throw new UnauthorizedApiException(ApiErrorCode::UNAUTHORIZED, 'Нужно авторизоваться');
    }

    $follow = Follow::findOne([
      'follower_id' => $follower_id,
      'followed_id' => $followed_id,
    ]);

    if (!$follow) {
      throw new NotFoundApiException(
        ApiErrorCode::NOT_FOUND,
        'Вы не подписаны на этого пользователя'
      );
    }

    if (!$follow->delete()) {
      throw new InternalServerApiException(
        ApiErrorCode::DELETE_FAILED,
        'Ошибка при отписке'
      );
    }
  }

  public function isFollowing(int $followed_id): bool
  {
    $follower_id = (int)Yii::$app->user->id;

    return (bool)Follow::find()->where([
      'follower_id' => $follower_id,
      'followed_id' => $followed_id,
    ])->exists();
  }

  public function getFollowers(int $user_id, int $offset, int $limit): array
  {
    $query = Follow::find()->where(['followed_id' => $user_id]);

    $total = (int)(clone $query)->count();

    $followers = $query
      ->orderBy(['id' => SORT_DESC])
      ->offset($offset)
      ->limit($limit)
      ->asArray()
      ->all();

    return [$followers, $total];
  }

  public function getFollowing(int $user_id, int $offset, int $limit): array
  {
    $query = Follow::find()->where(['follower_id' => $user_id]);

    $total = (int)(clone $query)->count();

    $following = $query
      ->orderBy(['id' => SORT_DESC])
      ->offset($offset)
      ->limit($limit)
      ->asArray()
      ->all();

    return [$following, $total];
  }
}
