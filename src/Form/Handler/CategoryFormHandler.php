<?php


namespace App\Form\Handler;


use App\Entity\Category;
use App\Form\Model\EditCategoryModel;
use App\Utils\Manager\CategoryManager;

class CategoryFormHandler
{

    /**
     * @var CategoryManager
     */
    private $categoryManager;

    public function __construct(CategoryManager $categoryManager)
    {
        $this->categoryManager = $categoryManager;
    }

    public function proccesEditForm(EditCategoryModel $editCategoryModel)
    {
        $category = new Category();
        if ($editCategoryModel->id) {
            $category = $this->categoryManager->find($editCategoryModel->id);
        }
        $category->setTitle($editCategoryModel->title);

        $this->categoryManager->save($category);

        return $category;
    }
}