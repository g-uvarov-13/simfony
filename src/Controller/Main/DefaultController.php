<?php

namespace App\Controller\Main;

use App\Entity\Product;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="main_homepage")
     */
    public function index(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $productList  = $entityManager->getRepository(Product::class)->findAll();
        return $this->render('main/default/index.html.twig', [
        ]);
    }

}
