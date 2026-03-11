<?php

namespace app\rbac;

use yii\rbac\Rule;

class PostOwnerRule extends Rule
{

  public $name = 'isPostOwner';

  public function execute($userId, $item, $params)
  {
    if (!isset($params['post'])) {
      return false;
    }

    $post = $params['post'];

    return (int)$userId === (int)$post['user_id'];
  }
}
