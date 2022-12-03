<?php

namespace bronek911\messages\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_group}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%group}}`
 */
class m221119_102811_create_user_group_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_group}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'group_id' => $this->integer(),
            'created_at' => $this->integer(),
            'is_active' => $this->boolean(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-user_group-user_id}}',
            '{{%user_group}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-user_group-user_id}}',
            '{{%user_group}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `group_id`
        $this->createIndex(
            '{{%idx-user_group-group_id}}',
            '{{%user_group}}',
            'group_id'
        );

        // add foreign key for table `{{%group}}`
        $this->addForeignKey(
            '{{%fk-user_group-group_id}}',
            '{{%user_group}}',
            'group_id',
            '{{%group}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-user_group-user_id}}',
            '{{%user_group}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-user_group-user_id}}',
            '{{%user_group}}'
        );

        // drops foreign key for table `{{%group}}`
        $this->dropForeignKey(
            '{{%fk-user_group-group_id}}',
            '{{%user_group}}'
        );

        // drops index for column `group_id`
        $this->dropIndex(
            '{{%idx-user_group-group_id}}',
            '{{%user_group}}'
        );

        $this->dropTable('{{%user_group}}');
    }
}
