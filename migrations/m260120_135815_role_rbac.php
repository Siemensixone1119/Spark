<?php

use yii\db\Migration;

class m260120_135815_role_rbac extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = \Yii::$app->authManager;

        $admin = $auth->createRole('admin');
        $admin->description = 'Администратор';
        $auth->add($admin);

        $user = $auth->createRole('user');
        $user->description = 'Пользователь';
        $auth->add($user);

        $auth->addChild($admin, $user);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260120_135815_role_rbac cannot be reverted.\n";

        return false;
    }
    */
}
