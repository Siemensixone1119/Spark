<?php

namespace app\services;

use app\models\Post;
use app\constants\ApiErrorCode;
use app\exceptions\BadRequestApiException;
use app\exceptions\ForbiddenApiException;
use app\exceptions\NotFoundApiException;
use app\exceptions\UnprocessableEntityApiException;
use app\exceptions\InternalServerApiException;
use Yii;
use app\components\Pagination;

class PostService
{
  public function createPost(string $content, int $visibility): array
  {
    $access = Yii::$app->user->can('post.create');
    if (!$access) {
      throw new ForbiddenApiException(
        ApiErrorCode::ACCESS_DENIED,
        'Недостаточно прав для создания поста'
      );
    }

    $content = trim((string)$content);

    if ($content === '') {
      throw new UnprocessableEntityApiException(
        ApiErrorCode::POST_CONTENT_EMPTY,
        'Передан пустой контент'
      );
    }

    if ($visibility !== 10 && $visibility !== -10) {
      throw new BadRequestApiException(
        ApiErrorCode::INVALID_PARAMETER,
        'Передан неверный идентификатор доступа'
      );
    }

    $post = new Post();
    $post->user_id = (int)Yii::$app->user->id;
    $post->content = $content;
    $post->visibility = (int)$visibility;

    if (!$post->save()) {
      throw new InternalServerApiException(
        ApiErrorCode::POST_CREATE_FAILED,
        'Ошибка при добавлении поста'
      );
    }

    return [
      'post_id' => $post->id,
      'user_id' => $post->user_id,
      'content' => $post->content,
      'visibility' => $post->visibility
    ];
  }

  public function removePost(int $post_id): void
  {
    $post = Post::findOne(['id' => $post_id]);

    if (!$post) {
      throw new NotFoundApiException(
        ApiErrorCode::POST_NOT_FOUND,
        'Пост не найден'
      );
    }

    $access = Yii::$app->user->can('post.delete') || Yii::$app->user->can('post.deleteOwn', ['post' => $post]);
    if (!$access) {
      throw new ForbiddenApiException(
        ApiErrorCode::ACCESS_DENIED,
        'Недостаточно прав для удаления поста'
      );
    }

    if (!$post->delete()) {
      throw new InternalServerApiException(
        ApiErrorCode::POST_DELETE_FAILED,
        'Ошибка при удалении поста'
      );
    }
  }

  public function hasPost(): bool
  {
    $user_id = (int)Yii::$app->user->id;

    return (bool)Post::find()
      ->where(['user_id' => $user_id])
      ->exists();
  }

  public function getPostById(int $id): array
  {
    $post = Post::findOne($id);

    if (!$post) {
      throw new NotFoundApiException(
        ApiErrorCode::POST_NOT_FOUND,
        'Пост не найден'
      );
    }

    if ((int)$post->visibility === -10) {
      $access = Yii::$app->user->can('post.viewOwn', ['post' => $post]);
      if (!$access) {
        throw new ForbiddenApiException(
          ApiErrorCode::ACCESS_DENIED,
          'Недостаточно прав для просмотра поста'
        );
      }
    }

    return [
      'id' => $post->id,
      'content' => $post->content,
      'visibility' => $post->visibility,
    ];
  }

  public function getMyPosts(int $offset, int $limit)
  {
    $user_id = (int)Yii::$app->user->id;

    $query = Post::find()->where(['user_id' => $user_id]);
    $total = (int)(clone $query)->count();
    $posts = $query
      ->orderBy(['created_at' => SORT_DESC])
      ->offset($offset)
      ->limit($limit)
      ->asArray()
      ->all();

    return [$posts, $total];
  }

  public function updatePost(int $id, array $data): array
  {
    $post = Post::findOne($id);

    if (!$post) {
      throw new NotFoundApiException(
        ApiErrorCode::POST_NOT_FOUND,
        'Пост не найден'
      );
    }

    $access = Yii::$app->user->can('post.update') || Yii::$app->user->can('post.updateOwn', ['post' => $post]);
    if (!$access) {
      throw new ForbiddenApiException(
        ApiErrorCode::ACCESS_DENIED,
        'Недостаточно прав для обновления поста'
      );
    }

    $hasContent = array_key_exists('content', $data);
    $hasVisibility = array_key_exists('visibility', $data);

    if (!$hasContent && !$hasVisibility) {
      throw new BadRequestApiException(
        ApiErrorCode::MISSING_PARAMETER,
        'Нет данных для обновления'
      );
    }

    if ($hasContent) {
      $content = trim((string)$data['content']);
      if ($content === '') {
        throw new UnprocessableEntityApiException(
          ApiErrorCode::POST_CONTENT_EMPTY,
          'Передан пустой контент'
        );
      }
      $post->content = $content;
    }

    if ($hasVisibility) {
      $visibility = (int)$data['visibility'];
      if ($visibility !== 10 && $visibility !== -10) {
        throw new BadRequestApiException(
          ApiErrorCode::INVALID_PARAMETER,
          'Передан неверный идентификатор доступа'
        );
      }
      $post->visibility = $visibility;
    }

    if (!$post->save()) {
      throw new InternalServerApiException(
        ApiErrorCode::POST_UPDATE_FAILED,
        'Ошибка при сохранении'
      );
    }

    return [
      'post_id' => $post->id,
      'user_id' => $post->user_id,
      'content' => $post->content,
      'visibility' => $post->visibility
    ];
  }
}
