<?php

namespace App\Controller;

use App\Entity\Server;
use App\Form\ServerType;
use App\Repository\ServerRepository;
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
        return $this->render('server/index.html.twig', [
            'servers' => $serverRepository->findAll(),
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

            return $this->redirectToRoute('server_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('server/new.html.twig', [
            'server' => $server,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'server_show', methods: ['GET'])]
    public function show(Server $server): Response
    {
        return $this->render('server/show.html.twig', [
            'server' => $server,
        ]);
    }

    #[Route('/{id}/edit', name: 'server_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Server $server, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ServerType::class, $server);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('server_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('server/edit.html.twig', [
            'server' => $server,
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
}
