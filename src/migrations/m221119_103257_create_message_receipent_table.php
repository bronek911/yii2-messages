<?php

namespace bronek911\messages\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%message_receipent}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%message}}`
 */
class m221119_103257_create_message_receipent_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%message_receipent}}', [
            'id' => $this->primaryKey(),
            'receipent_id' => $this->integer(),
            'receipent_group_id' => $this->integer(),
            'message_id' => $this->integer(),
            'is_read' => $this->boolean(),
        ]);

        // creates index for column `receipent_id`
        $this->createIndex(
            '{{%idx-message_receipent-receipent_id}}',
            '{{%message_receipent}}',
            'receipent_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-message_receipent-receipent_id}}',
            '{{%message_receipent}}',
            'receipent_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `receipent_group_id`
        $this->createIndex(
            '{{%idx-message_receipent-receipent_group_id}}',
            '{{%message_receipent}}',
            'receipent_group_id'
        );

        // add foreign key for table `{{%group}}`
        $this->addForeignKey(
            '{{%fk-message_receipent-receipent_group_id}}',
            '{{%message_receipent}}',
            'receipent_group_id',
            '{{%group}}',
            'id',
            'CASCADE'
        );

        // creates index for column `message_id`
        $this->createIndex(
            '{{%idx-message_receipent-message_id}}',
            '{{%message_receipent}}',
            'message_id'
        );

        // add foreign key for table `{{%message}}`
        $this->addForeignKey(
            '{{%fk-message_receipent-message_id}}',
            '{{%message_receipent}}',
            'message_id',
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
            '{{%fk-message_receipent-receipent_id}}',
            '{{%message_receipent}}'
        );

        // drops index for column `receipent_id`
        $this->dropIndex(
            '{{%idx-message_receipent-receipent_id}}',
            '{{%message_receipent}}'
        );

        // drops foreign key for table `{{%group}}`
        $this->dropForeignKey(
            '{{%fk-message_receipent-receipent_group_id}}',
            '{{%message_receipent}}'
        );

        // drops index for column `receipent_group_id`
        $this->dropIndex(
            '{{%idx-message_receipent-receipent_group_id}}',
            '{{%message_receipent}}'
        );

        // drops foreign key for table `{{%message}}`
        $this->dropForeignKey(
            '{{%fk-message_receipent-message_id}}',
            '{{%message_receipent}}'
        );

        // drops index for column `message_id`
        $this->dropIndex(
            '{{%idx-message_receipent-message_id}}',
            '{{%message_receipent}}'
        );

        $this->dropTable('{{%message_receipent}}');
    }
}
