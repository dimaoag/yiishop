<?php

namespace shop\readModels\blog;

use shop\entities\blog\Tag;

class TagReadRepository
{
    public function find($id): ?Tag
    {
        return Tag::findOne($id);
    }
}