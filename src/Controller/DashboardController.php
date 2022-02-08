<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    #[Route('/', name: 'home')]
    public function indexAction()
    {
        return $this->render('dashboard/index.html.twig');
    }

}
