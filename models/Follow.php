<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use app\models\User;

class Follow extends ActiveRecord
{
  public static function tableName()
  {
    return '{{%follow}}';
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
      [['follower_id', 'followed_id'], 'required'],
      [['follower_id', 'followed_id', 'created_at'], 'integer']
    ];
  }

  public function getFollower()
  {
    return $this->hasOne(User::class, ['id' => 'follower_id']);
  }

  public function getFollowed()
  {
    return $this->hasOne(User::class, ['id' => 'followed_id']);
  }
}
