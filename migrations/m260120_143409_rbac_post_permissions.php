<?php

use yii\db\Migration;

class m260120_143409_rbac_post_permissions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        $admin = $auth->getRole('admin');
        $user = $auth->getRole('user');

        $postView = $auth->createPermission('post.view');
        $postView->description = 'Просмотреть все посты пользователя';
        $auth->add($postView);

        $postCreate = $auth->createPermission('post.create');
        $postCreate->description = 'Создать пост';
        $auth->add($postCreate);

        $postUpdate = $auth->createPermission('post.update');
        $postUpdate->description = 'Обновить пост';
        $auth->add($postUpdate);

        $postDelete = $auth->createPermission('post.delete');
        $postDelete->description = 'Удалить пост';
        $auth->add($postDelete);

        $auth->addChild($user, $postView);
        $auth->addChild($user, $postCreate);

        $auth->addChild($admin, $postUpdate);
        $auth->addChild($admin, $postDelete);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $auth = Yii::$app->authManager;

        $admin = $auth->getRole('admin');
        $user = $auth->getRole('user');

        $postView = $auth->getPermission('post.view');
        $postCreate = $auth->getPermission('post.create');
        $postUpdate = $auth->getPermission('post.update');
        $postDelete = $auth->getPermission('post.delete');



        if ($user && $postView) {
            $auth->removeChild($user, $postView);
        }
        if ($user && $postCreate) {
            $auth->removeChild($user, $postCreate);
        }
        if ($admin && $postUpdate) {
            $auth->removeChild($admin, $postUpdate);
        }
        if ($admin && $postDelete) {
            $auth->removeChild($admin, $postDelete);
        }
        if ($postView) {
            $auth->remove($postView);
        }
        if ($postCreate) {
            $auth->remove($postCreate);
        }
        if ($postUpdate) {
            $auth->remove($postUpdate);
        }
        if ($postDelete) {
            $auth->remove($postDelete);
        }


        return true;
    }
}
