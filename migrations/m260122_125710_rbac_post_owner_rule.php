<?php

use yii\db\Migration;
use app\rbac\PostOwnerRule;

class m260122_125710_rbac_post_owner_rule extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        $user = $auth->getRole('user');

        $rule = new PostOwnerRule;
        $auth->add($rule);

        $postView = $auth->getPermission('post.view');
        $postUpdate = $auth->getPermission('post.update');
        $postDelete = $auth->getPermission('post.delete');


        $postUpdateOwn = $auth->createPermission('post.updateOwn');
        $postUpdateOwn->description = 'Обновление своего поста';
        $postUpdateOwn->ruleName = $rule->name;
        $auth->add($postUpdateOwn);

        $postDeleteOwn = $auth->createPermission('post.deleteOwn');
        $postDeleteOwn->description = 'Обновление своего поста';
        $postDeleteOwn->ruleName = $rule->name;
        $auth->add($postDeleteOwn);

        $postViewOwn = $auth->createPermission('post.viewOwn');
        $postViewOwn->description = 'просмотр приватного поста';
        $postViewOwn->ruleName = $rule->name;
        $auth->add($postViewOwn);


        $auth->addChild($postViewOwn, $postView);
        $auth->addChild($postUpdateOwn, $postUpdate);
        $auth->addChild($postDeleteOwn, $postDelete);

        
        $auth->addChild($user, $postViewOwn);
        $auth->addChild($user, $postUpdateOwn);
        $auth->addChild($user, $postDeleteOwn);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $auth = Yii::$app->authManager;

        $user = $auth->getRole('user');

        $postUpdate = $auth->getPermission('post.update');
        $postDelete = $auth->getPermission('post.delete');
        $postView = $auth->getPermission('post.view');

        $postUpdateOwn = $auth->getPermission('post.updateOwn');
        $postDeleteOwn = $auth->getPermission('post.deleteOwn');
        $postViewOwn = $auth->getPermission('post.viewOwn');

        if ($user && $postUpdateOwn && $auth->hasChild($user, $postUpdateOwn)) {
            $auth->removeChild($user, $postUpdateOwn);
        }
        if ($user && $postDeleteOwn && $auth->hasChild($user, $postDeleteOwn)) {
            $auth->removeChild($user, $postDeleteOwn);
        }
        if ($user && $postViewOwn && $auth->hasChild($user, $postViewOwn)) {
            $auth->removeChild($user, $postViewOwn);
        }

        if ($postUpdateOwn && $postUpdate && $auth->hasChild($postUpdateOwn, $postUpdate)) {
            $auth->removeChild($postUpdateOwn, $postUpdate);
        }
        if ($postDeleteOwn && $postDelete && $auth->hasChild($postDeleteOwn, $postDelete)) {
            $auth->removeChild($postDeleteOwn, $postDelete);
        }
        if ($postViewOwn && $postView && $auth->hasChild($postViewOwn, $postView)) {
            $auth->removeChild($postViewOwn, $postView);
        }

        if ($postUpdateOwn) {
            $auth->remove($postUpdateOwn);
        }
        if ($postDeleteOwn) {
            $auth->remove($postDeleteOwn);
        }
        if ($postViewOwn) {
            $auth->remove($postViewOwn);
        }

        $rule = $auth->getRule('isPostOwner');
        if ($rule) {
            $auth->remove($rule);
        }

        return true;
    }
}
