<?php

namespace App\Controller;

use App\Util\Binder;
use App\Entity\SocialNetwork;
use App\Form\SocialNetworkType;
use App\Repository\SocialNetworkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;

#[Route('/social/network')]
class SocialNetworkController extends AbstractController
{
    #[Route('/', name: 'social_network_index', methods: ['GET'])]
    public function index(SocialNetworkRepository $socialNetworkRepository): Response
    {
        $socialNetworks = $socialNetworkRepository->findAll();

        return $this->render('social_network/index.html.twig', [
            'social_networks' => $socialNetworks,
            'currentSocialNetwork' => $socialNetworks[0] ?? null
        ]);
    }

    #[Route('/search', name: 'social_network_search', methods: ['GET'])]
    public function search(Request $request, SocialNetworkRepository $socialNetworkRepository): Response
    {
        $socialNetworks= [];
        $ids = array_filter(explode(",", $request->get('ids')));

        if ("" !== $ids[0]) {
            foreach ($ids as $id) {
                $socialNetworks[] = $socialNetworkRepository->findOneBy(['id' => $id]);
            }
        }

        return $this->render("social_network/search.html.twig", [
            'social_networks' => $socialNetworks,
        ]);
    }

    #[Route('/ordered/{id}', name: 'social_network_ordered', methods: ['GET'])]
    public function ordered(Request $request, SocialNetworkRepository $socialNetworkRepository, SocialNetwork $socialNetwork): Response
    {
        $orderBy = ('ASC' === $request->get('orderBy')) ? 'DESC' : 'ASC';

        return $this->render('social_network/index.html.twig', [
            'social_networks' => $socialNetworkRepository->findBy([], [$request->get('orderedType') => $orderBy]),
            'orderBy' => $orderBy,
            'currentSocialNetwork' => $socialNetwork
        ]);
    }

    #[Route('/new', name: 'social_network_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $socialNetwork = new SocialNetwork();
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
            $entityManager->persist($socialNetwork);
            $entityManager->flush();

            Binder::set($socialNetwork, $entityManager);

            return $this->redirectToRoute('social_network_show', ['id' => $socialNetwork->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('social_network/new.html.twig', [
            'currentSocialNetwork' => $socialNetwork,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'social_network_show', methods: ['GET'])]
    public function show(SocialNetwork $socialNetwork, SocialNetworkRepository $socialNetworkRepository): Response
    {
        $socialNetworks = $socialNetworkRepository->findAll();

        return $this->render('social_network/index.html.twig', [
            'currentSocialNetwork' => $socialNetwork,
            'social_networks' => $socialNetworks,
        ]);
    }

    #[Route('/{id}/edit', name: 'social_network_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SocialNetwork $socialNetwork, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SocialNetworkType::class, $socialNetwork);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('social_network_show', ['id' => $socialNetwork->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('social_network/edit.html.twig', [
            'currentSocialNetwork' => $socialNetwork,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'social_network_delete', methods: ['POST'])]
    public function delete(Request $request, SocialNetwork $socialNetwork, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$socialNetwork->getId(), $request->request->get('_token'))) {
            $entityManager->remove($socialNetwork);
            $entityManager->flush();
        }

        return $this->redirectToRoute('social_network_index', [], Response::HTTP_SEE_OTHER);
    }
}
