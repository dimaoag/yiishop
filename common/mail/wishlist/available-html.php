<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user \shop\entities\user\User */
/* @var $product \shop\entities\shop\product\Product */

$link = Yii::$app->get('frontendUrlManager')->createAbsoluteUrl(['shop/catalog/product', 'id' => $product->id]);
?>
<div class="password-reset">
    <p>Hello <?= Html::encode($user->username) ?>,</p>

    <p>Product from your wishlist is available right now:</p>

    <p><?= Html::a(Html::encode($link), $link) ?></p>
</div>
