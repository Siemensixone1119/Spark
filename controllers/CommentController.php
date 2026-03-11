<?php

namespace app\controllers;

use Yii;
use app\services\CommentService;
use app\constants\ApiErrorCode;
use app\exceptions\BadRequestApiException;
use app\exceptions\UnprocessableEntityApiException;
use app\components\Pagination;

class CommentController extends BaseApiController
{
  public function actionCreate(int $post_id): array
  {
    if ($post_id <= 0) {
      throw new UnprocessableEntityApiException(
        ApiErrorCode::MISSING_PARAMETER,
        'id поста не передан'
      );
    }

    $data = Yii::$app->request->getBodyParams();

    if (!array_key_exists('content', $data)) {
      throw new BadRequestApiException(
        ApiErrorCode::MISSING_PARAMETER,
        'Не передан параметр content'
      );
    }

    $content = trim((string)$data['content']);
    if ($content === '') {
      throw new UnprocessableEntityApiException(
        ApiErrorCode::COMMENT_CONTENT_EMPTY,
        'Нельзя оставить пустой комментарий'
      );
    }

    $commentService = new CommentService();
    $comment = $commentService->create($post_id, $content);

    Yii::$app->response->statusCode = 201;
    return $comment;
  }

  public function actionList(int $post_id): array
  {
    if ($post_id <= 0) {
      throw new UnprocessableEntityApiException(
        ApiErrorCode::MISSING_PARAMETER,
        'id поста не передан'
      );
    }

    $p = Pagination::fromRequest(2, 3);

    $commentService = new CommentService();
    [$comments, $total] = $commentService->getPostComments($post_id, $p['offset'], $p['limit']);

    Yii::$app->response->statusCode = 200;
    return [
      'data' => $comments,
      'meta' => Pagination::meta($p['page'], $p['per_page'], $total)
    ];
  }

  public function actionUpdate(int $comment_id): array
  {
    if ($comment_id <= 0) {
      throw new BadRequestApiException(
        ApiErrorCode::MISSING_PARAMETER,
        'id комментария не передан'
      );
    }

    $data = Yii::$app->request->getBodyParams();

    $commentService = new CommentService();
    $comment = $commentService->updateComment($comment_id, $data);

    Yii::$app->response->statusCode = 200;
    return $comment;
  }

  public function actionDelete(int $comment_id): void
  {
    if ($comment_id <= 0) {
      throw new BadRequestApiException(
        ApiErrorCode::MISSING_PARAMETER,
        'id комментария не передан'
      );
    }

    $commentService = new CommentService();
    $commentService->removeComment($comment_id);

    Yii::$app->response->statusCode = 204;
  }
}
