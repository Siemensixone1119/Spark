<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m251215_072105_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(50)->notNull(),
            'email' => $this->string(255)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'auth_key' => $this->string(32)->notNull(),
            'status' => $this->tinyInteger()->defaultValue(10)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-user-username', '{{%user}}', 'username', true);
        $this->createIndex('idx-user-email', '{{%user}}', 'email', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-user-email', '{{%user}}');
        $this->dropIndex('idx-user-username', '{{%user}}');

        $this->dropTable('{{%user}}');
    }
}
