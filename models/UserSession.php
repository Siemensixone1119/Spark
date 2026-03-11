<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class UserSession extends ActiveRecord
{
  public static function tableName()
  {
    return '{{%user_session}}';
  }

  public function behaviors()
  {
    return [
      [
        'class' => TimestampBehavior::class,
        'updatedAtAttribute' => false,
      ]
    ];
  }

  public function rules()
  {
    return [
      [['refresh_token'], 'unique'],
      [['user_id', 'expires_at', 'created_at', 'last_activity', 'revoked_at', 'rotated_from'], 'integer'],
      [['user_id', 'refresh_token', 'expires_at'], 'required'],
      [['refresh_token', 'user_agent', 'ip_address'], 'string']
    ];
  }

  public function generateRefreshToken()
  {
    return $this->refresh_token = Yii::$app->security->generateRandomString(64);
  }

  public function getUser()
  {
    return $this->hasOne(User::class, ['id' => 'user_id']);
  }

  public function getRotatedFrom()
  {
    return $this->hasOne(UserSession::class, ['id' => 'rotated_from']);
  }
}
