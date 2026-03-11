<?php

namespace app\controllers;

use app\components\Pagination;
use app\services\PostService;
use Yii;
use app\constants\ApiErrorCode;
use app\exceptions\BadRequestApiException;

class PostController extends BaseApiController
{
  public function actionCreate(): array
  {
    $data = Yii::$app->request->getBodyParams();

    if (!array_key_exists('content', $data) || !array_key_exists('visibility', $data)) {
      throw new BadRequestApiException(
        ApiErrorCode::MISSING_PARAMETER,
        'Не переданы параметры поста'
      );
    }

    $postService = new PostService();
    $post = $postService->createPost((string)$data['content'], (int)$data['visibility']);

    Yii::$app->response->statusCode = 201;
    return $post;
  }

  public function actionView(int $id): array
  {
    if ($id <= 0) {
      throw new BadRequestApiException(
        ApiErrorCode::MISSING_PARAMETER,
        'id поста не передан'
      );
    }

    $postService = new PostService();
    $post = $postService->getPostById($id);

    Yii::$app->response->statusCode = 200;
    return $post;
  }

  public function actionMy(): array
  {
    $p = Pagination::fromRequest(2, 3);

    $postService = new PostService();
    [$posts, $total] = $postService->getMyPosts($p['offset'], $p['limit']);

    Yii::$app->response->statusCode = 200;
    return [
      'data' => $posts,
      'meta' => Pagination::meta($p['page'], $p['per_page'], $total)
    ];
  }

  public function actionDelete(int $id): void
  {
    if ($id <= 0) {
      throw new BadRequestApiException(
        ApiErrorCode::MISSING_PARAMETER,
        'id поста не передан'
      );
    }

    $postService = new PostService();
    $postService->removePost($id);

    Yii::$app->response->statusCode = 204;
  }

  public function actionUpdate(int $id): array
  {
    if ($id <= 0) {
      throw new BadRequestApiException(
        ApiErrorCode::MISSING_PARAMETER,
        'id поста не передан'
      );
    }

    $body = Yii::$app->request->getBodyParams();

    $data = [];
    if (array_key_exists('content', $body)) {
      $data['content'] = $body['content'];
    }
    if (array_key_exists('visibility', $body)) {
      $data['visibility'] = $body['visibility'];
    }

    $postService = new PostService();
    $post = $postService->updatePost($id, $data);

    Yii::$app->response->statusCode = 200;
    return $post;
  }
}
