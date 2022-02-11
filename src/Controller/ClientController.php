<?php

namespace App\Controller;

use App\Entity\{Client,Site};
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Util\GetterServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;

#[Route('/client')]
class ClientController extends AbstractController
{
    #[Route('/', name: 'client_index', methods: ['GET'])]
    public function index(Request $request, ClientRepository $clientRepository, EntityManagerInterface $entityManager): Response
    {
        $clients = $clientRepository->findAll();

        return $this->render('client/index.html.twig', [
            'clients' => $clients,
            'currentClient' => $clients[0],
        ]);
    }

    #[Route('/search', name: 'client_search', methods: ['GET'])]
    public function search(Request $request, ClientRepository $clientRepository): Response
    {
        $clients = [];
        $ids = explode(",", $request->get('ids'));
        if ("" !== $ids[0]) {
            foreach ($ids as $id) {
                $clients[] = $clientRepository->findOneBy(['id' => $id]);
            }
        }

        return $this->render("client/search.html.twig", [
            "clients" => $clients,
        ]);
    }

    #[Route('/ordered', name: 'client_ordered', methods: ['GET'])]
    public function ordered(Request $request, ClientRepository $clientRepository): Response
    {
        $orderBy = ('ASC' === $request->get('orderBy')) ? 'DESC' : 'ASC';
        $clients =  $clientRepository->findBy([], [$request->get('orderedType') => $orderBy]);

        return $this->render('client/index.html.twig', [
            'clients' => $clients,
            'currentClient' => $clients[0],
            'orderBy' => $orderBy
        ]);
    }

    #[Route('/new', name: 'client_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($client);
            $entityManager->flush();

            return $this->redirectToRoute('client_show', ['id' => $client->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('client/new.html.twig', [
            'currentClient' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'client_show', methods: ['GET'])]
    public function show(Client $currentClient, ClientRepository $clientRepository, EntityManagerInterface $entityManager): Response
    {
        $clients = $clientRepository->findAll();
        $services = GetterServices::getServices($currentClient->getSiteClientToServicesBinders(), $entityManager);

        return $this->render('client/show.html.twig', [
            'clients' => $clients,
            'currentClient' => $currentClient,
            'domainNames' => $services['domainNames'] ?? null,
            'clickAndCollects' => $services['clickAndCollects'] ?? null,
            'mails' => $services['mails'] ?? null,
            'frenchEchoppes' => $services['frenchEchoppes'] ?? null,
            'ads' => $services['ads'] ?? null,
            'socialNetworks' => $services['socialNetworks'] ?? null,
        ]);
    }
    #[Route('/{id}/edit', name: 'client_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Client $client, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('client_show', ['id' => $client->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('client/edit.html.twig', [
            'currentClient' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'client_delete', methods: ['POST'])]
    public function delete(Request $request, Client $client, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$client->getId(), $request->request->get('_token'))) {
            $entityManager->remove($client);
            $entityManager->flush();
        }

        return $this->redirectToRoute('client_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/remove-site/{id}', name: 'client_remove_site', methods: ['GET', 'POST'])]
    public function removeSite(Client $client,Request $request, EntityManagerInterface $entityManager): Response
    {
        $client->removeSite($entityManager->getRepository(Site::class)->findOneBy(["id" => $request->get('idToDelete')]));

        GetterServices::removeAllServices($entityManager->getRepository(Site::class)->findOneBy(["id" => $request->get('idToDelete')])->getSiteClientToServicesBinders(), $entityManager);

        $entityManager->remove($entityManager->getRepository(Site::class)->findOneBy(["id" => $request->get('idToDelete')]));
        $entityManager->flush();

        return $this->redirectToRoute("client_show", ["id" => $client->getId()]);
    }
}
