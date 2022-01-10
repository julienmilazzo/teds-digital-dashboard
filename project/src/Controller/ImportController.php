<?php

namespace App\Controller;

use App\Entity\DomainName;
use App\Form\{DomainNameType, ImportCSVType};
use DateInterval;
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

            match ($form->get('choiceEntity')->getData()) {
                'client' => $this->importClient($csvFile, $entityManager),
                'nomDeDomaine' => $this->importDomainName($csvFile, $entityManager),
                'serveur' => $this->importServer($csvFile, $entityManager),
                'site' => $this->importSite($csvFile, $entityManager),
            };
        }

        return $this->render('Import/import-csv.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param UploadedFile $csvFile
     * @param EntityManagerInterface $entityManager
     * @return void
     * @throws \Exception
     */
    private function importDomainName(UploadedFile $csvFile, EntityManagerInterface $entityManager)
    {
        if ($csvFile && false !== ($fps = fopen($csvFile->getPath() . '/' . $csvFile->getFilename(), "r"))) {
            $indexCSV = 0;
            while (false !== ($sRow = fgetcsv($fps, 1000, ';'))) {
                ++$indexCSV;
                if (1 < $indexCSV) {
                    $domainName = [
                        'url' => $sRow[0],
                        'provider' => $sRow[1],
                        'offer' => $sRow[2],
                        'invoicedPrice' => $sRow[3],
                        'cost' => $sRow[4],
                        'renewalType' => $sRow[5],
                        'startDate' => new \DateTime($sRow[6]),
                        'renewalDate' => $sRow[7],
                    ];
                    if ("" === $domainName['renewalDate']) {
                        $domainName['renewalDate'] = date_add(new \DateTime($sRow[6]) , new DateInterval('P1Y'));
                    }

                    $futurDomainName = new DomainName();
                    $domainNameForm = $this->createForm(DomainNameType::class, $futurDomainName);
                    $domainNameForm->submit($domainName);
                    /** @var DomainName $domainNameToComplete */
                    $domainNameToComplete = $domainNameForm->getData();
                    $domainNameToComplete->setStartDate($domainName['startDate']);
                    $domainNameToComplete->setRenewalDate($domainName['renewalDate']);
                    //TO DO ajouter le lien avec le client
                    $entityManager->persist($domainNameForm->getData());
                }
            }
            $entityManager->flush();
        }
    }


    private function importClient(UploadedFile $csvFile, EntityManagerInterface $entityManager) {

    }


    private function importServer(UploadedFile $csvFile, EntityManagerInterface $entityManager) {

    }


    private function importSite(UploadedFile $csvFile, EntityManagerInterface $entityManager) {

    }
}
