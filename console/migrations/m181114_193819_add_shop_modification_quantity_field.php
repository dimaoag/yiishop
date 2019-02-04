<?php

use yii\db\Migration;

/**
 * Class m181114_193819_add_shop_modification_quantity_field
 */
class m181114_193819_add_shop_modification_quantity_field extends Migration
{
    public function up()
    {
        $this->addColumn('{{%shop_modifications}}', 'quantity', $this->integer()->notNull());
    }

    public function down()
    {
        $this->dropColumn('{{%shop_modifications}}', 'quantity');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181114_193819_add_shop_modification_quantity_field cannot be reverted.\n";

        return false;
    }
    */
}
