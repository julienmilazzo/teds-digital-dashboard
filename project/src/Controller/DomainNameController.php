<?php

namespace App\Controller;

use App\Entity\{DomainName, Service, SiteClientToServicesBinder};
use App\Form\DomainNameType;
use App\Repository\DomainNameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;

#[Route('/domain-name')]
class DomainNameController extends AbstractController
{
    #[Route('/', name: 'domain_name_index', methods: ['GET'])]
    public function index(DomainNameRepository $domainNameRepository): Response
    {
        $domainNames = $domainNameRepository->findAll();

        return $this->render('domain_name/index.html.twig', [
            'domain_names' => $domainNames,
            'currentDomainName' => $domainNames[0]
        ]);
    }

    #[Route('/search', name: 'domain_name_search', methods: ['GET'])]
    public function search(Request $request, DomainNameRepository $domainNameRepository): Response
    {
        return $this->render('domain_name/search.html.twig', [
            'domain_names' => $domainNameRepository->findBy(['url' => $request->get('searchUrl')]),
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

            $this->setBinder($domainName, $entityManager);

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

    /**
     * @param DomainName $domainName
     * @param EntityManagerInterface $entityManager
     * @return void
     */
    private function setBinder(DomainName $domainName, EntityManagerInterface $entityManager)
    {
        $siteClientToServicesBinder = new SiteClientToServicesBinder();
        $siteClientToServicesBinder
            ->setClient($domainName->getClient())
            ->setSite($domainName->getSite())
            ->setType(Service::DOMAIN_NAME)
            ->setServiceId($domainName->getId());

        $entityManager->persist($siteClientToServicesBinder);
        $entityManager->flush();
        $domainName->setSiteClientToServicesBinderId($siteClientToServicesBinder->getId());

        $entityManager->persist($domainName);
        $entityManager->flush();
    }
}
