<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%notification}}`.
 */
class m251215_103710_create_notification_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%notification}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'type' => $this->string(50)->notNull(),
            'payload' => $this->json()->notNull(),
            'is_read' => $this->boolean()->defaultValue(false)->notNull(),
            'created_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-notification-user_id', '{{%notification}}', 'user_id');
        $this->createIndex('idx-notification-user_read', '{{%notification}}', ['user_id', 'is_read']);

        $this->addForeignKey('fk-notification-user_id', '{{%notification}}', 'user_id', '{{%user}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-notification-user_id', '{{%notification}}');

        $this->dropIndex('idx-notification-user_read', '{{%notification}}');
        $this->dropIndex('idx-notification-user_id', '{{%notification}}');

        $this->dropTable('{{%notification}}');
    }
}
