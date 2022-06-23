<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Site;
use App\Util\Binder;
use App\Entity\ClickAndCollect;
use App\Form\ClickAndCollectType;
use App\Repository\ClickAndCollectRepository;
use DateInterval;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{JsonResponse, Request, Response};
use Symfony\Component\Routing\Annotation\Route;

#[Route('/click/and/collect')]
class ClickAndCollectController extends AbstractController
{
    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(private EntityManagerInterface $em){}

    #[Route('/', name: 'click_and_collect_index', methods: ['GET'])]
    public function index(ClickAndCollectRepository $clickAndCollectRepository): Response
    {
        return $this->render('click_and_collect/index.html.twig', [
            'clickAndCollects' => $clickAndCollectRepository->findAllOrderByRenewalDate(),
        ]);
    }

    #[Route('/search', name: 'click_and_collect_search', methods: ['GET'])]
    public function search(Request $request, ClickAndCollectRepository $clickAndCollectRepository): Response
    {
        $clickAndCollects= [];
        $ids = array_filter(explode(",", $request->get('ids')));
        foreach ($ids as $id) {
            $clickAndCollects[] = $clickAndCollectRepository->findOneBy(['id' => $id]);
        }

        return $this->render("click_and_collect/search.html.twig", [
            'click_and_collects' => $clickAndCollects,
        ]);
    }

    #[Route('/new', name: 'click_and_collect_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $clickAndCollect = new ClickAndCollect();
        if ($clientId = $request->get('clientId')) {
            if ($client = $this->em->getRepository(Client::class)->find($clientId)) {
                $clickAndCollect->setClient($client);
            }
        }
        if ($siteId = $request->get('siteId')) {
            if ($site = $this->em->getRepository(Site::class)->find($siteId)) {
                $clickAndCollect->setSite($site);
            }
        }

        $form = $this->createForm(ClickAndCollectType::class, $clickAndCollect);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ClickAndCollect $clickAndCollect */
            $clickAndCollect = $form->getData();

            $this->em->persist($clickAndCollect);
            $this->em->flush();

            Binder::set($clickAndCollect, $this->em);

            return $this->redirectToRoute('click_and_collect_index');
        }

        return $this->renderForm('click_and_collect/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'click_and_collect_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ClickAndCollect $clickAndCollect): Response
    {
        $form = $this->createForm(ClickAndCollectType::class, $clickAndCollect);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var ClickAndCollect $clickAndCollect */
            $clickAndCollect = $form->getData();

            $this->em->persist($clickAndCollect);
            $this->em->flush();

            return $this->redirectToRoute('click_and_collect_index');
        }

        return $this->renderForm('click_and_collect/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'click_and_collect_delete', methods: ['POST'])]
    public function delete(Request $request, ClickAndCollect $clickAndCollect): Response
    {
        if ($this->isCsrfTokenValid('delete'.$clickAndCollect->getId(), $request->request->get('_token'))) {
            $this->em->remove($clickAndCollect);
            $this->em->flush();
        }

        return $this->redirectToRoute('click_and_collect_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/update-renewal-date/{id}', name: 'updateAddRenewalDate', methods: ['GET'])]
    public function updateAddRenewalDate(ClickAndCollect $clickAndCollect)
    {
        $newDate = $clickAndCollect->getRenewalDate()->add(new DateInterval('P1Y'));

        $clickAndCollect->setRenewalDate(new \DateTime($newDate->format('Y-m-d')) );
        $this->em->persist($clickAndCollect);
        $this->em->flush();

        return new JsonResponse(true);
    }
}
