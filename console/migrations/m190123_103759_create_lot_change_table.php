<?php

use yii\db\Migration;

/**
 * Handles the creation of table `lot_change`.
 */
class m190123_103759_create_lot_change_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%lot_change}}', [
            'id' => $this->primaryKey(),
            'lot_id' => $this->integer()->notNull(),
            'timestamp' => $this->timestamp()->notNull(),
            'price' => $this->float()->notNull(),
            'user_id' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey("change_user_fk", "{{%lot_change}}", "user_id", "{{%user}}", "id", 'RESTRICT');
        $this->addForeignKey("change_lot_fk", "{{%lot_change}}", "lot_id", "{{%lot}}", "id", 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%lot_change}}');
    }
}
