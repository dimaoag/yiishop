<?php

use yii\db\Migration;

/**
 * Class m181106_213438_add_shop_product_quantity_field
 */
class m181106_213438_add_shop_product_quantity_field extends Migration
{
    public function up()
    {
        $this->addColumn('{{%shop_products}}', 'quantity', $this->integer()->notNull());
    }

    public function down()
    {
        $this->dropColumn('{{%shop_products}}', 'quantity');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181106_213438_add_shop_product_quantity_field cannot be reverted.\n";

        return false;
    }
    */
}
