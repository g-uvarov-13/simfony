<?php

namespace App\Form\Model;

use App\Entity\Product;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
class EditProductModel
{
    /**
     * @var int
     */
    public $id;


    /**
     * @Assert\NotBlank(message ="Пожалуйста  введите заголовок")
     * @var string
     */
    public $title;

    /**
     * @Assert\NotBlank(message ="Пожалуйста укажите цену")
     * @Assert\GreaterThanOrEqual(value ="0")
     * @var string
     */
    public $price;

    /**
     * @Assert\File(
     *     maxSize = "5024K",
     *     mimeTypes={"image/jpeg","image/png"},
     *     mimeTypesMessage="Загрузите правильный формат картинки")
     * @var UploadedFile|null
     */
    public $newImage;


    /**
     * @Assert\NotBlank(message ="Пожалуйста укажите колличество")
     * @var int
     */
    public $quantity;

    /**
     * @var string
     */
    public $description;

    /**
     * @var boolean
     */
    public $isPublished;

    /**
     * @var boolean
     */
    public $isDeleted;


    public static function makeFromProduct(?Product $product): self
    {
        $model = new self();

        if(!$product){
            return $model;
        }

        $model->id = $product->getId();
        $model->title = $product->getTitle();
        $model->price = $product->getPrice();
        $model->quantity = $product->getQuantity();
        $model->description = $product->getDescription();
        $model->isPublished = $product->isIsPublished();
        $model->isDeleted = $product->isIsDeleted();

        return $model;
    }
}