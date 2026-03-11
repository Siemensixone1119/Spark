<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;
use app\models\Profile;
use app\models\Post;

class User extends ActiveRecord implements IdentityInterface
{

  const STATUS_ACTIVE = 10;
  const STATUS_INACTIVE = 0;
  const STATUS_BANNED = -10;

  public function behaviors()
  {
    return [
      TimestampBehavior::class
    ];
  }

  public static function tableName()
  {
    return '{{%user}}';
  }

  public function rules()
  {
    return [
      [['status'], 'default', 'value' => 10],
      [['username', 'email', 'password_hash', 'status'], 'required'],
      [['username', 'email'], 'unique'],
      [['created_at', 'updated_at', 'status'], 'integer'],
      [['username', 'password_hash', 'auth_key'], 'string'],
      [['email'], 'string', 'max' => 255],
      [['username'], 'string', 'max' => 50],
      [['auth_key'], 'string', 'max' => 32],
    ];
  }

  public static function findIdentity($id)
  {
    return static::findOne($id);
  }

  public static function findIdentityByAccessToken($token, $type = null)
  {
    return null;
  }

  public function getId()
  {
    return $this->id;
  }

  public function getAuthKey()
  {
    return $this->auth_key;
  }

  public function validateAuthKey($auth_key)
  {
    return $this->auth_key === $auth_key;
  }

  public function setPassword($password)
  {
    return $this->password_hash = Yii::$app->security->generatePasswordHash($password);
  }

  public function validatePassword($password)
  {
    return Yii::$app->security->validatePassword($password, $this->password_hash);
  }

  public function generateAuthKey()
  {
    $this->auth_key = Yii::$app->security->generateRandomString(32);
  }

  public function isActive()
  {
    return $this->status === self::STATUS_ACTIVE;
  }

  public function isInactive()
  {
    return $this->status === self::STATUS_INACTIVE;
  }

  public function isBanned()
  {
    return $this->status === self::STATUS_BANNED;
  }

  public function getProfile()
  {
    return $this->hasOne(Profile::class, ['user_id' => 'id']);
  }

  public function getPosts()
  {
    return $this->hasMany(Post::class, ['user_id' => 'id']);
  }
}
