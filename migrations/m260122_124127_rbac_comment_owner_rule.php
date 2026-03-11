<?php

use yii\db\Migration;
use app\rbac\CommentOwnerRule;

class m260122_124127_rbac_comment_owner_rule extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        $user = $auth->getRole('user');

        $rule = new CommentOwnerRule;
        $auth->add($rule);

        $commentUpdate = $auth->getPermission('comment.update');
        $commentDelete = $auth->getPermission('comment.delete');


        $commentUpdateOwn = $auth->createPermission('comment.updateOwn');
        $commentUpdateOwn->description = 'Обновление своего комментария';
        $commentUpdateOwn->ruleName = $rule->name;
        $auth->add($commentUpdateOwn);

        $commentDeleteOwn = $auth->createPermission('comment.deleteOwn');
        $commentDeleteOwn->description = 'Обновление своего комментария';
        $commentDeleteOwn->ruleName = $rule->name;
        $auth->add($commentDeleteOwn);


        $auth->addChild($commentUpdateOwn, $commentUpdate);
        $auth->addChild($commentDeleteOwn, $commentDelete);

        $auth->addChild($user, $commentUpdateOwn);
        $auth->addChild($user, $commentDeleteOwn);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $auth = Yii::$app->authManager;

        $user = $auth->getRole('user');

        $commentUpdate = $auth->getPermission('comment.update');
        $commentDelete = $auth->getPermission('comment.delete');

        $commentUpdateOwn = $auth->getPermission('comment.updateOwn');
        $commentDeleteOwn = $auth->getPermission('comment.deleteOwn');

        if ($user && $commentUpdateOwn && $auth->hasChild($user, $commentUpdateOwn)) {
            $auth->removeChild($user, $commentUpdateOwn);
        }
        if ($user && $commentDeleteOwn && $auth->hasChild($user, $commentDeleteOwn)) {
            $auth->removeChild($user, $commentDeleteOwn);
        }

        if ($commentUpdateOwn && $commentUpdate && $auth->hasChild($commentUpdateOwn, $commentUpdate)) {
            $auth->removeChild($commentUpdateOwn, $commentUpdate);
        }
        if ($commentDeleteOwn && $commentDelete && $auth->hasChild($commentDeleteOwn, $commentDelete)) {
            $auth->removeChild($commentDeleteOwn, $commentDelete);
        }

        if ($commentUpdateOwn) {
            $auth->remove($commentUpdateOwn);
        }
        if ($commentDeleteOwn) {
            $auth->remove($commentDeleteOwn);
        }

        $rule = $auth->getRule('isCommentOwner');
        if ($rule) {
            $auth->remove($rule);
        }

        return true;
    }
}
