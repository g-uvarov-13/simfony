<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\Admin\EditProductFormType;
use App\Form\Handler\ProductFormHandler;
use App\Form\Model\EditProductModel;
use App\Repository\ProductRepository;
use App\Utils\Manager\ProductManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormTypeInterface;


/**
 * @Route("/admin/product", name="admin_product_")
 */

class ProductController extends AbstractController
{
    /**
     * @Route("/list", name="list")
     * @param ProductRepository $productRepository
     * @return Response
     */
    public function list(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findBy(['isDeleted' => false], ['id'=>"ASC"],50);
        return $this->render('admin/product/list.html.twig',
            ['products' => $products
            ] );
    }


    /**
     * @Route("/edit/{id}", name="edit")
     * @Route("/add", name="add")
     * @param Request $request
     * @param ProductFormHandler $productFormHandler
     * @param Product|null $product
     * @return Response
     */
    public function edit(Request $request, ProductFormHandler $productFormHandler ,Product $product = null ): Response
    {

        $editProductModel = EditProductModel::makeFromProduct($product);
        $form = $this->createForm(form::class, $editProductModel);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $product = $productFormHandler->proccesEditForm($editProductModel, $form);

            $this->addFlash('success','Ваши изменения были сохранены!');

            return $this->redirectToRoute('admin_product_edit', ['id' => $product->getId()]);
        }
        if($form->isSubmitted() && !$form->isValid()){
            $this->addFlash('warning','Неправильно введённые данные');
        }

        $images = $product ? $product->getProductImages()->getValues() : [];
        return $this->render('admin/product/edit.html.twig', [
            'images' => $images,
            'product' => $product,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/delete/{id}", name="delete")
     * @param Product $product
     * @param ProductManager $productManager
     * @return Response
     */
    public function delete(Product $product, ProductManager $productManager): Response
    {
        $productManager->remove($product);
        $this->addFlash('warning','Продукт был успешно удалён');
         return $this->redirectToRoute('admin_product_list');
    }
}
