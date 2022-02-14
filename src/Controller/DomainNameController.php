<?php

namespace App\Controller;

use App\Util\Binder;
use App\Entity\DomainName;
use App\Form\DomainNameType;
use App\Repository\DomainNameRepository;
use DateInterval;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{JsonResponse, Request, Response};
use Symfony\Component\Routing\Annotation\Route;

#[Route('/domain-name')]
class DomainNameController extends AbstractController
{
    #[Route('/', name: 'domain_name_index', methods: ['GET'])]
    public function index(Request $request, DomainNameRepository $domainNameRepository): Response
    {
        $domainNames = $domainNameRepository->findAllOrderByRenewalDate();

        return $this->render('domain_name/index.html.twig', [
            'domain_names' => $domainNames,
            'currentDomainName' => $domainNames[0] ?? null,
        ]);
    }

    #[Route('/search', name: 'domain_name_search', methods: ['GET'])]
    public function search(Request $request, DomainNameRepository $domainNameRepository): Response
    {
        $domainNames = [];
        $ids = array_filter(explode(",", $request->get('ids')));
        foreach ($ids as $id) {
            $domainNames[] = $domainNameRepository->findOneBy(['id' => $id]);
        }

        return $this->render('domain_name/search.html.twig', [
            'domain_names' => $domainNames
        ]);
    }

    #[Route('/ordered/{id}', name: 'domain_name_ordered', methods: ['GET'])]
    public function ordered(Request $request, DomainNameRepository $domainNameRepository, DomainName $domainName): Response
    {
        $orderBy = ('ASC' === $request->get('orderBy')) ? 'DESC' : 'ASC';

        return $this->render('domain_name/index.html.twig', [
            'domain_names' => $domainNameRepository->findBy([], [$request->get('orderedType') => $orderBy]),
            'orderBy' => $orderBy,
            'currentDomainName' => $domainName
        ]);
    }

    #[Route('/new', name: 'domain_name_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $domainName = new DomainName();
        $form = $this->createForm(DomainNameType::class, $domainName);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var DomainName $domainName */
            $domainName = $form->getData();

            $entityManager->persist($domainName);
            $entityManager->flush();

            Binder::set($domainName, $entityManager);

            return $this->redirectToRoute('domain_name_show', ['id' => $domainName->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('domain_name/new.html.twig', [
            'currentDomainName' => $domainName,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'domain_name_show', methods: ['GET'])]
    public function show(DomainName $domainName, DomainNameRepository $domainNameRepository): Response
    {
        $domainNames = $domainNameRepository->findAll();
        return $this->render('domain_name/index.html.twig', [
            'domain_names' => $domainNames,
            'currentDomainName' => $domainName,
        ]);
    }

    #[Route('/{id}/edit', name: 'domain_name_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DomainName $domainName, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DomainNameType::class, $domainName);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var DomainName $domainName */
            $domainName = $form->getData();

            $entityManager->persist($domainName);
            $entityManager->flush();

            return $this->redirectToRoute('domain_name_show', ['id' => $domainName->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('domain_name/edit.html.twig', [
            'currentDomainName' => $domainName,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'domain_name_delete', methods: ['POST'])]
    public function delete(Request $request, DomainName $domainName, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$domainName->getId(), $request->request->get('_token'))) {
            $entityManager->remove($domainName);
            $entityManager->flush();
        }

        return $this->redirectToRoute('domain_name_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/update-renewal-date/{id}', name: 'updateAddRenewalDate', methods: ['GET'])]
    public function updateAddRenewalDate(Request $request, DomainName $domainName, EntityManagerInterface $em)
    {
        $newDate = $domainName->getRenewalDate()->add(new DateInterval('P1Y'));

        $domainName->setRenewalDate(new \DateTime($newDate->format('Y-m-d')) );
        $em->persist($domainName);
        $em->flush();
        return new JsonResponse(true);
    }
}
