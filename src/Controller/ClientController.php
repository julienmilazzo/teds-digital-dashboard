<?php

namespace App\Controller;

use App\Entity\{Ad, ClickAndCollect, Client, DomainName, FrenchEchoppe, Mail, Site, SocialNetwork};
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
    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(private EntityManagerInterface $em){}

    #[Route('/', name: 'client_index', methods: ['GET'])]
    public function index(Request $request, ClientRepository $clientRepository): Response
    {
        $clients = $clientRepository->findAll();

        return $this->render('client/index.html.twig', [
            'clients' => $clients,
            'currentClient' => $clients[0] ?? null,
            'domainNames' => $this->em->getRepository(DomainName::class)->findAll(),
            'clickAndCollects' => $this->em->getRepository(ClickAndCollect::class)->findAll(),
            'frenchEchoppes' => $this->em->getRepository(FrenchEchoppe::class)->findAll(),
            'mails' => $this->em->getRepository(Mail::class)->findAll(),
            'ads' => $this->em->getRepository(Ad::class)->findAll(),
            'socialNetworks' => $this->em->getRepository(SocialNetwork::class)->findAll(),
        ]);
    }

    #[Route('/search', name: 'client_search', methods: ['GET'])]
    public function search(Request $request, ClientRepository $clientRepository): Response
    {
        $clients = [];
        $ids = array_filter(explode(",", $request->get('ids')));

        foreach ($ids as $id) {
            $clients[] = $clientRepository->findOneBy(['id' => $id]);
        }

        return $this->render("client/search.html.twig", [
            "clients" => $clients,
        ]);
    }

    #[Route('/new', name: 'client_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($client);
            $this->em->flush();

            return $this->redirectToRoute('client_show', ['id' => $client->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('client/new.html.twig', [
            'currentClient' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'client_show', methods: ['GET'])]
    public function show(Client $currentClient, ClientRepository $clientRepository): Response
    {
        $clients = $clientRepository->findAll();
        $services = GetterServices::getServices($currentClient->getSiteClientToServicesBinders(), $this->em);

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
    public function edit(Request $request, Client $client): Response
    {
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            return $this->redirectToRoute('client_show', ['id' => $client->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('client/edit.html.twig', [
            'currentClient' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'client_delete', methods: ['POST'])]
    public function delete(Request $request, Client $client): Response
    {
        if ($this->isCsrfTokenValid('delete'.$client->getId(), $request->request->get('_token'))) {
            $this->em->remove($client);
            $this->em->flush();
        }

        return $this->redirectToRoute('client_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/remove-site/{id}', name: 'client_remove_site', methods: ['GET', 'POST'])]
    public function removeSite(Client $client, Site $site): Response
    {
        $client->removeSite($site);
        $this->em->flush();

        return $this->render('client/show.html.twig', [
            'currentClient' => $client,
        ]);
    }
}
