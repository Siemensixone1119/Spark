<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%follow}}`.
 */
class m251215_084841_create_follow_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%follow}}', [
            'id' => $this->primaryKey(),
            'follower_id' => $this->integer()->notNull(),
            'followed_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull()
        ]);

        $this->addCheck('chk-follow-no-self', '{{%follow}}', 'follower_id <> followed_id');

        $this->createIndex('idx-follow-follower_id', '{{%follow}}', 'follower_id');
        $this->createIndex('idx-follow-followed_id', '{{%follow}}', 'followed_id');

        $this->addForeignKey('fk-follow-follower_id', '{{%follow}}', 'follower_id', '{{%user}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-follow-followed_id', '{{%follow}}', 'followed_id', '{{%user}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-follow-followed_id', '{{%follow}}');
        $this->dropForeignKey('fk-follow-follower_id', '{{%follow}}');

        $this->dropIndex('idx-follow-followed_id', '{{%follow}}');
        $this->dropIndex('idx-follow-follower_id', '{{%follow}}');

        $this->dropCheck('chk-follow-no-self', '{{%follow}}');

        $this->dropTable('{{%follow}}');
    }
}
