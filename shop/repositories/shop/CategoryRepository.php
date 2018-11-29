<?php

namespace shop\repositories\shop;

use shop\dispatchers\EventDispatcher;
use shop\entities\shop\Category;
use shop\repositories\events\EntityPersisted;
use shop\repositories\events\EntityRemoved;
use shop\repositories\NotFoundException;
use yii\helpers\ArrayHelper;

class CategoryRepository
{
//    private $dispatcher;
//
//    public function __construct(EventDispatcher $dispatcher)
//    {
//        $this->dispatcher = $dispatcher;
//    }

    public function get($id): Category
    {
        if (!$category = Category::findOne($id)) {
            throw new NotFoundException('Category is not found.');
        }
        return $category;
    }

    public function save(Category $category): void
    {
        if (!$category->save()) {
            throw new \RuntimeException('Saving error.');
        }
//        $this->dispatcher->dispatch(new EntityPersisted($category));
    }

    public function remove(Category $category): void
    {
        if (!$category->delete()) {
            throw new \RuntimeException('Removing error.');
        }
//        $this->dispatcher->dispatch(new EntityRemoved($category));
    }


    public function getParentById(Category $category)
    {
        return ArrayHelper::getColumn($category->parents, 'id');
    }

}