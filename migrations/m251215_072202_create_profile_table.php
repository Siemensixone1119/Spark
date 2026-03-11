<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%profile}}`.
 */
class m251215_072202_create_profile_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%profile}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'display_name' => $this->string(100),
            'bio' => $this->text(),
            'avatar' => $this->string(255),
            'birth_date' => $this->date(),
        ]);

        $this->createIndex('idx-profile-user_id', '{{%profile}}', 'user_id', true);
        $this->addForeignKey('fk-profile-user_id', '{{%profile}}', 'user_id', '{{%user}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-profile-user_id', '{{%profile}}');
        $this->dropIndex('idx-profile-user_id', '{{%profile}}');
        $this->dropTable('{{%profile}}');
    }
}
