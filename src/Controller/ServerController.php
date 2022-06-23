<?php

namespace App\Controller;

use DateInterval;
use App\Entity\{ClickAndCollect, Client, Server, Site};
use App\Form\ServerType;
use App\Repository\ServerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{JsonResponse, Request, Response};
use Symfony\Component\Routing\Annotation\Route;

#[Route('/server')]
class ServerController extends AbstractController
{
    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(private EntityManagerInterface $em){}

    #[Route('/', name: 'server_index', methods: ['GET'])]
    public function index(ServerRepository $serverRepository): Response
    {
        return $this->render('server/index.html.twig', [
            'servers' => $serverRepository->findAllOrderByRenewalDate(),
            'clickAndCollects' => $this->em->getRepository(ClickAndCollect::class)->findAll(),
        ]);
    }

    #[Route('/search', name: 'server_search', methods: ['GET'])]
    public function search(Request $request, ServerRepository $serverRepository): Response
    {
        $servers = [];
        $ids = array_filter(explode(",", $request->get('ids')));

        foreach ($ids as $id) {
            $servers[] = $serverRepository->findOneBy(['id' => $id]);
        }

        return $this->render('server/search.html.twig', [
            'servers' => $servers,
        ]);
    }

    #[Route('/new', name: 'server_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $server = new Server();
        if ($clientId = $request->get('clientId')) {
            if ($client = $this->em->getRepository(Client::class)->find($clientId)) {
                $server->setClient($client);
            }
        }
        if ($siteId = $request->get('siteId')) {
            if ($site = $this->em->getRepository(Site::class)->find($siteId)) {
                $server->addSite($site);
            }
        }

        $form = $this->createForm(ServerType::class, $server);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Server $server */
            $server = $form->getData();
            foreach ($form->get('site')->getData() as $site) {
                $server->addSite($site);
            }
            foreach ($form->get('domainName')->getData() as $domainName) {
                $server->addDomainName($domainName);
            }
            foreach ($form->get('clickAndCollect')->getData() as $clickAndCollect) {
                $server->addClickAndCollect($clickAndCollect);
            }
            $this->em->persist($server);
            $this->em->flush();

            return $this->redirectToRoute('server_index');
        }

        return $this->renderForm('server/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'server_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Server $server): Response
    {
        $form = $this->createForm(ServerType::class, $server);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Server $server */
            $server = $form->getData();
            foreach ($form->get('site')->getData() as $site) {
                $server->addSite($site);
            }
            foreach ($form->get('domainName')->getData() as $domainName) {
                $server->addDomainName($domainName);
            }
            foreach ($form->get('clickAndCollect')->getData() as $clickAndCollect) {
                $server->addClickAndCollect($clickAndCollect);
            }
            $this->em->persist($server);
            $this->em->flush();

            return $this->redirectToRoute('server_index');
        }

        return $this->renderForm('server/edit.html.twig', [
            'currentServer' => $server,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'server_delete', methods: ['POST'])]
    public function delete(Request $request, Server $server): Response
    {
        if ($this->isCsrfTokenValid('delete'.$server->getId(), $request->request->get('_token'))) {
            $this->em->remove($server);
            $this->em->flush();
        }

        return $this->redirectToRoute('server_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/remove-site/{id}', name: 'server_remove_site', methods: ['GET', 'POST'])]
    public function removeSite(Server $server, Site $site): Response
    {
        $server->removeSite($site);
        $this->em->flush();

        return $this->redirectToRoute('server_index');
    }

    #[Route('/update-renewal-date/{id}', name: 'updateAddRenewalDate', methods: ['GET'])]
    public function updateAddRenewalDate(Request $request, Server $server)
    {
        $newDate = $server->getRenewalDate()->add(new DateInterval('P1Y'));

        $server->setRenewalDate(new \DateTime($newDate->format('Y-m-d')) );
        $this->em->persist($server);
        $this->em->flush();
        return new JsonResponse(true);
    }
}
