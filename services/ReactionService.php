<?php

namespace app\services;

use app\models\Comment;
use app\models\Post;
use app\models\Reaction;
use app\constants\ApiErrorCode;
use app\exceptions\BadRequestApiException;
use app\exceptions\NotFoundApiException;
use app\exceptions\InternalServerApiException;
use Throwable;
use Yii;
use yii\db\IntegrityException;

class ReactionService
{
  public function toggleReaction(string $target_type, int $target_id): array
  {
    $user_id = (int)Yii::$app->user->id;

    if ($target_type === Reaction::TARGET_POST) {
      $targetExists = Post::find()->where(['id' => $target_id])->exists();
      if (!$targetExists) {
        throw new NotFoundApiException(
          ApiErrorCode::POST_NOT_FOUND,
          'Пост не найден'
        );
      }
    } elseif ($target_type === Reaction::TARGET_COMMENT) {
      $targetExists = Comment::find()->where(['id' => $target_id])->exists();
      if (!$targetExists) {
        throw new NotFoundApiException(
          ApiErrorCode::COMMENT_NOT_FOUND,
          'Комментарий не найден'
        );
      }
    } else {
      throw new BadRequestApiException(
        ApiErrorCode::INVALID_PARAMETER,
        'Неверный тип реакции'
      );
    }

    $deleted = Reaction::deleteAll([
      'user_id' => $user_id,
      'target_id' => $target_id,
      'target_type' => $target_type,
    ]);

    if ($deleted > 0) {
      $count = (int)Reaction::find()
        ->where(['target_type' => $target_type, 'target_id' => $target_id])
        ->count();

      return ['added' => false, 'count' => $count, 'my' => false];
    }

    try {
      $reaction = new Reaction();
      $reaction->user_id = $user_id;
      $reaction->target_id = $target_id;
      $reaction->target_type = $target_type;

      if (!$reaction->save()) {
        throw new InternalServerApiException(
          ApiErrorCode::REACTION_SAVE_FAILED,
          'Ошибка при сохранении реакции'
        );
      }
    } catch (IntegrityException $e) {
    } catch (Throwable $e) {
      throw new InternalServerApiException(
        ApiErrorCode::INTERNAL_ERROR,
        'Ошибка при сохранении реакции'
      );
    }

    $count = (int)Reaction::find()
      ->where(['target_type' => $target_type, 'target_id' => $target_id])
      ->count();

    $my = (bool)Reaction::find()
      ->where([
        'user_id' => $user_id,
        'target_id' => $target_id,
        'target_type' => $target_type,
      ])
      ->exists();

    return [
      'added' => $my,
      'count' => $count,
      'my' => $my,
    ];
  }

  public function getReactionByTarget(string $target_type, int $target_id): array
  {
    $count = (int)Reaction::find()
      ->where(['target_id' => $target_id, 'target_type' => $target_type])
      ->count();

    $my = (bool)Reaction::find()
      ->where([
        'user_id' => (int)Yii::$app->user->id,
        'target_id' => $target_id,
        'target_type' => $target_type,
      ])
      ->exists();

    return [
      'count' => $count,
      'my' => $my,
    ];
  }

  public function hasReaction(string $target_type, int $target_id): bool
  {
    return (bool)Reaction::find()
      ->where([
        'user_id' => (int)Yii::$app->user->id,
        'target_id' => $target_id,
        'target_type' => $target_type,
      ])
      ->exists();
  }
}
