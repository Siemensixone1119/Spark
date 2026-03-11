<?php

namespace app\models;

use yii\db\ActiveRecord;
use app\models\User;
use yii\behaviors\TimestampBehavior;

class Post extends ActiveRecord
{

  const VISIBILITY_PUBLIC = 10;
  const VISIBILITY_PPRIVATE = -10;

  public static function tableName()
  {
    return '{{%post}}';
  }

  public function behaviors()
  {
    return [
      TimestampBehavior::class,
    ];
  }

  public function rules()
  {
    return [
      [['user_id', 'content', 'visibility'], 'required'],
      [['user_id', 'created_at', 'updated_at'], 'integer'],
      [['content'], 'string'],
      [['visibility'], 'integer']
    ];
  }

  public function getUser()
  {
    return $this->hasOne(User::class, ['id' => 'user_id']);
  }
}
