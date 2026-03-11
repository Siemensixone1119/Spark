<?php

namespace app\components;

use Yii;
use app\constants\ApiErrorCode;
use app\exceptions\BadRequestApiException;

final class Pagination
{
  public static function fromRequest(int $defaultPerPage = 20, int $maxPerPage = 100): array
  {
    $page = (int)Yii::$app->request->get('page', 1);
    $perPage = (int)Yii::$app->request->get('per_page', $defaultPerPage);

    if ($page < 1) {
      throw new BadRequestApiException(ApiErrorCode::INVALID_PARAMETER, 'Некорректный page');
    }

    if ($perPage < 1 || $perPage > $maxPerPage) {
      throw new BadRequestApiException(ApiErrorCode::INVALID_PARAMETER, 'Некорректный per_page');
    }

    return [
      'page' => $page,
      'per_page' => $perPage,
      'offset' => ($page - 1) * $perPage,
      'limit' => $perPage,
    ];
  }

  public static function meta(int $page, int $perPage, int $total): array
  {
    $pages = $perPage > 0 ? (int)ceil($total / $perPage) : 0;

    return [
      'page' => $page,
      'per_page' => $perPage,
      'total' => $total,
      'pages' => $pages,
      'has_prev' => $page > 1,
      'has_next' => $pages > 0 && $page < $pages,
    ];
  }
}
