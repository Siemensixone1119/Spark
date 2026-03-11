<?php

namespace app\services;

use app\models\Comment;
use app\constants\ApiErrorCode;
use app\exceptions\BadRequestApiException;
use app\exceptions\ForbiddenApiException;
use app\exceptions\NotFoundApiException;
use app\exceptions\UnprocessableEntityApiException;
use app\exceptions\InternalServerApiException;
use Yii;

class CommentService
{
  public function create(int $post_id, string $content): array
  {
    $access = Yii::$app->user->can('comment.create');
    if (!$access) {
      throw new ForbiddenApiException(
        ApiErrorCode::ACCESS_DENIED,
        'Недостаточно прав для создания комментария'
      );
    }

    $content = trim($content);

    // 422 — данные не прошли валидацию
    if ($content === '') {
      throw new UnprocessableEntityApiException(
        ApiErrorCode::COMMENT_CONTENT_EMPTY,
        'Текст комментария не может быть пустым.'
      );
    }

    $comment = new Comment();
    $comment->user_id = (int)Yii::$app->user->id;
    $comment->post_id = $post_id;
    $comment->content = $content;

    // 500 — техническая ошибка (например, БД/валидация модели)
    if (!$comment->save()) {
      throw new InternalServerApiException(
        ApiErrorCode::COMMENT_CREATE_FAILED,
        'Не удалось создать комментарий. Попробуйте позже.'
      );
    }

    return [
      'comment_id' => (int)$comment->id,
      'user_id' => (int)$comment->user_id,
      'post_id' => (int)$comment->post_id,
      'content' => (string)$comment->content,
      'created_at' => (int)$comment->created_at,
    ];
  }

  public function removeComment(int $comment_id): void
  {
    $comment = Comment::findOne($comment_id);

    if (!$comment) {
      throw new NotFoundApiException(
        ApiErrorCode::COMMENT_NOT_FOUND,
        'Комментарий не найден.'
      );
    }

    $access = Yii::$app->user->can('comment.delete') || Yii::$app->user->can('comment.deleteOwn', ['comment' => $comment]);
    if (!$access) {
      throw new ForbiddenApiException(
        ApiErrorCode::ACCESS_DENIED,
        'Недостаточно прав для удаления комментария'
      );
    }

    if (!$comment->delete()) {
      throw new InternalServerApiException(
        ApiErrorCode::COMMENT_DELETE_FAILED,
        'Не удалось удалить комментарий. Попробуйте позже.'
      );
    }
  }

  public function hasComments(int $post_id): bool
  {
    return (bool)Comment::find()->where(['post_id' => $post_id])->exists();
  }

  public function getPostComments(int $post_id, int $offset, int $limit): array
  {
    $query = Comment::find()->where(['post_id' => $post_id]);
    $total = (int)(clone $query)->count();
    $comments = $query
      ->orderBy(['created_at' => SORT_DESC])
      ->offset($offset)
      ->limit($limit)
      ->asArray()
      ->all();

    return [$comments, $total];
  }

  public function updateComment(int $comment_id, array $data): array
  {
    $comment = Comment::findOne($comment_id);

    if (!$comment) {
      throw new NotFoundApiException(
        ApiErrorCode::COMMENT_NOT_FOUND,
        'Комментарий не найден.'
      );
    }

    $access = Yii::$app->user->can('comment.update') || Yii::$app->user->can('comment.updateOwn', ['comment' => $comment]);
    if (!$access) {
      throw new ForbiddenApiException(
        ApiErrorCode::ACCESS_DENIED,
        'Недостаточно прав для обновления комментария'
      );
    }

    // 400 — параметр не передан (формат запроса)
    if (!array_key_exists('content', $data)) {
      throw new BadRequestApiException(
        ApiErrorCode::MISSING_PARAMETER,
        'Не передан параметр content.'
      );
    }

    $content = trim((string)$data['content']);

    // 422 — передан, но невалидный
    if ($content === '') {
      throw new UnprocessableEntityApiException(
        ApiErrorCode::COMMENT_CONTENT_EMPTY,
        'Текст комментария не может быть пустым.'
      );
    }

    $comment->content = $content;

    if (!$comment->save()) {
      throw new InternalServerApiException(
        ApiErrorCode::COMMENT_UPDATE_FAILED,
        'Не удалось обновить комментарий. Попробуйте позже.'
      );
    }

    return [
      'comment_id' => (int)$comment->id,
      'user_id' => (int)$comment->user_id,
      'post_id' => (int)$comment->post_id,
      'content' => (string)$comment->content,
      'created_at' => (int)$comment->created_at,
    ];
  }
}
