<?php

namespace App\Controller;

use App\Entity\SiteClientToServicesBinder;
use App\Form\SiteClientToServicesBinderType;
use App\Repository\SiteClientToServicesBinderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;

#[Route('/site/client/to/services/binder')]
class SiteClientToServicesBinderController extends AbstractController
{
    #[Route('/', name: 'site_client_to_services_binder_index', methods: ['GET'])]
    public function index(SiteClientToServicesBinderRepository $siteClientToServicesBinderRepository): Response
    {
        return $this->render('site_client_to_services_binder/index.html.twig', [
            'site_client_to_services_binders' => $siteClientToServicesBinderRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'site_client_to_services_binder_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $siteClientToServicesBinder = new SiteClientToServicesBinder();
        $form = $this->createForm(SiteClientToServicesBinderType::class, $siteClientToServicesBinder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($siteClientToServicesBinder);
            $entityManager->flush();

            return $this->redirectToRoute('site_client_to_services_binder_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('site_client_to_services_binder/new.html.twig', [
            'site_client_to_services_binder' => $siteClientToServicesBinder,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'site_client_to_services_binder_show', methods: ['GET'])]
    public function show(SiteClientToServicesBinder $siteClientToServicesBinder): Response
    {
        return $this->render('site_client_to_services_binder/show.html.twig', [
            'site_client_to_services_binder' => $siteClientToServicesBinder,
        ]);
    }

    #[Route('/{id}/edit', name: 'site_client_to_services_binder_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SiteClientToServicesBinder $siteClientToServicesBinder, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SiteClientToServicesBinderType::class, $siteClientToServicesBinder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('site_client_to_services_binder_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('site_client_to_services_binder/edit.html.twig', [
            'site_client_to_services_binder' => $siteClientToServicesBinder,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'site_client_to_services_binder_delete', methods: ['POST'])]
    public function delete(Request $request, SiteClientToServicesBinder $siteClientToServicesBinder, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$siteClientToServicesBinder->getId(), $request->request->get('_token'))) {
            $entityManager->remove($siteClientToServicesBinder);
            $entityManager->flush();
        }

        return $this->redirectToRoute('site_client_to_services_binder_index', [], Response::HTTP_SEE_OTHER);
    }
}
