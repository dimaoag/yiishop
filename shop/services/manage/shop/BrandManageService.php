<?php

namespace shop\services\manage\shop;

use shop\entities\Meta;
use shop\entities\shop\Brand;
use shop\forms\manage\shop\BrandForm;
use shop\repositories\shop\BrandRepository;
//use shop\repositories\Shop\ProductRepository;

class BrandManageService
{
    private $brands;
//    private $products;

//    public function __construct(BrandRepository $brands, ProductRepository $products)
//    {
//        $this->brands = $brands;
//        $this->products = $products;
//    }
    public function __construct(BrandRepository $brands)
    {
        $this->brands = $brands;
    }

    public function create(BrandForm $form): Brand
    {
        $brand = Brand::create(
            $form->name,
            $form->slug,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            )
        );
        $this->brands->save($brand);
        return $brand;
    }

    public function edit($id, BrandForm $form): void
    {
        $brand = $this->brands->get($id);
        $brand->edit(
            $form->name,
            $form->slug,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            )
        );
        $this->brands->save($brand);
    }

    public function remove($id): void
    {
        $brand = $this->brands->get($id);
//        if ($this->products->existsByBrand($brand->id)) {
//            throw new \DomainException('Unable to remove brand with products.');
//        }
        $this->brands->remove($brand);
    }
}