<?php

namespace bronek911\messages\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%group}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m221119_102810_create_group_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%group}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'is_active' => $this->boolean(),
        ]);

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-group-created_by}}',
            '{{%group}}',
            'created_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-group-created_by}}',
            '{{%group}}',
            'created_by',
            '{{%user}}',
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
            '{{%fk-group-created_by}}',
            '{{%group}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-group-created_by}}',
            '{{%group}}'
        );

        $this->dropTable('{{%group}}');
    }
}
