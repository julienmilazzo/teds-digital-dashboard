<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\Client2Type;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    #[Route('/', name: 'home')]
    public function indexAction()
    {
        return $this->render('dashboard/index.html.twig');
    }

}
