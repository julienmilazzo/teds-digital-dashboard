<?php

namespace App\Controller;

use App\Util\Binder;
use App\Entity\{Client, Mail};
use App\Form\MailType;
use App\Repository\MailRepository;
use DateInterval;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{JsonResponse, Request, Response};
use Symfony\Component\Routing\Annotation\Route;

#[Route('/mail')]
class MailController extends AbstractController
{
    /**
     * @param EntityManagerInterface $em
     * @param MailRepository $mailRepository
     */
    public function __construct(private EntityManagerInterface $em, private MailRepository $mailRepository){}

    #[Route('/', name: 'mail_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('mail/index.html.twig', [
            'mails' => $this->mailRepository->findAllOrderByRenewalDate(),
        ]);
    }

    #[Route('/new', name: 'mail_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $mail = new Mail();
        if ($clientId = $request->get('clientId')) {
            if ($client = $this->em->getRepository(Client::class)->find($clientId)) {
                $mail->setClient($client);
            }
        }

        $form = $this->createForm(MailType::class, $mail);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($mail);
            $this->em->flush();

            Binder::set($mail, $this->em);

            return $this->redirectToRoute('mail_index');
        }

        return $this->renderForm('mail/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'mail_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Mail $mail): Response
    {
        $form = $this->createForm(MailType::class, $mail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            return $this->redirectToRoute('mail_index');
        }

        return $this->renderForm('mail/edit.html.twig', [
            'currentMail' => $mail,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'mail_delete', methods: ['POST'])]
    public function delete(Request $request, Mail $mail): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mail->getId(), $request->request->get('_token'))) {
            $this->em->remove($mail);
            $this->em->flush();
        }

        return $this->redirectToRoute('mail_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/update-renewal-date/{id}', name: 'updateAddRenewalDate', methods: ['GET'])]
    public function updateAddRenewalDate(Mail $mail)
    {
        $newDate = $mail->getRenewalDate()->add(new DateInterval('P1Y'));

        $mail->setRenewalDate(new \DateTime($newDate->format('Y-m-d')) );
        $this->em->persist($mail);
        $this->em->flush();

        return new JsonResponse(true);
    }
}
