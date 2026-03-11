<?php

namespace app\rbac;

use yii\rbac\Rule;

class CommentOwnerRule extends Rule
{

  public $name = 'isCommentOwner';

  public function execute($userId, $item, $params)
  {
    if (!isset($params['comment'])) {
      return false;
    }

    $comment = $params['comment'];

    return (int)$userId === (int)$comment['user_id'];
  }
}
