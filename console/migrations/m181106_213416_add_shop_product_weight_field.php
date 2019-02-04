<?php

use yii\db\Migration;

/**
 * Class m181106_213416_add_shop_product_weight_field
 */
class m181106_213416_add_shop_product_weight_field extends Migration
{
    public function up()
    {
        $this->addColumn('{{%shop_products}}', 'weight', $this->integer()->notNull());
    }

    public function down()
    {
        $this->dropColumn('{{%shop_products}}', 'weight');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181106_213416_add_shop_product_weight_field cannot be reverted.\n";

        return false;
    }
    */
}
