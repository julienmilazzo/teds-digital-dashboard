<?php

namespace App\Controller;

use App\Entity\{ClickAndCollect, DomainName, Service, Server, Site, SiteClientToServicesBinder};
use App\Form\SiteType;
use Doctrine\Common\Collections\Collection;
use App\Repository\SiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Util\GetterServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;

#[Route('/site')]
class SiteController extends AbstractController
{
    #[Route('/', name: 'site_index', methods: ['GET'])]
    public function index(SiteRepository $siteRepository, EntityManagerInterface $entityManager): Response
    {
        $sites = $siteRepository->findAll();
        $currentSite = $sites[0];
        $services = GetterServices::getServices($currentSite->getSiteClientToServicesBinders(), $entityManager);

        return $this->render('site/index.html.twig', [
            'sites' => $sites,
            'currentSite' => $currentSite,
            'domainNames' => $services[0],
            'clickAndCollects' => $services[1],
        ]);
    }

    #[Route('/search', name: 'site_search', methods: ['GET'])]
    public function search(Request $request, SiteRepository $siteRepository): Response
    {
        return $this->render('site/search.html.twig', [
            'sites' => $siteRepository->findBy(['name' => $request->get('searchName')]),
        ]);
    }

    #[Route('/ordered/{id}', name: 'site_ordered', methods: ['GET'])]
    public function ordered(Request $request, SiteRepository $siteRepository, Site $site): Response
    {
        $orderBy = ('ASC' === $request->get('orderBy')) ? 'DESC' : 'ASC';

        return $this->render('site/index.html.twig', [
            'sites' => $siteRepository->findBy([], [$request->get('orderedType') => $orderBy]),
            'orderBy' => $orderBy,
            'currentSite' => $site,
        ]);
    }

    #[Route('/new', name: 'site_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $site = new Site();
        $form = $this->createForm(SiteType::class, $site);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Site $site */
            $site = $form->getData();
            $entityManager->persist($site);
            $entityManager->flush();

            return $this->redirectToRoute('site_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('site/new.html.twig', [
            'currentSite' => $site,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'site_show', methods: ['GET'])]
    public function show(Site $site, SiteRepository $siteRepository, EntityManagerInterface $entityManager): Response
    {
        $sites = $siteRepository->findAll();
        $services = GetterServices::getServices($site->getSiteClientToServicesBinders(), $entityManager);

        return $this->render('site/index.html.twig', [
            'sites' => $sites,
            'currentSite' => $site,
            'domainNames' => $services[0],
            'clickAndCollects' => $services[1]
        ]);
    }

    #[Route('/{id}/edit', name: 'site_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Site $site, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SiteType::class, $site);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Site $site */
            $site = $form->getData();

            $entityManager->persist($site);
            $entityManager->flush();

            return $this->redirectToRoute('site_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('site/edit.html.twig', [
            'currentSite' => $site,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'site_delete', methods: ['POST'])]
    public function delete(Request $request, Site $site, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$site->getId(), $request->request->get('_token'))) {
            $entityManager->remove($site);
            $entityManager->flush();
        }

        return $this->redirectToRoute('site_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/remove-server/{id}', name: 'site_remove_server', methods: ['GET', 'POST'])]
    public function removeServer(Site $site, Server $server, EntityManagerInterface $entityManager): Response
    {
        $site->removeServer($server);
        $entityManager->flush();

        return $this->render('site/show.html.twig', [
            'currentSite' => $site,
        ]);
    }

    #[Route('/remove-domain-name/{id}', name: 'site_remove_domain_name', methods: ['GET', 'POST'])]
    public function removeDomainName(Site $site, DomainName $domainName, EntityManagerInterface $entityManager): Response
    {
        $site->removeDomainName($domainName);
        $entityManager->flush();

        return $this->render('site/show.html.twig', [
            'currentSite' => $site,
        ]);
    }
}
