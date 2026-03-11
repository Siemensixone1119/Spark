<?php

namespace app\services;

use app\models\Profile;
use app\constants\ApiErrorCode;
use app\exceptions\BadRequestApiException;
use app\exceptions\NotFoundApiException;
use app\exceptions\UnprocessableEntityApiException;
use app\exceptions\InternalServerApiException;
use Yii;

class ProfileService
{
  public function createProfile(int $user_id): void
  {
    if ($user_id <= 0) {
      throw new BadRequestApiException(
        ApiErrorCode::INVALID_PARAMETER,
        'Некорректный user_id'
      );
    }

    $profile = new Profile();
    $profile->user_id = $user_id;

    if (!$profile->save()) {
      throw new InternalServerApiException(
        ApiErrorCode::PROFILE_CREATE_FAILED,
        'Ошибка при создании профиля'
      );
    }
  }

  public function updateProfile(?string $display_name = null, ?string $bio = null, ?string $avatar = null, ?string $birth_date = null): array
  {
    $user_id = (int)Yii::$app->user->id;

    $profile = Profile::findOne(['user_id' => $user_id]);
    if (!$profile) {
      throw new NotFoundApiException(
        ApiErrorCode::PROFILE_NOT_FOUND,
        'Профиль не найден'
      );
    }

    $changed = false;

    if ($display_name !== null) {
      $display_name = trim($display_name);
      if ($display_name === '') {
        throw new UnprocessableEntityApiException(
          ApiErrorCode::PROFILE_DISPLAY_NAME_EMPTY,
          'display_name не может быть пустым'
        );
      }
      $profile->display_name = $display_name;
      $changed = true;
    }

    if ($bio !== null) {
      $bio = trim($bio);
      if ($bio === '') {
        throw new UnprocessableEntityApiException(
          ApiErrorCode::PROFILE_BIO_EMPTY,
          'bio не может быть пустым'
        );
      }
      $profile->bio = $bio;
      $changed = true;
    }

    if ($avatar !== null) {
      $avatar = trim($avatar);
      if ($avatar === '') {
        throw new UnprocessableEntityApiException(
          ApiErrorCode::PROFILE_AVATAR_EMPTY,
          'avatar не может быть пустым'
        );
      }
      $profile->avatar = $avatar;
      $changed = true;
    }

    if ($birth_date !== null) {
      $birth_date = trim($birth_date);
      if ($birth_date === '') {
        throw new UnprocessableEntityApiException(
          ApiErrorCode::PROFILE_BIRTH_DATE_EMPTY,
          'birth_date не может быть пустым'
        );
      }
      $profile->birth_date = $birth_date;
      $changed = true;
    }

    if (!$changed) {
      throw new BadRequestApiException(
        ApiErrorCode::MISSING_PARAMETER,
        'Нет данных для обновления'
      );
    }

    if (!$profile->save()) {
      throw new InternalServerApiException(
        ApiErrorCode::PROFILE_UPDATE_FAILED,
        'Ошибка при обновлении профиля'
      );
    }

    return [
      '$profile_id' => $profile->id,
      'user_id' => $profile->user_id,
      'diaplay_name' => $profile->display_name,
      'bio' => $profile->bio,
      'avatar' => $profile->avatar,
      'birth_date' => $profile->birth_date,
    ];
  }

  public function getProfile(): array
  {
    $user_id = (int)Yii::$app->user->id;

    $profile = Profile::findOne(['user_id' => $user_id]);

    if (!$profile) {
      throw new NotFoundApiException(
        ApiErrorCode::PROFILE_NOT_FOUND,
        'Профиль не найден'
      );
    }

    return [
      'display_name' => $profile->display_name,
      'bio' => $profile->bio,
      'avatar' => $profile->avatar,
      'birth_date' => $profile->birth_date,
    ];
  }
}
