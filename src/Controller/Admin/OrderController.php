<?php

namespace App\Controller\Admin;


use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\StaticStorage\OrderStaticStorage;
use App\Form\Ad\EditOrderFormType;
use App\Form\Admin\EditCategoryFormType;
use App\Form\Handler\OrderFormHandler;
use App\Repository\OrderRepository;
use App\Utils\Manager\OrderManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/order", name="admin_order_")
 */
class OrderController extends AbstractController
{

    /**
     * @Route("/list", name="list")
     */
    public function list(OrderRepository $orderRepository): Response
    {
        $orders = $orderRepository->findBy(['isDeleted' => false ],['id' => "DESC"]);

        return $this->render('admin/order/list.html.twig', [
            'orders' => $orders,
            'orderStatusChoice'=> OrderStaticStorage::getOrderStatusChoices()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     * @Route("/add", name="add")
     * @param Request $request
     * @param OrderFormHandler $orderFormHandler
     * @param Order|null $order
     * @return Response
     */
    public function edit(Request $request, OrderFormHandler $orderFormHandler, Order $order = null): Response
    {
        if(!$order){
            $order = new Order();
        }

        $form = $this->createForm(EditOrderFormType::class,$order);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //
            $order = $orderFormHandler->proccesEditForm($order);
            $this->addFlash('success','Ваши изменения были сохранены!');
            return $this->redirectToRoute('admin_order_edit', ['id' => $order->getId()]);
        }
        if($form->isSubmitted() && !$form->isValid()){
            $this->addFlash('warning','Неправильно введённые данные');
        }

        $orderProducts = [];
      //  /** @var OrderProduct $product */
//        foreach ($order->getOrderProducts()->getValues() as $product) {
//            $orderProducts[] = [
//                'id' => $product->getId(),
//                'product' => [
//                    'id' => $product->getProduct()->getId() ,
//                    'title' => $product->getProduct()->getTitle(),
//                    'price' => $product->getProduct()->getPrice(),
//                    'quantity' => $product->getProduct()->getQuantity(),
//                    'category' => [
//                       'id' => $product->getProduct()->getCategory()->getId(),
//                       'title' => $product->getProduct()->getCategory()->getTitle(),
//                    ],
//
//                ],
//
//                'quantity' => $product->getQuantity(),
//                'pricePerOne' => $product->getPricePerOne()
//            ];
//        }

        return $this->render('admin/order/edit.html.twig',[
            'order' => $order,
            'orderProducts' =>$orderProducts,
            'form' =>$form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     * @param Order $order
     * @param OrderManager $orderManager
     * @return Response
     */
    public function delete(Order $order, OrderManager $orderManager): Response
    {
        $orderManager->remove($order);
        $this->addFlash('warning','Раздел был успешно удалён');
        return $this->redirectToRoute('admin_order_list');
    }
}

