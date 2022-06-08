<?php

namespace App\Command;

use App\Entity\{Ad, ClickAndCollect, Client, DomainName, FrenchEchoppe, Mail, Server, SocialNetwork};
use App\Repository\ClientRepository;
use App\Util\GetterServices;
use App\Util\SendMail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\{InputArgument, InputInterface};
use Symfony\Component\Console\Output\OutputInterface;

class EndOfSubscriptionCommand extends Command
{
    protected static $defaultName = 'app:end-of-sub';
    protected static $defaultDescription = "Liste des abonnements qui se terminent bientôt, par client :";
    /**
     * @var ClientRepository
     */
    private $clientRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var \Swift_Mailer
     */
    private $mailer; //Todo change mailer because outdated

    /**
     * @param ClientRepository $clientRepository
     * @param EntityManagerInterface $entityManager
     * @param \Swift_Mailer $mailer
     */
    public function __construct(ClientRepository $clientRepository, EntityManagerInterface $entityManager, \Swift_Mailer $mailer)
    {
        $this->clientRepository = $clientRepository;
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->addArgument('nextWhat', InputArgument::REQUIRED, 'Recherche pour la semaine/mois/année suivant')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $toDate = [
            'semaine' => '7 days',
            'mois' => '1 month',
            'annee' => '1 year'
        ];

        $date = new \DateTime();
        $date->add(\DateInterval::createFromDateString($toDate[$input->getArgument('nextWhat')]));

        $clients = $this->clientRepository->findAll();
        $output->writeln('Les abonnements qui finnissent le/la/l\' ' . $input->getArgument('nextWhat') . ' prochain :');
        $mailSubject = 'Les abonnements qui finnissent le/la/l\' ' . $input->getArgument('nextWhat') . ' prochain :';
        $mailView = '';
        /** @var Client $client */
        foreach ($clients as $client) {
            $output->writeln("===============================");
            $services = GetterServices::getServices($client->getSiteClientToServicesBinders(), $this->entityManager);
            $output->writeln("Client : " . $client->getName());
            $output->writeln(" ");
            $mailView .= '<hr><br><h3> Client : ' . $client->getName() . '</h3>';
            $mailView .= '<h4> Nom de domaine :</h4><ul> ';

            /** @var DomainName $domainName */
            foreach ($services['domainNames'] as $domainName) {
                if ($date >= $domainName->getRenewalDate()) {
                    $output->writeln("--------------------------");
                    $output->writeln("Nom de domaine : " . $domainName->getUrl() . " fin le " . ($domainName->getRenewalDate())->format("d-m-Y"));
                    $output->writeln(" ");
                    $mailView .='<li>' . '<b>' . $domainName->getUrl() . ' :</b>' . ' fin le <u class="text-danger">' . ($domainName->getRenewalDate())->format("d-m-Y") . '</u></li><br>';
                }
            }
            if (!count($services['domainesNames'])) {
                $mailView .= '-';
            }
            $mailView .= '</ul><h4> Click\'N Collect:</h4><ul> ';
            /** @var ClickAndCollect $clickAndCollect */
            foreach ($services['clickAndCollects'] as $clickAndCollect) {
                if ($date >= $clickAndCollect->getRenewalDate()) {
                    $output->writeln("--------------------------");
                    $output->writeln("Click'N Collect : " . " fin le " . ($clickAndCollect->getRenewalDate())->format("d-m-Y"));
                    $output->writeln(" ");
                    $mailView .= '<li>' . ' fin le <u class="text-danger">' . ($clickAndCollect->getRenewalDate())->format("d-m-Y") . '</u></li><br>';
                }
            }

            if (!count($services['clickAndCollects'])) {
                $mailView .= '-';
            }
            $mailView .= '</ul><h4> Mail :</h4><ul> ';
            /** @var Mail $mail */
            foreach ($services['mails'] as $mail) {
                if ($date >= $mail->getRenewalDate()) {
                    $output->writeln("--------------------------");
                    $output->writeln("Mail : " . " fin le " . ($mail->getRenewalDate())->format("d-m-Y"));
                    $output->writeln(" ");
                    $mailView .= '<li>' . ' fin le <u class="text-danger">' . ($mail->getRenewalDate())->format("d-m-Y") . '</u></li><br>';
                }
            }

            if (!count($services['mails'])) {
                $mailView .= '-';
            }
            $mailView .= '</ul><h4> French Echoppe :</h4><ul> ';
            /** @var FrenchEchoppe $frenchEchoppe */
            foreach ($services['frenchEchoppes'] as $frenchEchoppe) {
                if ($date >= $frenchEchoppe->getRenewalDate()) {
                    $output->writeln("--------------------------");
                    $output->writeln("French Echoppe : " . " fin le " . ($frenchEchoppe->getRenewalDate())->format("d-m-Y"));
                    $output->writeln(" ");
                    $mailView .= '<li>' . ' fin le <u class="text-danger">' . ($frenchEchoppe->getRenewalDate())->format("d-m-Y") . '</u></li><br>';
                }
            }

            if (!count($services['frenchEchoppes'])) {
                $mailView .= '-';
            }
            $mailView .= '</ul><h4> Ad :</h4><ul> ';
            /** @var Ad $ad */
            foreach ($services['ads'] as $ad) {
                if ($date >= $ad->getRenewalDate()) {
                    $output->writeln("--------------------------");
                    $output->writeln("Ad : " . " fin le " . ($ad->getRenewalDate())->format("d-m-Y"));
                    $output->writeln(" ");
                    $mailView .= '<li>' . ' fin le <u class="text-danger">' . ($ad->getRenewalDate())->format("d-m-Y") . '</u></li><br>';
                }
            }

            if (!count($services['ads'])) {
                $mailView .= '-';
            }
            $mailView .= '</ul><h4> Réseaux sociaux :</h4><ul> ';
            /** @var SocialNetwork $socialNetwork */
            foreach ($services['socialNetworks'] as $socialNetwork) {
                if ($date >= $socialNetwork->getRenewalDate()) {
                    $output->writeln("--------------------------");
                    $output->writeln("Réseaux sociaux : " . " fin le " . ($socialNetwork->getRenewalDate())->format("d-m-Y"));
                    $output->writeln(" ");
                    $mailView .= '<li>' . ' fin le <u class="text-danger">' . ($socialNetwork->getRenewalDate())->format("d-m-Y") . '</u></li><br>';
                }
            }

            if (!count($services['socialNetworks'])) {
                $mailView .= '-';
            }
            $mailView .= '</ul><h4> Hébergements :</h4><ul>';

            /** @var Server $server */
            foreach ($client->getServers() as $server) {
                if ($date >= $server->getRenewalDate()) {
                    $output->writeln("--------------------------");
                    $output->writeln("Hébergement : " . $server->getName() . " fin le " . ($server->getRenewalDate())->format("d-m-Y"));
                    $output->writeln(" ");
                    $mailView .= '<li>' . $server->getName() . ' fin le <u class="text-danger">' . ($server->getRenewalDate())->format("d-m-Y") . '</u></li><br>';
                }
            }
            if (!count($client->getServers())) {
                $mailView .= '-';
            }
            $mailView .= '</ul>';
        }

        SendMail::sendOrderMail($mailView, $mailSubject, $this->mailer);

        return Command::SUCCESS;
    }
}
