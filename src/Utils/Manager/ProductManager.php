<?php

namespace App\Utils\Manager;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class ProductManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var string
     */
    private $productImagesDir;
    /**
     * @var ProductImageManager
     */
    private $productImageManager;

    /**
     * ProductManager constructor.
     * @param EntityManagerInterface $entityManager
     * @param ProductImageManager $productImageManager
     * @param string $productImagesDir
     */
    public function __construct(EntityManagerInterface $entityManager, ProductImageManager $productImageManager, string $productImagesDir)
    {

        $this->entityManager = $entityManager;
        $this->productImagesDir = $productImagesDir;
        $this->productImageManager = $productImageManager;
    }

    /**
     * @return ObjectRepository
     */
    public function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(Product::class);
    }


    /**
     * @param Product $product
     */
    public function remove(Product $product)
    {
        $product->setIsDeleted(true);
        $this->save($product);
    }


    /**
     * @param Product $product
     * @return string
     */
    public function GetProductImagesDir(Product $product): string
    {
            return sprintf('%s/%s', $this->productImagesDir,$product->getId());
    }

    public function UpdateProductImagesDir(Product $product, string $tempImageFileName = null): Product
    {
        if(!$tempImageFileName){
            return $product;
        }

       $productDir = $this->GetProductImagesDir($product);
        $productImage = $this->productImageManager->saveImageForProduct($productDir,$tempImageFileName);
        $productImage->setProduct($product);
        $product->addProductImage($productImage);
        return $product;

    }

    /**
     * @param Product $product
     */
    public function save(Product $product)
    {
        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }
}