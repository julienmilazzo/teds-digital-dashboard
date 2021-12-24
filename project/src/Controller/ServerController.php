<?php

namespace App\Controller;

use App\Entity\Server;
use App\Entity\Site;
use App\Form\ServerType;
use App\Repository\{ServerRepository, SiteRepository};
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;

#[Route('/server')]
class ServerController extends AbstractController
{
    #[Route('/', name: 'server_index', methods: ['GET'])]
    public function index(ServerRepository $serverRepository): Response
    {
        $servers = $serverRepository->findAll();
        return $this->render('server/index.html.twig', [
            'servers' => $servers,
            'currentServer' => $servers[0],
        ]);
    }

    #[Route('/search', name: 'server_search', methods: ['GET'])]
    public function search(Request $request, ServerRepository $serverRepository): Response
    {
        return $this->render('server/search.html.twig', [
            'servers' => $serverRepository->findBy(['name' => $request->get('searchName')]),
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
}
