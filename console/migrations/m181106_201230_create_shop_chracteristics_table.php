<?php

use yii\db\Migration;

/**
 * Handles the creation of table `shop_chracteristics`.
 */
class m181106_201230_create_shop_chracteristics_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('shop_chracteristics', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('shop_chracteristics');
    }
}
