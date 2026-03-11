<?php

namespace app\models;

use yii\db\ActiveRecord;
use app\models\User;

class Profile extends ActiveRecord
{

  public static function tableName()
  {
    return '{{%profile}}';
  }

  public function rules()
  {
    return [
      [['user_id'], 'required'],
      [['user_id'], 'integer'],
      [['display_name', 'bio', 'avatar'], 'string'],
      [['display_name'], 'string', 'max' => 100],
      [['avatar'], 'string', 'max' => 255],
      [['birth_date'], 'date']
    ];
  }

  public function getUser()
  {
    return $this->hasOne(User::class, ['id' => 'user_id']);
  }
}
