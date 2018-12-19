<?php

namespace frontend\widgets\blog;

use shop\entities\blog\Category;
use shop\readModels\blog\CategoryReadRepository;
use yii\base\Widget;
use yii\helpers\Html;

class CategoriesWidget extends Widget
{
    /** @var Category|null */
    public $active;

    private $categories;

    public function __construct(CategoryReadRepository $categories, $config = [])
    {
        parent::__construct($config);
        $this->categories = $categories;
    }

    public function run(): string
    {
        return Html::tag('div', implode(PHP_EOL, array_map(function (Category $category) {
            $active = $this->active && ($this->active->id == $category->id);
            return Html::a(
                Html::encode($category->name),
                ['/blog/post/category', 'slug' => $category->slug],
                ['class' => $active ? 'list-group-item active' : 'list-group-item']
            );
        }, $this->categories->getAll())), [
            'class' => 'list-group',
        ]);
    }
}