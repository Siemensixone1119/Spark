<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%reaction}}`.
 */
class m251215_082300_create_reaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%reaction}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'target_type' => $this->string(20)->notNull(),
            'target_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-reaction-user_id', '{{%reaction}}', 'user_id');
        $this->createIndex('idx-reaction-target', '{{%reaction}}', ['target_type', 'target_id'], true);

        $this->addForeignKey('fk-reaction-user_id', '{{%reaction}}', 'user_id', '{{%user}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-reaction-user_id', '{{%reaction}}');

        $this->dropIndex('idx-reaction-target', '{{%reaction}}');
        $this->dropIndex('idx-reaction-user_id', '{{%reaction}}');

        $this->dropTable('{{%reaction}}');
    }
}
