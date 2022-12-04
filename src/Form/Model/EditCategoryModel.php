<?php
namespace App\Form\Model;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
class EditCategoryModel
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
     * @param Category|null $category
     * @return static
     */
    public static function makeFromCategory(?Category $category): self
    {
        $model = new self();

        if(!$category){
            return $model;
        }

        $model->id = $category->getId();
        $model->title = $category->getTitle();


        return $model;
    }
}