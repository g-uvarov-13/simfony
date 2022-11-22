<?php

namespace App\Form\Handler;

use App\Entity\Product;
use App\Form\Model\EditProductModel;
use App\Utils\File\FileSaver;
use App\Utils\Manager\ProductManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Form;

class ProductFormHandler
{

    /**
     * @var FileSaver
     */
    private $fileSaver;
    /**
     * @var ProductManager
     */
    private $productManager;

    /**
     * ProductFormHandler constructor.
     * @param ProductManager $productManager
     * @param FileSaver $fileSaver
     */
    public function __construct(ProductManager $productManager, FileSaver $fileSaver)
    {

        $this->fileSaver = $fileSaver;
        $this->productManager = $productManager;
    }

    /**
     * @param EditProductModel $editProductModel
     * @param Form $form
     *
     * @return Product|null
     */
    public function proccesEditForm(EditProductModel $editProductModel, Form $form)
    {
        $product = new Product();
        if($editProductModel->id){
            $product = $this->productManager->find($editProductModel->id);
        }
        $product->setTitle($editProductModel->title);
        $product->setPrice($editProductModel->price);
        $product->setQuantity($editProductModel->quantity);
        $product->setDescription($editProductModel->description);
        $product->setIsPublished($editProductModel->isPublished);
        $product->setIsDeleted($editProductModel->isDeleted);

        $this->productManager->save($product);

        //Получаем картинку
        $newImageFile = $form->get('newImage')->getData();

        $tempImageFileName = $newImageFile ? $this->fileSaver->saveUploadedFileIntoTemp($newImageFile) : null;

        $this->productManager->UpdateProductImagesDir($product, $tempImageFileName);



        $this->productManager->save($product);
        return $product;
    }
}