<?php

namespace app\modules\messaging\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%message}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m221119_102812_create_message_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%message}}', [
            'id' => $this->primaryKey(),
            'subject' => $this->string(100),
            'message' => $this->text(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'parent_message_id' => $this->integer(),
        ]);

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-message-created_by}}',
            '{{%message}}',
            'created_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-message-created_by}}',
            '{{%message}}',
            'created_by',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `parent_message_id`
        $this->createIndex(
            '{{%idx-message-parent_message_id}}',
            '{{%message}}',
            'parent_message_id'
        );

        // add foreign key for table `{{%message}}`
        $this->addForeignKey(
            '{{%fk-message-parent_message_id}}',
            '{{%message}}',
            'parent_message_id',
            '{{%message}}',
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
            '{{%fk-message-created_by}}',
            '{{%message}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-message-created_by}}',
            '{{%message}}'
        );

        // drops foreign key for table `{{%message}}`
        $this->dropForeignKey(
            '{{%fk-message-parent_message_id}}',
            '{{%message}}'
        );

        // drops index for column `parent_message_id`
        $this->dropIndex(
            '{{%idx-message-parent_message_id}}',
            '{{%message}}'
        );

        $this->dropTable('{{%message}}');
    }
}
