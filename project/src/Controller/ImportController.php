<?php

namespace App\Controller;

use App\Form\ImportCSVType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/import')]
class ImportController extends AbstractController
{

    #[Route('/csv', name: 'import_csv', methods: ['GET', 'POST'])]
    public function importCSVFileAction(Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(ImportCSVType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $csvFile */
            $csvFile = $form->get('csv')->getData();
            dd($csvFile);
        }

        return $this->render('Import/import-csv.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
