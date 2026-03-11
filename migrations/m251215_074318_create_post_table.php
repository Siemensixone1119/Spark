<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%post}}`.
 */
class m251215_074318_create_post_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%post}}', [
            'id' => $this->primaryKey(),
            'user_id' =>$this->integer()->notNull(),
            'content' => $this->text()->notNull(),
            'visibility' => $this->tinyInteger()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-post-user_id', '{{%post}}', 'user_id');
        $this->addForeignKey('fk-post-user_id', '{{%post}}', 'user_id', '{{%user}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropForeignKey('fk-post-user_id', '{{%post}}');
        $this->dropIndex('idx-post-user_id', '{{%post}}');
        $this->dropTable('{{%post}}');
    }
}
