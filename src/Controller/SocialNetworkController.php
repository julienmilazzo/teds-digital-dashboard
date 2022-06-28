<?php

namespace App\Controller;

use App\Util\Binder;
use App\Entity\{Client, SocialNetwork};
use App\Form\SocialNetworkType;
use App\Repository\SocialNetworkRepository;
use DateInterval;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{JsonResponse, Request, Response};
use Symfony\Component\Routing\Annotation\Route;

#[Route('/social/network')]
class SocialNetworkController extends AbstractController
{
    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(private EntityManagerInterface $em){}

    #[Route('/', name: 'social_network_index', methods: ['GET'])]
    public function index(SocialNetworkRepository $socialNetworkRepository): Response
    {
        return $this->render('social_network/index.html.twig', [
            'socialNetworks' => $socialNetworkRepository->findAllOrderByRenewalDate()
        ]);
    }

    #[Route('/new', name: 'social_network_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $socialNetwork = new SocialNetwork();
        if ($clientId = $request->get('clientId')) {
            if ($client = $this->em->getRepository(Client::class)->find($clientId)) {
                $socialNetwork->setClient($client);
            }
        }

        $form = $this->createForm(SocialNetworkType::class, $socialNetwork);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var SocialNetwork $socialNetwork */
            $socialNetwork = $form->getData();
            $futurSocialNetworks = [];
            foreach ($form->get('whichSocialNetworks')->getData() as $whichSocialNetwork) {
                $futurSocialNetworks[] = $whichSocialNetwork;
            }
            $socialNetwork->setWhichSocialNetwork($futurSocialNetworks);
            $this->em->persist($socialNetwork);
            $this->em->flush();

            Binder::set($socialNetwork, $this->em);

            return $this->redirectToRoute('social_network_index');
        }

        return $this->renderForm('social_network/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'social_network_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SocialNetwork $socialNetwork): Response
    {
        $form = $this->createForm(SocialNetworkType::class, $socialNetwork);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            return $this->redirectToRoute('social_network_index');
        }

        return $this->renderForm('social_network/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'social_network_delete', methods: ['POST'])]
    public function delete(Request $request, SocialNetwork $socialNetwork): Response
    {
        if ($this->isCsrfTokenValid('delete'.$socialNetwork->getId(), $request->request->get('_token'))) {
            $this->em->remove($socialNetwork);
            $this->em->flush();
        }

        return $this->redirectToRoute('social_network_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/update-renewal-date/{id}', name: 'updateAddRenewalDate', methods: ['GET'])]
    public function updateAddRenewalDate(Request $request, SocialNetwork $socialNetwork, EntityManagerInterface $em)
    {
        $newDate = $socialNetwork->getRenewalDate()->add(new DateInterval('P1Y'));

        $socialNetwork->setRenewalDate(new \DateTime($newDate->format('Y-m-d')) );
        $em->persist($socialNetwork);
        $em->flush();
        return new JsonResponse(true);
    }
}
