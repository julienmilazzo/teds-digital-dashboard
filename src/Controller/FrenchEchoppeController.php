<?php

namespace App\Controller;

use App\Util\Binder;
use App\Entity\FrenchEchoppe;
use App\Form\FrenchEchoppeType;
use App\Repository\FrenchEchoppeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;

#[Route('/french/echoppe')]
class FrenchEchoppeController extends AbstractController
{
    #[Route('/', name: 'french_echoppe_index', methods: ['GET'])]
    public function index(FrenchEchoppeRepository $frenchEchoppeRepository): Response
    {
        $frenchEchoppes = $frenchEchoppeRepository->findAll();

        return $this->render('french_echoppe/index.html.twig', [
            'french_echoppes' => $frenchEchoppes,
            'currentFrenchEchoppe' => $frenchEchoppes[0] ?? null
        ]);
    }

    #[Route('/search', name: 'french_echoppe_search', methods: ['GET'])]
    public function search(Request $request, FrenchEchoppeRepository $frenchEchoppeRepository): Response
    {
        return $this->render('french_echoppe/search.html.twig', [
            'frenchEchoppes' => $frenchEchoppeRepository->findBy(['url' => $request->get('searchUrl')]),
        ]);
    }

    #[Route('/ordered/{id}', name: 'french-echoppe_ordered', methods: ['GET'])]
    public function ordered(Request $request, FrenchEchoppeRepository $frenchEchoppeRepository, FrenchEchoppe $frenchEchoppe): Response
    {
        $orderBy = ('ASC' === $request->get('orderBy')) ? 'DESC' : 'ASC';

        return $this->render('french_echoppe/index.html.twig', [
            'french_echoppes' => $frenchEchoppeRepository->findBy([], [$request->get('orderedType') => $orderBy]),
            'orderBy' => $orderBy,
            'currentFrenchEchoppe' => $frenchEchoppe
        ]);
    }

    #[Route('/new', name: 'french_echoppe_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $frenchEchoppe = new FrenchEchoppe();
        $form = $this->createForm(FrenchEchoppeType::class, $frenchEchoppe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var FrenchEchoppe $frenchEchoppe */
            $frenchEchoppe = $form->getData();

            $entityManager->persist($frenchEchoppe);
            $entityManager->flush();

            Binder::set($frenchEchoppe, $entityManager);

            return $this->redirectToRoute('french_echoppe_show', ['id' => $frenchEchoppe->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('french_echoppe/new.html.twig', [
            'currentFrenchEchoppe' => $frenchEchoppe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'french_echoppe_show', methods: ['GET'])]
    public function show(FrenchEchoppe $frenchEchoppe, FrenchEchoppeRepository $frenchEchoppeRepository): Response
    {
        $frenchEchoppes = $frenchEchoppeRepository->findAll();

        return $this->render('french_echoppe/index.html.twig', [
            'french_echoppes' => $frenchEchoppes,
            'currentFrenchEchoppe' => $frenchEchoppe
        ]);
    }

    #[Route('/{id}/edit', name: 'french_echoppe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FrenchEchoppe $frenchEchoppe, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FrenchEchoppeType::class, $frenchEchoppe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var FrenchEchoppe $frenchEchoppe */
            $frenchEchoppe = $form->getData();

            $entityManager->persist($frenchEchoppe);
            $entityManager->flush();

            return $this->redirectToRoute('french_echoppe_show', ['id' => $frenchEchoppe->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('french_echoppe/edit.html.twig', [
            'currentFrenchEchoppe' => $frenchEchoppe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'french_echoppe_delete', methods: ['POST'])]
    public function delete(Request $request, FrenchEchoppe $frenchEchoppe, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$frenchEchoppe->getId(), $request->request->get('_token'))) {
            $entityManager->remove($frenchEchoppe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('french_echoppe_index', [], Response::HTTP_SEE_OTHER);
    }
}
