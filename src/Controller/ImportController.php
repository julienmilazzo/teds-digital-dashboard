<?php

namespace App\Controller;

use App\Entity\{Ad, ClickAndCollect, Client, DomainName, FrenchEchoppe, Mail, Server, Site, SocialNetwork};
use App\Repository\ClientRepository;
use App\Form\{AdType,
    ClickAndCollectType,
    DomainNameType,
    FrenchEchoppeType,
    ImportCSVType,
    MailType,
    ServerType,
    SiteType,
    SocialNetworkType};
use DateInterval;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/import')]
class ImportController extends AbstractController
{
//TODO: Maybe we can simplify some code

    #[Route('/csv', name: 'import_csv', methods: ['GET', 'POST'])]
    public function importCSVFileAction(Request $request, EntityManagerInterface $entityManager, ClientRepository $clientRepository)
    {
        $form = $this->createForm(ImportCSVType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $csvFile */
            $csvFile = $form->get('csv')->getData();

            match ($form->get('choiceEntity')->getData()) {
                'client' => $this->importClient($csvFile, $entityManager),
                'nomDeDomaine' => $this->importDomainName($csvFile, $entityManager, $clientRepository),
                'serveur' => $this->importServer($csvFile, $entityManager, $clientRepository),
                'site' => $this->importSite($csvFile, $entityManager),
                'clickAndCollect' => $this->importClickAndCollect($csvFile, $entityManager),
                'frenchEchoppe' => $this->importFrenchEchoppe($csvFile, $entityManager),
                'mail' => $this->importMail($csvFile, $entityManager),
                'socialNetwork' => $this->importSocialNetwork($csvFile, $entityManager),
                'ad' => $this->importAd($csvFile, $entityManager),
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
    private function importDomainName(UploadedFile $csvFile, EntityManagerInterface $entityManager, ClientRepository $clientRepository)
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
                        'renewalDate' => new \DateTime($sRow[7]),
                    ];
                    if (!$domainName['renewalDate']) {
                        $domainName['renewalDate'] = date_add(new \DateTime($sRow[6]) , new DateInterval('P1Y'));
                    }

                    $futurDomainName = new DomainName();
                    $domainNameForm = $this->createForm(DomainNameType::class, $futurDomainName);
                    $domainNameForm->submit($domainName);
                    $domainNameForm->getData()
                        ->setStartDate($domainName['startDate'])
                        ->setRenewalDate($domainName['renewalDate'])
                        ->setClient($clientRepository->findOneBy(['name' => $sRow[8]]));

                    $entityManager->persist($domainNameForm->getData());
                }
            }
            $entityManager->flush();
        }
    }

    /**
     * @param UploadedFile $csvFile
     * @param EntityManagerInterface $entityManager
     * @return void
     */
    private function importClient(UploadedFile $csvFile, EntityManagerInterface $entityManager) {
        if ($csvFile && false !== ($fps = fopen($csvFile->getPath() . '/' . $csvFile->getFilename(), "r"))) {
            $indexCSV = 0;
            while (false !== ($sRow = fgetcsv($fps, 1000, ';'))) {
                ++$indexCSV;
                if (1 < $indexCSV) {
                    $site = [
                        'name' => $sRow[0],
                        'email' => $sRow[1],
                        'address' => $sRow[2],
                        'zipCode' => $sRow[3],
                        'city' => $sRow[4],
                        'phone' => $sRow[5],
                    ];

                    $futurSite = new Site();
                    $siteForm = $this->createForm(SiteType::class, $futurSite);
                    $siteForm->submit($site);

                    $entityManager->persist($siteForm->getData());
                }
            }
            $entityManager->flush();
        }
    }


    /**
     * @param UploadedFile $csvFile
     * @param EntityManagerInterface $entityManager
     * @param ClientRepository $clientRepository
     * @return void
     * @throws \Exception
     */
    private function importServer(UploadedFile $csvFile, EntityManagerInterface $entityManager, ClientRepository $clientRepository) {

        if ($csvFile && false !== ($fps = fopen($csvFile->getPath() . '/' . $csvFile->getFilename(), "r"))) {
            $indexCSV = 0;
            while (false !== ($sRow = fgetcsv($fps, 1000, ';'))) {
                ++$indexCSV;
                if (1 < $indexCSV) {
                    $server = [
                        'name' => $sRow[0],
                        'provider' => $sRow[1],
                        'offer' => $sRow[2],
                        'invoicedPrice' => $sRow[3],
                        'cost' => $sRow[4],
                        'renewalType' => $sRow[5],
                        'startDate' => new \DateTime($sRow[6]),
                        'renewalDate' => new \DateTime($sRow[7]),
                    ];
                    if (!$server['renewalDate']) {
                        $server['renewalDate'] = date_add(new \DateTime($sRow[6]) , new DateInterval('P1Y'));
                    }

                    $futurServer = new Server();
                    $serverForm = $this->createForm(ServerType::class, $futurServer);
                    $serverForm->submit($server);
                    /** @var Server $serverToComplete */
                    $serverToComplete = $serverForm->getData();
                    $serverToComplete->setStartDate($server['startDate'])
                        ->setRenewalDate($server['renewalDate']);

                    $serverForm->getData()->setClient($clientRepository->findOneBy(['name' => $sRow[8]]));

                    $entityManager->persist($serverForm->getData());
                }
            }
            $entityManager->flush();
        }
    }

    /**
     * @param UploadedFile $csvFile
     * @param EntityManagerInterface $entityManager
     * @return void
     * @throws \Exception
     */
    private function importSite(UploadedFile $csvFile, EntityManagerInterface $entityManager) {
        if ($csvFile && false !== ($fps = fopen($csvFile->getPath() . '/' . $csvFile->getFilename(), "r"))) {
            $indexCSV = 0;
            while (false !== ($sRow = fgetcsv($fps, 1000, ';'))) {
                ++$indexCSV;
                if (1 < $indexCSV) {
                    $site = [
                        'name' => $sRow[0],
                        'onlineDate' => new \DateTime($sRow[1]),
                        'online' => $sRow[2],
                    ];

                    $futurSite = new Site();
                    $siteForm = $this->createForm(SiteType::class, $futurSite);
                    $siteForm->submit($site);
                    $siteToComplete = $siteForm->getData();
                    $siteToComplete->setOnlineDate($site['onlineDate'])
                        ->setClient($entityManager->getRepository(Client::class)->findOneBy(['name' => $sRow[3]]))
                        ->addServer($entityManager->getRepository(Server::class)->findOneBy(['name' => $sRow[4]]));

                    $entityManager->persist($siteForm->getData());
                }
            }
            $entityManager->flush();
        }
    }

    /**
     * @param UploadedFile $csvFile
     * @param EntityManagerInterface $entityManager
     * @return void
     * @throws \Exception
     */
    private function importClickAndCollect(UploadedFile $csvFile, EntityManagerInterface $entityManager) {

        if ($csvFile && false !== ($fps = fopen($csvFile->getPath() . '/' . $csvFile->getFilename(), "r"))) {
            $indexCSV = 0;
            while (false !== ($sRow = fgetcsv($fps, 1000, ';'))) {
                ++$indexCSV;
                if (1 <= $indexCSV) {
                    $clickAndCollect = [
                        'provider' => $sRow[0],
                        'offer' => $sRow[1],
                        'invoicedPrice' => $sRow[2],
                        'cost' => $sRow[3],
                        'renewalType' => $sRow[4],
                        'startDate' => new \DateTime($sRow[5]),
                        'renewalDate' => new \DateTime($sRow[6]),
                        'onlineDate' => new \DateTime($sRow[7]),
                        'online' => $sRow[8],
                    ];
                    if (!$clickAndCollect['renewalDate']) {
                        $clickAndCollect['renewalDate'] = date_add(new \DateTime($sRow[5]) , new DateInterval('P1Y'));
                    }

                    $futurClickAndCollect = new ClickAndCollect();
                    $clickAndCollectForm = $this->createForm(ClickAndCollectType::class, $futurClickAndCollect);
                    $clickAndCollectForm->submit($clickAndCollect);

                    $clickAndCollectToComplete = $clickAndCollectForm->getData();
                    $clickAndCollectToComplete->setStartDate($clickAndCollect['startDate'])
                        ->setRenewalDate($clickAndCollect['renewalDate'])
                        ->setOnlineDate($clickAndCollect['onlineDate'])
                        ->setClient($entityManager->getRepository(Client::class)->findOneBy(['name' => $sRow[9]]))
                        ->setSite($entityManager->getRepository(Site::class)->findOneBy(['name' => $sRow[10]]))
                        ->setServer($entityManager->getRepository(Server::class)->findOneBy(['name' => $sRow[11]]));

                    $entityManager->persist($clickAndCollectForm->getData());
                }
            }
            $entityManager->flush();
        }
    }

    /**
     * @param UploadedFile $csvFile
     * @param EntityManagerInterface $entityManager
     * @return void
     * @throws \Exception
     */
    private function importFrenchEchoppe(UploadedFile $csvFile, EntityManagerInterface $entityManager) {

        if ($csvFile && false !== ($fps = fopen($csvFile->getPath() . '/' . $csvFile->getFilename(), "r"))) {
            $indexCSV = 0;
            while (false !== ($sRow = fgetcsv($fps, 1000, ';'))) {
                ++$indexCSV;
                if (1 <= $indexCSV) {
                    $frenchEchoppe = [
                        'provider' => $sRow[0],
                        'offer' => $sRow[1],
                        'invoicedPrice' => $sRow[2],
                        'cost' => $sRow[3],
                        'renewalType' => $sRow[4],
                        'startDate' => new \DateTime($sRow[5]),
                        'renewalDate' => new \DateTime($sRow[6]),
                        'onlineDate' => new \DateTime($sRow[7]),
                        'online' => $sRow[8],
                    ];
                    if (!$frenchEchoppe['renewalDate']) {
                        $frenchEchoppe['renewalDate'] = date_add(new \DateTime($sRow[5]) , new DateInterval('P1Y'));
                    }

                    $futurFrenchEchoppe = new FrenchEchoppe();
                    $frenchEchoppeForm = $this->createForm(FrenchEchoppeType::class, $futurFrenchEchoppe);
                    $frenchEchoppeForm->submit($frenchEchoppe);
                    $frenchEchoppeToComplete = $frenchEchoppeForm->getData();
                    $frenchEchoppeToComplete->getData()->setStartDate($frenchEchoppe['startDate'])
                        ->setRenewalDate($frenchEchoppe['renewalDate'])
                        ->setOnlineDate($frenchEchoppe['onlineDate'])
                        ->setClient($entityManager->getRepository(Client::class)->findOneBy(['name' => $sRow[9]]));

                    $entityManager->persist($frenchEchoppeForm->getData());
                }
            }
            $entityManager->flush();
        }
    }

    /**
     * @param UploadedFile $csvFile
     * @param EntityManagerInterface $entityManager
     * @return void
     * @throws \Exception
     */
    private function importMail(UploadedFile $csvFile, EntityManagerInterface $entityManager) {

        if ($csvFile && false !== ($fps = fopen($csvFile->getPath() . '/' . $csvFile->getFilename(), "r"))) {
            $indexCSV = 0;
            while (false !== ($sRow = fgetcsv($fps, 1000, ';'))) {
                ++$indexCSV;
                if (1 <= $indexCSV) {
                    $mail = [
                        'provider' => $sRow[0],
                        'offer' => $sRow[1],
                        'invoicedPrice' => $sRow[2],
                        'cost' => $sRow[3],
                        'renewalType' => $sRow[4],
                        'startDate' => new \DateTime($sRow[5]),
                        'renewalDate' => new \DateTime($sRow[6]),
                    ];
                    if (!$mail['renewalDate']) {
                        $mail['renewalDate'] = date_add(new \DateTime($sRow[5]) , new DateInterval('P1Y'));
                    }

                    $futurMail = new Mail();
                    $mailForm = $this->createForm(MailType::class, $futurMail);
                    $mailForm->submit($mail);
                    $mailToComplete = $mailForm->getData();
                    $mailToComplete->setStartDate($mail['startDate'])
                        ->setRenewalDate($mail['renewalDate'])
                        ->setClient($entityManager->getRepository(Client::class)->findOneBy(['name' => $sRow[7]]))
                        ->setDomainName($entityManager->getRepository(DomainName::class)->findOneBy(['url' => $sRow[8]]));

                    $entityManager->persist($mailForm->getData());
                }
            }
            $entityManager->flush();
        }
    }

    /**
     * @param UploadedFile $csvFile
     * @param EntityManagerInterface $entityManager
     * @return void
     * @throws \Exception
     */
    private function importSocialNetwork(UploadedFile $csvFile, EntityManagerInterface $entityManager) {

        if ($csvFile && false !== ($fps = fopen($csvFile->getPath() . '/' . $csvFile->getFilename(), "r"))) {
            $indexCSV = 0;
            while (false !== ($sRow = fgetcsv($fps, 1000, ';'))) {
                ++$indexCSV;
                if (1 <= $indexCSV) {
                    $socialNetwork = [
                        'provider' => $sRow[0],
                        'offer' => $sRow[1],
                        'invoicedPrice' => $sRow[2],
                        'cost' => $sRow[3],
                        'renewalType' => $sRow[4],
                        'startDate' => new \DateTime($sRow[5]),
                        'renewalDate' => new \DateTime($sRow[6]),
                        'postByWeek' => $sRow[7],
                        'whichSocialNetwork' => explode(",", $sRow[8]),
                    ];
                    if (!$socialNetwork['renewalDate']) {
                        $socialNetwork['renewalDate'] = date_add(new \DateTime($sRow[5]) , new DateInterval('P1Y'));
                    }

                    $futurSocialNetwork = new SocialNetwork();
                    $socialNetworkForm = $this->createForm(SocialNetworkType::class, $futurSocialNetwork);
                    $socialNetworkForm->submit($socialNetwork);
                    $socialNetworkToComplete = $socialNetworkForm->getData();
                    $socialNetworkToComplete->setStartDate($socialNetwork['startDate'])
                        ->setRenewalDate($socialNetwork['renewalDate'])
                        ->setWhichSocialNetwork($socialNetwork['whichSocialNetwork'])
                        ->setClient($entityManager->getRepository(Client::class)->findOneBy(['name' => $sRow[9]]));

                    $entityManager->persist($socialNetworkForm->getData());
                }
            }
            $entityManager->flush();
        }
    }

    /**
     * @param UploadedFile $csvFile
     * @param EntityManagerInterface $entityManager
     * @return void
     * @throws \Exception
     */
    private function importAd(UploadedFile $csvFile, EntityManagerInterface $entityManager) {

        if ($csvFile && false !== ($fps = fopen($csvFile->getPath() . '/' . $csvFile->getFilename(), "r"))) {
            $indexCSV = 0;
            while (false !== ($sRow = fgetcsv($fps, 1000, ';'))) {
                ++$indexCSV;
                if (1 <= $indexCSV) {
                    $ad = [
                        'provider' => $sRow[0],
                        'offer' => $sRow[1],
                        'invoicedPrice' => $sRow[2],
                        'cost' => $sRow[3],
                        'renewalType' => $sRow[4],
                        'startDate' => new \DateTime($sRow[5]),
                        'renewalDate' => new \DateTime($sRow[6]),
                        'endDate' => new \DateTime($sRow[7]),
                        'commentary' => $sRow[8],
                    ];
                    if (!$ad['renewalDate']) {
                        $ad['renewalDate'] = date_add(new \DateTime($sRow[5]) , new DateInterval('P1Y'));
                    }

                    $futurAd = new Ad();
                    $adForm = $this->createForm(AdType::class, $futurAd);
                    $adForm->submit($ad);
                    $adToComplete = $adForm->getData();

                    $adToComplete->setStartDate($ad['startDate'])
                        ->setRenewalDate($ad['renewalDate'])
                        ->setEndDate($ad['endDate'])
                        ->setClient($entityManager->getRepository(Client::class)->findOneBy(['name' => $sRow[9]]))
                        ->setSite($entityManager->getRepository(Site::class)->findOneBy(['name' => $sRow[10]]));

                    $entityManager->persist($adForm->getData());
                }
            }
            $entityManager->flush();
        }
    }
}
