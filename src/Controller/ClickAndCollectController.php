<?php

namespace App\Controller;

use App\Util\Binder;
use App\Entity\ClickAndCollect;
use App\Form\ClickAndCollectType;
use App\Repository\ClickAndCollectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;

#[Route('/click/and/collect')]
class ClickAndCollectController extends AbstractController
{
    #[Route('/', name: 'click_and_collect_index', methods: ['GET'])]
    public function index(ClickAndCollectRepository $clickAndCollectRepository): Response
    {
        $clickAndCollects = $clickAndCollectRepository->findAll();

        return $this->render('click_and_collect/index.html.twig', [
            'click_and_collects' => $clickAndCollects,
            'currentClickAndCollect' => $clickAndCollects[0] ?? null
        ]);
    }

    #[Route('/search', name: 'click_and_collect_search', methods: ['GET'])]
    public function search(Request $request, ClickAndCollectRepository $clickAndCollectRepository): Response
    {
        $clickAndCollects= [];
        $ids = explode(",", $request->get('ids'));
        if ("" !== $ids[0]) {
            foreach ($ids as $id) {
                $clickAndCollects[] = $clickAndCollectRepository->findOneBy(['id' => $id]);
            }
        }

        return $this->render("click_and_collect/search.html.twig", [
            'click_and_collects' => $clickAndCollects,
        ]);
    }

    #[Route('/ordered/{id}', name: 'domain_name_ordered', methods: ['GET'])]
    public function ordered(Request $request, ClickAndCollectRepository $clickAndCollectRepository, ClickAndCollect $clickAndCollect): Response
    {
        $orderBy = ('ASC' === $request->get('orderBy')) ? 'DESC' : 'ASC';

        return $this->render('click_and_collect/index.html.twig', [
            'click_and_collects' => $clickAndCollectRepository->findBy([], [$request->get('orderedType') => $orderBy]),
            'orderBy' => $orderBy,
            'currentClickAndCollect' => $clickAndCollect
        ]);
    }

    #[Route('/new', name: 'click_and_collect_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $clickAndCollect = new ClickAndCollect();
        $form = $this->createForm(ClickAndCollectType::class, $clickAndCollect);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var ClickAndCollect $clickAndCollect */
            $clickAndCollect = $form->getData();

            $entityManager->persist($clickAndCollect);
            $entityManager->flush();

            Binder::set($clickAndCollect, $entityManager);

            return $this->redirectToRoute('click_and_collect_show', ['id' => $clickAndCollect->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('click_and_collect/new.html.twig', [
            'currentClickAndCollect' => $clickAndCollect,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'click_and_collect_show', methods: ['GET'])]
    public function show(ClickAndCollect $clickAndCollect, ClickAndCollectRepository $clickAndCollectRepository): Response
    {
        $clickAndCollects = $clickAndCollectRepository->findAll();

        return $this->render('click_and_collect/index.html.twig', [
            'click_and_collects' => $clickAndCollects,
            'currentClickAndCollect' => $clickAndCollect
        ]);
    }

    #[Route('/{id}/edit', name: 'click_and_collect_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ClickAndCollect $clickAndCollect, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ClickAndCollectType::class, $clickAndCollect);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var ClickAndCollect $clickAndCollect */
            $clickAndCollect = $form->getData();

            $entityManager->persist($clickAndCollect);
            $entityManager->flush();

            return $this->redirectToRoute('click_and_collect_show', ['id' => $clickAndCollect->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('click_and_collect/edit.html.twig', [
            'currentClickAndCollect' => $clickAndCollect,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'click_and_collect_delete', methods: ['POST'])]
    public function delete(Request $request, ClickAndCollect $clickAndCollect, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$clickAndCollect->getId(), $request->request->get('_token'))) {
            $entityManager->remove($clickAndCollect);
            $entityManager->flush();
        }

        return $this->redirectToRoute('click_and_collect_index', [], Response::HTTP_SEE_OTHER);
    }
}
