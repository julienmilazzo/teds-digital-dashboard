<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use App\Util\Binder;
use App\Entity\DomainName;
use App\Form\DomainNameType;
use App\Repository\DomainNameRepository;
use DateInterval;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{JsonResponse, Request, Response};
use Symfony\Component\Routing\Annotation\Route;

#[Route('/domain-name')]
class DomainNameController extends AbstractController
{
    /**
     * @param EntityManagerInterface $em
     * @param DomainNameRepository $dr
     */
    public function __construct(private EntityManagerInterface $em, private DomainNameRepository $dr){}

    #[Route('/', name: 'domain_name_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $domainNames = $this->dr->findAllOrderByRenewalDate();

        return $this->render('domain_name/index.html.twig', [
            'domainNames' => $domainNames,
        ]);
    }

    #[Route('/search', name: 'domain_name_search', methods: ['GET'])]
    public function search(Request $request): Response
    {
        $domainNames = [];
        $ids = array_filter(explode(",", $request->get('ids')));
        foreach ($ids as $id) {
            $domainNames[] = $this->dr->findOneBy(['id' => $id]);
        }

        return $this->render('domain_name/search.html.twig', [
            'domain_names' => $domainNames
        ]);
    }

    #[Route('/new', name: 'domain_name_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ClientRepository $cr): Response
    {
        $domainName = new DomainName();
        if ($clientId = $request->get('clientId')) {
            if ($client = $cr->find($clientId)) {
                $domainName->setClient($client);
            }
        }
        $form = $this->createForm(DomainNameType::class, $domainName);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var DomainName $domainName */
            $domainName = $form->getData();

            $this->em->persist($domainName);
            $this->em->flush();

            Binder::set($domainName, $this->em);

            return $this->redirectToRoute('domain_name_show', ['id' => $domainName->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('domain_name/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'domain_name_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DomainName $domainName): Response
    {
        $form = $this->createForm(DomainNameType::class, $domainName);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var DomainName $domainName */
            $domainName = $form->getData();

            $this->em->persist($domainName);
            $this->em->flush();

            return $this->redirectToRoute('domain_name_show', ['id' => $domainName->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('domain_name/edit.html.twig', [
            'currentDomainName' => $domainName,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'domain_name_delete', methods: ['POST'])]
    public function delete(Request $request, DomainName $domainName): Response
    {
        if ($this->isCsrfTokenValid('delete'.$domainName->getId(), $request->request->get('_token'))) {
            $this->em->remove($domainName);
            $this->em->flush();
        }

        return $this->redirectToRoute('domain_name_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/update-renewal-date/{id}', name: 'updateAddRenewalDate', methods: ['GET'])]
    public function updateAddRenewalDate(Request $request, DomainName $domainName, EntityManagerInterface $em)
    {
        $newDate = $domainName->getRenewalDate()->add(new DateInterval('P1Y'));

        $domainName->setRenewalDate(new \DateTime($newDate->format('Y-m-d')) );
        $em->persist($domainName);
        $em->flush();
        return new JsonResponse(true);
    }
}
