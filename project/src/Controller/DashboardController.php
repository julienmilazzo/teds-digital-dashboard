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

    #[Route('/liste', name: 'client-list')]
    public function listAction()
    {
        return $this->render('dashboard/client-list.html.twig');
    }
}
