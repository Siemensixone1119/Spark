<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use app\models\User;
use app\models\Post;

class Comment extends ActiveRecord
{
  public static function tableName()
  {
    return '{{%comment}}';
  }

  public function behaviors()
  {
    return [
      ['class' => TimestampBehavior::class,
      'updatedAtAttribute' => false,]
    ];
  }

  public function rules()
  {
    return [
      [['user_id', 'post_id', 'content'], 'required'],
      [['user_id', 'post_id', 'created_at'], 'integer'],
      [['content'], 'string']
    ];
  }

  public function getUser()
  {
    return $this->hasOne(User::class, ['id' => 'user_id']);
  }

  public function getPost()
  {
    return $this->hasOne(Post::class, ['id' => 'post_id']);
  }
}
