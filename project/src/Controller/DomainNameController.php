<?php

namespace App\Controller;

use App\Entity\DomainName;
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
        return $this->render('domain_name/index.html.twig', [
            'domain_names' => $domainNameRepository->findAll(),
        ]);
    }

    #[Route('/search', name: 'domain_name_search', methods: ['GET'])]
    public function search(Request $request, DomainNameRepository $domainNameRepository): Response
    {
        return $this->render('domain_name/search.html.twig', [
            'domain_names' => $domainNameRepository->findBy(['url' => $request->get('searchUrl')]),
        ]);
    }

    #[Route('/ordered', name: 'domain_name_ordered', methods: ['GET'])]
    public function ordered(Request $request, DomainNameRepository $domainNameRepository): Response
    {

        switch ($request->get('orderedType')) {
            case 'url':
                return $this->render('domain_name/index.html.twig', [
                    'domain_names' => $domainNameRepository->findBy([], ['url' => 'ASC']),
                ]);
            case 'onlineDate' :
                return $this->render('domain_name/index.html.twig', [
                    'domain_names' => $domainNameRepository->findBy([], ['onlineDate' => 'ASC']),
                ]);
            case 'provider' :
                return $this->render('domain_name/index.html.twig', [
                    'domain_names' => $domainNameRepository->findBy([], ['provider' => 'ASC']),
                ]);
            case 'price' :
                return $this->render('domain_name/index.html.twig', [
                    'domain_names' => $domainNameRepository->findBy([], ['price' => 'ASC']),
                ]);
            case 'invoicedPrice' :
                return $this->render('domain_name/index.html.twig', [
                    'domain_names' => $domainNameRepository->findBy([], ['invoicedPrice' => 'ASC']),
                ]);
            case 'renewalType' :
                return $this->render('domain_name/index.html.twig', [
                    'domain_names' => $domainNameRepository->findBy([], ['renewalType' => 'ASC']),
                ]);
            case 'renewalDate' :
                return $this->render('domain_name/index.html.twig', [
                    'domain_names' => $domainNameRepository->findBy([], ['renewalDate' => 'ASC']),
                ]);
            case 'offer' :
                return $this->render('domain_name/index.html.twig', [
                    'domain_names' => $domainNameRepository->findBy([], ['offer' => 'ASC']),
                ]);
            case 'enable' :
                return $this->render('domain_name/index.html.twig', [
                    'domain_names' => $domainNameRepository->findBy([], ['enable' => 'ASC']),
                ]);
            case 'site' :
                return $this->render('domain_name/index.html.twig', [
                    'domain_names' => $domainNameRepository->findBy([], ['site' => 'ASC']),
                ]);
        }
        return $this->render('domain_name/search.html.twig', [
            'domain_names' => $domainNameRepository->findAll(),
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
            if($form->get('site')->getData()) {
                $domainName->setSite($form->get('site')->getData());
            }
            $entityManager->persist($domainName);
            $entityManager->flush();

            return $this->redirectToRoute('domain_name_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('domain_name/new.html.twig', [
            'domain_name' => $domainName,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'domain_name_show', methods: ['GET'])]
    public function show(DomainName $domainName): Response
    {
        return $this->render('domain_name/show.html.twig', [
            'domain_name' => $domainName,
        ]);
    }

    #[Route('/{id}/edit', name: 'domain_name_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DomainName $domainName, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DomainNameType::class, $domainName);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('domain_name_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('domain_name/edit.html.twig', [
            'domain_name' => $domainName,
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
}
