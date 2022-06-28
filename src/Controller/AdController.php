<?php

namespace App\Controller;

use App\Entity\{Ad, Client, Site};
use App\Form\AdType;
use App\Repository\AdRepository;
use App\Util\Binder;
use DateInterval;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{JsonResponse, Request, Response};
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ad')]
class AdController extends AbstractController
{
    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(private EntityManagerInterface $em){}

    #[Route('/', name: 'ad_index', methods: ['GET'])]
    public function index(AdRepository $adRepository): Response
    {
        return $this->render('ad/index.html.twig', [
            'ads' => $adRepository->findAllOrderByRenewalDate(),
        ]);
    }

    #[Route('/new', name: 'ad_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $ad = new Ad();
        if ($clientId = $request->get('clientId')) {
            if ($client = $this->em->getRepository(Client::class)->find($clientId)) {
                $ad->setClient($client);
            }
        }
        if ($siteId = $request->get('siteId')) {
            if ($site = $this->em->getRepository(Site::class)->find($siteId)) {
                $ad->setSite($site);
            }
        }
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($ad);
            $this->em->flush();

            Binder::set($ad, $this->em);

            return $this->redirectToRoute('ad_index', ['id' => $ad->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ad/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'ad_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ad $ad): Response
    {
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            return $this->redirectToRoute('ad_index', ['id' =>  $ad->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ad/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'ad_delete', methods: ['POST'])]
    public function delete(Request $request, Ad $ad): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ad->getId(), $request->request->get('_token'))) {
            $this->em->remove($ad);
            $this->em->flush();
        }

        return $this->redirectToRoute('ad_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/update-renewal-date/{id}', name: 'updateAddRenewalDate', methods: ['GET'])]
    public function updateAddRenewalDate(Request $request, Ad $ad, EntityManagerInterface $em)
    {
        $newDate = $ad->getRenewalDate()->add(new DateInterval('P1Y'));

        $ad->setRenewalDate(new \DateTime($newDate->format('Y-m-d')) );
        $em->persist($ad);
        $em->flush();
        return new JsonResponse(true);
    }

    #[Route('/remove-site/{id}', name: 'ad_remove_site', methods: ['GET', 'POST'])]
    public function removeSite(Ad $ad): Response
    {
        $ad->setSite(null);
        $this->em->flush();

        return $this->redirectToRoute('ad_index');
    }
}
