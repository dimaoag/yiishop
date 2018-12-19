<?php

namespace shop\readModels\blog;

use shop\entities\blog\Category;

class CategoryReadRepository
{
    public function getAll(): array
    {
        return Category::find()->orderBy('sort')->all();
    }

    public function find($id): ?Category
    {
        return Category::findOne($id);
    }

    public function findBySlug($slug): ?Category
    {
        return Category::find()->andWhere(['slug' => $slug])->one();
    }
}