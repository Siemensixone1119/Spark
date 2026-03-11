<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_session}}`.
 */
class m260103_144236_create_user_session_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%user_session}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'refresh_token' => $this->string()->notNull()->unique(),
            'expires_at' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'user_agent' => $this->string(),
            'ip_address' => $this->string(),
            'last_activity' => $this->integer()->notNull(),
            'revoked_at' => $this->integer(),
            'rotated_from' => $this->integer(),
        ]);

        $this->addForeignKey(
            'fk_user_session_user',
            '{{%user_session}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_user_session_rotated_from',
            '{{%user_session}}',
            'rotated_from',
            '{{%user_session}}',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->createIndex('idx_user_session_user_id', '{{%user_session}}', 'user_id');
        $this->createIndex('idx_user_session_last_activity', '{{%user_session}}', 'last_activity');
        $this->createIndex('idx_user_session_rotated_from', '{{%user_session}}', 'rotated_from');
        $this->createIndex('idx_user_session_revoked_at', '{{%user_session}}', 'revoked_at');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_user_session_rotated_from', '{{%user_session}}');
        $this->dropForeignKey('fk_user_session_user', '{{%user_session}}');

        $this->dropIndex('idx_user_session_user_id', '{{%user_session}}');
        $this->dropIndex('idx_user_session_last_activity', '{{%user_session}}');
        $this->dropIndex('idx_user_session_rotated_from', '{{%user_session}}');
        $this->dropIndex('idx_user_session_revoked_at', '{{%user_session}}');

        $this->dropTable('{{%user_session}}');
    }
}
