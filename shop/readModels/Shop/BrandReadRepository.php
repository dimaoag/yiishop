<?php

namespace shop\readModels\shop;

use shop\entities\shop\Brand;

class BrandReadRepository
{
    public function find($id): ?Brand
    {
        return Brand::findOne($id);
    }
}