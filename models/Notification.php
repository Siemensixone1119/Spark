<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use app\models\User;

class Notification extends ActiveRecord
{
  public static function tableName()
  {
    return '{{%notification}}';
  }

  public function behaviors()
  {
    return [
      TimestampBehavior::class
    ];
  }

  public function rules()
  {
    return [
      [['user_id', 'type', 'payload', 'is_read'], 'required'],
      [['user_id', 'created_at'], 'integer'],
      [['type'], 'string', 'max' => 50],
      [['payload'], 'safe'],
      [['is_read'], 'boolean'],
      [['is_read'], 'default', 'value' => false]
    ];
  }

  public function getUser()
  {
    return $this->hasMany(User::class, ['id' => 'user_id']);
  }
}
