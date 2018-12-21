<?php

/* @var $this yii\web\View */
/* @var $user \shop\entities\user\User */
/* @var $product \shop\entities\shop\product\Product */

$link = Yii::$app->get('frontendUrlManager')->createAbsoluteUrl(['shop/catalog/product', 'id' => $product->id]);
?>
Hello <?= $user->username ?>,

Product from your wishlist is available right now:

<?= $link ?>