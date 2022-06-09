<?php

namespace App\Controller;

use App\Entity\{ClickAndCollect, DomainName, Server, Site};
use App\Form\SiteType;
use App\Repository\SiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;

#[Route('/site')]
class SiteController extends AbstractController
{
    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(private EntityManagerInterface $em){}

    #[Route('/', name: 'site_index', methods: ['GET'])]
    public function index(SiteRepository $siteRepository): Response
    {
        return $this->render('site/index.html.twig', [
            'sites' => $siteRepository->findAll(),
            'domainNames' => $this->em->getRepository(DomainName::class)->findAll(),
            'clickAndCollects' => $this->em->getRepository(ClickAndCollect::class)->findAll(),
        ]);
    }

    #[Route('/search', name: 'site_search', methods: ['GET'])]
    public function search(Request $request, SiteRepository $siteRepository): Response
    {
        $sites = [];
        $ids = array_filter(explode(",", $request->get('ids')));

        foreach ($ids as $id) {
            $sites[] = $siteRepository->findOneBy(['id' => $id]);
        }

        return $this->render('site/search.html.twig', [
            'sites' => $sites,
        ]);
    }

    #[Route('/new', name: 'site_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $site = new Site();
        $form = $this->createForm(SiteType::class, $site);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Site $site */
            $site = $form->getData();
            $this->em->persist($site);
            $this->em->flush();

            return $this->redirectToRoute('site_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('site/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'site_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Site $site): Response
    {
        $form = $this->createForm(SiteType::class, $site);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Site $site */
            $site = $form->getData();

            $this->em->persist($site);
            $this->em->flush();

            return $this->redirectToRoute('site_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('site/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'site_delete', methods: ['POST'])]
    public function delete(Request $request, Site $site): Response
    {
        if ($this->isCsrfTokenValid('delete'.$site->getId(), $request->request->get('_token'))) {
            $this->em->remove($site);
            $this->em->flush();
        }

        return $this->redirectToRoute('site_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/remove-server/{id}', name: 'site_remove_server', methods: ['GET', 'POST'])]
    public function removeServer(Site $site, Server $server): Response
    {
        $site->removeServer($server);
        $this->em->flush();

        return $this->redirectToRoute('site_index');
    }

    #[Route('/remove-domain-name/{id}', name: 'site_remove_domain_name', methods: ['GET', 'POST'])]
    public function removeDomainName(Site $site, DomainName $domainName): Response
    {
        $site->removeDomainName($domainName);
        $this->em->flush();

        return $this->redirectToRoute('site_index');
    }
}
