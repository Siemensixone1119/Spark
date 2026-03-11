<?php

namespace app\models;

use yii\db\ActiveRecord;
use app\models\User;
use yii\behaviors\TimestampBehavior;

class Reaction extends ActiveRecord
{

  const TARGET_POST = 'post';
  const TARGET_COMMENT = 'comment';

  public static function tableName()
  {
    return '{{%reaction}}';
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
      [['user_id', 'target_type', 'target_id'], 'required'],
      [['user_id', 'target_id', 'created_at'], 'integer'],
      [['target_type'], 'string', 'max' => 20],
    ];
  }

  public function getUser()
  {
    return $this->hasOne(User::class, ['id' => 'user_id']);
  }
}
