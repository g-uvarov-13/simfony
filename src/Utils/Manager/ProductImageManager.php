<?php

namespace App\Utils\Manager;

use App\Entity\Product;
use App\Entity\ProductImage;
use App\Utils\File\ImageResizer;
use App\Utils\FileSystem\FileSystemWorker;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class ProductImageManager extends AbstractBaseManager
{
    /**
     * @var FileSystemWorker
     */
    private $fileSystemWorker;
    /**
     * @var string
     */
    private $uploadTempDir;
    /**
     * @var ImageResizer
     */
    private $imageResizer;

    /**
     * ProductImageManager constructor.
     * @param EntityManagerInterface $entityManager
     * @param FileSystemWorker $fileSystemWorker
     * @param ImageResizer $imageResizer
     * @param string $uploadTempDir
     */
    public function __construct(EntityManagerInterface $entityManager, FileSystemWorker $fileSystemWorker,ImageResizer $imageResizer, string $uploadTempDir){

        parent::__construct($entityManager);
        $this->fileSystemWorker = $fileSystemWorker;
        $this->uploadTempDir = $uploadTempDir;
        $this->imageResizer = $imageResizer;
    }

    /**
     * @return ObjectRepository
     */
    public function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(ProductImage::class);
    }

    /**
     * @param string $productDir
     * @param string|null $tempImageFileName
     * @return null
     */
    public function saveImageForProduct(string $productDir, string $tempImageFileName = null)
    {
        if(!$tempImageFileName){
            return null;
        }
        $this->fileSystemWorker->CreateFolderIfNotExists($productDir);

        $filenameId = uniqid();

        $imageSmallParams=[
            'width' => 60,
            'height' =>null,
            'newFolder' => $productDir,
            'newFileName' => sprintf('%s_%s.jpg',$filenameId, 'Small')
        ];
        $imageSmall = $this->imageResizer->ResizeImageAndSave($this->uploadTempDir, $tempImageFileName, $imageSmallParams);


        $imageMiddlelParams=[
            'width' => 430 ,
            'height' =>null,
            'newFolder' => $productDir,
            'newFileName' => sprintf('%s_%s.jpg',$filenameId, 'Middle')
        ];
        $imageMiddle =  $this->imageResizer->ResizeImageAndSave($this->uploadTempDir, $tempImageFileName, $imageMiddlelParams);

        $imageBigParams=[
            'width' => 800 ,
            'height' =>null,
            'newFolder' => $productDir,
            'newFileName' => sprintf('%s_%s.jpg',$filenameId, 'Big')
        ];
        $imageBig =  $this->imageResizer->ResizeImageAndSave($this->uploadTempDir, $tempImageFileName, $imageBigParams);

        $productImage = new ProductImage();

        $productImage->setFilenameSmall($imageSmall);
        $productImage->setFilenameMiddle($imageMiddle);
        $productImage->setFilenameBig($imageBig);

        return $productImage;

    }

    public function removeImageFromProduct( ProductImage $productImage, string $productDir)
    {
        $smallFilePath = $productDir . '/' . $productImage->getFilenameSmall();
        $this->fileSystemWorker->remove($smallFilePath);

        $middleFilePath = $productDir . '/' . $productImage->getFilenameMiddle();
        $this->fileSystemWorker->remove($middleFilePath);

        $bigFilePath = $productDir . '/' . $productImage->getFilenameBig();
        $this->fileSystemWorker->remove($bigFilePath);

        $product = $productImage->getProduct();
        $product->removeProductImage($productImage);

        $this->entityManager->flush();
    }
}