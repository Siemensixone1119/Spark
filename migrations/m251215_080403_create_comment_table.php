<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%comment}}`.
 */
class m251215_080403_create_comment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%comment}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'post_id' => $this->integer()->notNull(),
            'content' => $this->text()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-comment-user_id', '{{%comment}}', 'user_id');
        $this->createIndex('idx-comment-post_id', '{{%comment}}', 'post_id');

        $this->addForeignKey('fk-comment-user_id', '{{%comment}}', 'user_id', '{{%user}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-comment-post_id', '{{%comment}}', 'post_id', '{{%post}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-comment-post_id', '{{%comment}}');
        $this->dropForeignKey('fk-comment-user_id', '{{%comment}}');

        $this->dropIndex('idx-comment-post_id', '{{%comment}}');
        $this->dropIndex('idx-comment-user_id', '{{%comment}}');

        $this->dropTable('{{%comment}}');
    }
}
