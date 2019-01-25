<?php

use yii\db\Migration;

/**
 * Handles the creation of table `bargain_result`.
 */
class m190123_102853_create_bargain_result_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bargain_result}}', [
            'id' => $this->primaryKey(),
            'lot_id' => $this->integer()->notNull()->unique(),
            'customer_id' => $this->integer()->notNull(),
            'timestamp' => $this->timestamp()->notNull(),
            'price' => $this->float()->notNull(),
        ]);

        $this->addForeignKey("customer_fk", "{{%bargain_result}}", "customer_id", "{{%user}}", "id", 'RESTRICT');
        $this->addForeignKey("lot_fk", "{{%bargain_result}}", "lot_id", "{{%lot}}", "id", 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%bargain_result}}');
    }
}
