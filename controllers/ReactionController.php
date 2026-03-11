<?php

namespace app\controllers;

use app\constants\ApiErrorCode;
use app\exceptions\BadRequestApiException;
use app\services\ReactionService;
use Yii;

class ReactionController extends BaseApiController
{
  public function actionToggle(string $target_type, int $target_id): array
  {
    if (trim($target_type) === '') {
      throw new BadRequestApiException(
        ApiErrorCode::MISSING_PARAMETER,
        'Не передан target_type'
      );
    }

    if ($target_id <= 0) {
      throw new BadRequestApiException(
        ApiErrorCode::INVALID_PARAMETER,
        'Некорректный target_id'
      );
    }

    $reactionService = new ReactionService();
    $result = $reactionService->toggleReaction($target_type, $target_id);

    Yii::$app->response->statusCode = 200;
    return $result;
  }

  public function actionIndex(string $target_type, int $target_id): array
  {
    if (trim($target_type) === '') {
      throw new BadRequestApiException(
        ApiErrorCode::MISSING_PARAMETER,
        'Не передан target_type'
      );
    }

    if ($target_id <= 0) {
      throw new BadRequestApiException(
        ApiErrorCode::INVALID_PARAMETER,
        'Некорректный target_id'
      );
    }

    $reactionService = new ReactionService();
    $reactions = $reactionService->getReactionByTarget($target_type, $target_id);

    Yii::$app->response->statusCode = 200;
    return $reactions;
  }
}
