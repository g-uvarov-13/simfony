<?php

namespace App\Controller\Admin;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use \Symfony\Component\HttpFoundation\Response;

/**
 * Class DashboardController
 *
 * @Route("/admin")
 */
class DashboardController extends AbstractController
{
    /**
     * Class DashboardController
     *
     * @Route("/dashboard", name="admin_dashboard_show")
     */
    public function  dashboard(): Response
    {
        return $this->render('admin/pages/dashboard.html.twig');
    }

}