<?php

use yii\db\Migration;

class m260120_144908_rbac_comment_permissions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        $admin = $auth->getRole('admin');
        $user = $auth->getRole('user');

        $commentCreate = $auth->createPermission('comment.create');
        $commentCreate->description = 'Добавление комментария';
        $auth->add($commentCreate);

        $commentViewList = $auth->createPermission('comment.list');
        $commentViewList->description = 'Просмотр списка комментариев';
        $auth->add($commentViewList);

        $commentUpdate = $auth->createPermission('comment.update');
        $commentUpdate->description = 'Обновление комментария';
        $auth->add($commentUpdate);

        $commentDelete = $auth->createPermission('comment.delete');
        $commentDelete->description = 'Удаление комментария';
        $auth->add($commentDelete);


        $auth->addChild($user, $commentCreate);
        $auth->addChild($user, $commentViewList);

        $auth->addChild($admin, $commentUpdate);
        $auth->addChild($admin, $commentDelete);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $auth = Yii::$app->authManager;

        $admin = $auth->getRole('admin');
        $user = $auth->getRole('user');

        $commentCreate = $auth->getPermission('comment.create');
        $commentViewList = $auth->getPermission('comment.list');
        $commentUpdate = $auth->getPermission('comment.update');
        $commentDelete = $auth->getPermission('comment.delete');



        if ($user && $commentCreate) {
            $auth->removeChild($user, $commentCreate);
        }
        if ($user && $commentViewList) {
            $auth->removeChild($user, $commentViewList);
        }

        if ($admin && $commentUpdate) {
            $auth->removeChild($admin, $commentUpdate);
        }
        if ($admin && $commentDelete) {
            $auth->removeChild($admin, $commentDelete);
        }



        if ($commentCreate) {
            $auth->remove($commentCreate);
        }
        if ($commentViewList) {
            $auth->remove($commentViewList);
        }
        if ($commentUpdate) {
            $auth->remove($commentUpdate);
        }
        if ($commentDelete) {
            $auth->remove($commentDelete);
        }

        return true;
    }
}
