<?php

namespace App\Form\Handler;

use App\Entity\Product;
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

    public function proccesEditForm(Product $product, Form $form)
    {


        $this->productManager->save($product);

        //Получаем картинку
        $newImageFile = $form->get('newImage')->getData();

        $tempImageFileName = $newImageFile ? $this->fileSaver->saveUploadedFileIntoTemp($newImageFile) : null;

        $this->productManager->UpdateProductImagesDir($product, $tempImageFileName);



        $this->productManager->save($product);
        return $product;
    }
}