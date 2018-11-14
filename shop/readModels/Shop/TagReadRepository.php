<?php

namespace shop\readModels\shop;

use shop\entities\shop\Tag;

class TagReadRepository
{
    public function find($id): ?Tag
    {
        return Tag::findOne($id);
    }
}