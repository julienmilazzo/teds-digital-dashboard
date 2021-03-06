<?php

namespace App\Controller;

use DateInterval;
use App\Entity\{Server, Site};
use App\Form\ServerType;
use App\Repository\ServerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{JsonResponse, Request, Response};
use Symfony\Component\Routing\Annotation\Route;

#[Route('/server')]
class ServerController extends AbstractController
{
    #[Route('/', name: 'server_index', methods: ['GET'])]
    public function index(ServerRepository $serverRepository): Response
    {
        $servers = $serverRepository->findAllOrderByRenewalDate();

        return $this->render('server/index.html.twig', [
            'servers' => $servers,
            'currentServer' => $servers[0] ?? null,
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

    #[Route('/ordered/{id}', name: 'server_ordered', methods: ['GET'])]
    public function ordered(Request $request, ServerRepository $serverRepository, Server $server): Response
    {
        $orderBy = ('ASC' === $request->get('orderBy')) ? 'DESC' : 'ASC';

        return $this->render('server/index.html.twig', [
            'servers' => $serverRepository->findBy([], [$request->get('orderedType') => $orderBy]),
            'orderBy' => $orderBy,
            'currentServer' => $server,
        ]);
    }

    #[Route('/new', name: 'server_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $server = new Server();
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
            $entityManager->persist($server);
            $entityManager->flush();

            return $this->redirectToRoute('server_show', ['id' => $server->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('server/new.html.twig', [
            'currentServer' => $server,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'server_show', methods: ['GET'])]
    public function show(Server $server, ServerRepository $serverRepository): Response
    {
        $servers = $serverRepository->findAll();

        return $this->render('server/index.html.twig', [
            'servers' => $servers,
            'currentServer' => $server,
        ]);
    }

    #[Route('/{id}/edit', name: 'server_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Server $server, EntityManagerInterface $entityManager): Response
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
            $entityManager->persist($server);
            $entityManager->flush();

            return $this->redirectToRoute('server_show', ['id' => $server->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('server/edit.html.twig', [
            'currentServer' => $server,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'server_delete', methods: ['POST'])]
    public function delete(Request $request, Server $server, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$server->getId(), $request->request->get('_token'))) {
            $entityManager->remove($server);
            $entityManager->flush();
        }

        return $this->redirectToRoute('server_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/remove-site/{id}', name: 'server_remove_site', methods: ['GET', 'POST'])]
    public function removeSite(Server $server, Site $site, EntityManagerInterface $entityManager): Response
    {
        $server->removeSite($site);
        $entityManager->flush();

        return $this->redirectToRoute('server_show', [
            'id' => $server->getId()
        ]);
    }

    #[Route('/update-renewal-date/{id}', name: 'updateAddRenewalDate', methods: ['GET'])]
    public function updateAddRenewalDate(Request $request, Server $server, EntityManagerInterface $em)
    {
        $newDate = $server->getRenewalDate()->add(new DateInterval('P1Y'));

        $server->setRenewalDate(new \DateTime($newDate->format('Y-m-d')) );
        $em->persist($server);
        $em->flush();
        return new JsonResponse(true);
    }
}
