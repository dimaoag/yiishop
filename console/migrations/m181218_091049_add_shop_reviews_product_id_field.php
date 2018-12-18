<?php

use yii\db\Migration;

/**
 * Class m181218_091049_add_shop_reviews_product_id_field
 */
class m181218_091049_add_shop_reviews_product_id_field extends Migration
{
    public function up()
    {
        $this->addColumn('{{%shop_reviews}}', 'product_id', $this->integer());

        $this->createIndex('{{%idx-shop_reviews-product_id}}', '{{%shop_reviews}}', 'product_id');

        $this->addForeignKey('{{%fk-shop_reviews-product_id}}', '{{%shop_reviews}}', 'product_id', '{{%shop_products}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropForeignKey('{{%fk-shop_reviews-product_id}}', '{{%shop_reviews}}');

        $this->dropColumn('{{%shop_reviews}}', 'product_id');
    }

}
