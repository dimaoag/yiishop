<?php

/* @var $this \yii\web\View */
/* @var $content string */

use frontend\widgets\shop\CategoriesWidget;

?>
<?php $this->beginContent('@frontend/views/layouts/main.php') ?>

<div class="row">
    <aside id="column-left" class="col-sm-3 hidden-xs">
        <?= CategoriesWidget::widget([
            'active' => $this->params['active_category'] ?? null, // if (!isset($this->params['active_category'])) return null;
        ]) ?>
    </aside>
    <div id="content" class="col-sm-9">
        <?= $content ?>
    </div>
</div>

<?php $this->endContent() ?>
