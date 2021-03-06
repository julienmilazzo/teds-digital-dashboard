<?php

namespace App\Controller;

use App\Util\Binder;
use App\Entity\Mail;
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
    #[Route('/', name: 'mail_index', methods: ['GET'])]
    public function index(MailRepository $mailRepository): Response
    {
        $mails = $mailRepository->findAllOrderByRenewalDate();

        return $this->render('mail/index.html.twig', [
            'mails' => $mails,
            'currentMail' => $mails[0] ?? null
        ]);
    }

    #[Route('/search', name: 'mail_search', methods: ['GET'])]
    public function search(Request $request, MailRepository $mailRepository): Response
    {
        $mails= [];
        $ids = array_filter(explode(",", $request->get('ids')));
        foreach ($ids as $id) {
            $mails[] = $mailRepository->findOneBy(['id' => $id]);
        }

        return $this->render("mail/search.html.twig", [
            'mails' => $mails,
        ]);
    }

    #[Route('/ordered/{id}', name: 'mail_ordered', methods: ['GET'])]
    public function ordered(Request $request, MailRepository $mailRepository, Mail $mail): Response
    {
        $orderBy = ('ASC' === $request->get('orderBy')) ? 'DESC' : 'ASC';

        return $this->render('mail/index.html.twig', [
            'mails' => $mailRepository->findBy([], [$request->get('orderedType') => $orderBy]),
            'orderBy' => $orderBy,
            'currentMail' => $mail
        ]);
    }

    #[Route('/new', name: 'mail_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $mail = new Mail();
        $form = $this->createForm(MailType::class, $mail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($mail);
            $entityManager->flush();

            Binder::set($mail, $entityManager);

            return $this->redirectToRoute('mail_show', ['id' => $mail->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('mail/new.html.twig', [
            'currentMail' => $mail,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'mail_show', methods: ['GET'])]
    public function show(Mail $mail, MailRepository $mailRepository): Response
    {
        $mails = $mailRepository->findAll();

        return $this->render('mail/index.html.twig', [
            'mails' => $mails,
            'currentMail' => $mail
        ]);
    }

    #[Route('/{id}/edit', name: 'mail_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Mail $mail, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MailType::class, $mail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('mail_show', ['id' => $mail->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('mail/edit.html.twig', [
            'currentMail' => $mail,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'mail_delete', methods: ['POST'])]
    public function delete(Request $request, Mail $mail, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mail->getId(), $request->request->get('_token'))) {
            $entityManager->remove($mail);
            $entityManager->flush();
        }

        return $this->redirectToRoute('mail_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/update-renewal-date/{id}', name: 'updateAddRenewalDate', methods: ['GET'])]
    public function updateAddRenewalDate(Request $request, Mail $mail, EntityManagerInterface $em)
    {
        $newDate = $mail->getRenewalDate()->add(new DateInterval('P1Y'));

        $mail->setRenewalDate(new \DateTime($newDate->format('Y-m-d')) );
        $em->persist($mail);
        $em->flush();
        return new JsonResponse(true);
    }
}
