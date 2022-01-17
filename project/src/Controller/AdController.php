<?php

namespace App\Controller;

use App\Entity\{Ad, Service, SiteClientToServicesBinder};
use App\Form\AdType;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ad')]
class AdController extends AbstractController
{
    #[Route('/', name: 'ad_index', methods: ['GET'])]
    public function index(AdRepository $adRepository): Response
    {
        $ads = $adRepository->findAll();

        return $this->render('ad/index.html.twig', [
            'ads' => $ads,
            'currentAd' => $ads[0] ?? null
        ]);
    }

    #[Route('/ordered/{id}', name: 'mail_ordered', methods: ['GET'])]
    public function ordered(Request $request, AdRepository $adRepository, Ad $ad): Response
    {
        $orderBy = ('ASC' === $request->get('orderBy')) ? 'DESC' : 'ASC';

        return $this->render('mail/index.html.twig', [
            'ads' => $adRepository->findBy([], [$request->get('orderedType') => $orderBy]),
            'orderBy' => $orderBy,
            'currentAd' => $ad
        ]);
    }

    #[Route('/new', name: 'ad_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ad = new Ad();
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ad);
            $entityManager->flush();

            $this->setBinder($ad, $entityManager);

            return $this->redirectToRoute('ad_index', ['id' => $ad->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ad/new.html.twig', [
            'currentAd' => $ad,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'ad_show', methods: ['GET'])]
    public function show(Ad $ad, AdRepository $adRepository): Response
    {
        $ads = $adRepository->findAll();

        return $this->render('ad/index.html.twig', [
            'ads' => $ads,
            'currentAd' => $ad
        ]);
    }

    #[Route('/{id}/edit', name: 'ad_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ad $ad, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('ad_index', ['id' =>  $ad->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ad/edit.html.twig', [
            'currentAd' => $ad,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'ad_delete', methods: ['POST'])]
    public function delete(Request $request, Ad $ad, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ad->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ad);
            $entityManager->flush();
        }

        return $this->redirectToRoute('ad_index', [], Response::HTTP_SEE_OTHER);
    }


    /**
     * @param Ad $ad
     * @param EntityManagerInterface $entityManager
     * @return void
     */
    private function setBinder(Ad $ad, EntityManagerInterface $entityManager)
    {
        $siteClientToServicesBinder = new SiteClientToServicesBinder();
        $siteClientToServicesBinder
            ->setClient($ad->getClient())
            ->setSite($ad->getSite() ?: null)
            ->setType(Service::AD)
            ->setServiceId($ad->getId());

        $entityManager->persist($siteClientToServicesBinder);
        $entityManager->flush();
        $ad->setSiteClientToServicesBinderId($siteClientToServicesBinder->getId());

        $entityManager->persist($ad);
        $entityManager->flush();
    }
}
