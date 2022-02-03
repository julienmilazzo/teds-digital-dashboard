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

    private $clientRepository;

    private $entityManager;

    private $mailer;

    public function __construct(ClientRepository $clientRepository, EntityManagerInterface $entityManager, \Swift_Mailer $mailer)
    {
        $this->clientRepository = $clientRepository;
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            // configure an argument
            ->addArgument('nextWhat', InputArgument::REQUIRED, 'Recherche pour la semaine/mois/année suivant')
            // ...
        ;
    }

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

            $count = 0;
            /** @var DomainName $domainName */
            foreach ($services['domainNames'] as $domainName) {
                if ($date >= $domainName->getRenewalDate()) {
                    ++$count;
                    $output->writeln("--------------------------");
                    $output->writeln("Nom de domaine : " . $domainName->getUrl() . " fin le " . ($domainName->getRenewalDate())->format("d-m-Y"));
                    $output->writeln(" ");
                    $mailView .='<li>' . '<b>' . $domainName->getUrl() . ' :</b>' . ' fin le <u style="color: red">' . ($domainName->getRenewalDate())->format("d-m-Y") . '</u></li><br>';
                }
            }
            if (0 === $count) {
                $mailView .= '-';
            } else {
                $count = 0;
            }
            $mailView .= '</ul><h4> Click\'N Collect:</h4><ul> ';
            /** @var ClickAndCollect $clickAndCollect */
            foreach ($services['clickAndCollects'] as $clickAndCollect) {
                if ($date >= $clickAndCollect->getRenewalDate()) {
                    ++$count;
                    $output->writeln("--------------------------");
                    $output->writeln("Click'N Collect : " . " fin le " . ($clickAndCollect->getRenewalDate())->format("d-m-Y"));
                    $output->writeln(" ");
                    $mailView .= '<li>' . ' fin le <u style="color: red">' . ($clickAndCollect->getRenewalDate())->format("d-m-Y") . '</u></li><br>';
                }
            }

            if (0 === $count) {
                $mailView .= '-';
            } else {
                $count = 0;
            }
            $mailView .= '</ul><h4> Mail :</h4><ul> ';
            /** @var Mail $mail */
            foreach ($services['mails'] as $mail) {
                if ($date >= $mail->getRenewalDate()) {
                    ++$count;
                    $output->writeln("--------------------------");
                    $output->writeln("Mail : " . " fin le " . ($mail->getRenewalDate())->format("d-m-Y"));
                    $output->writeln(" ");
                    $mailView .= '<li>' . ' fin le <u style="color: red">' . ($mail->getRenewalDate())->format("d-m-Y") . '</u></li><br>';
                }
            }

            if (0 === $count) {
                $mailView .= '-';
            } else {
                $count = 0;
            }
            $mailView .= '</ul><h4> French Echoppe :</h4><ul> ';
            /** @var FrenchEchoppe $frenchEchoppe */
            foreach ($services['frenchEchoppes'] as $frenchEchoppe) {
                if ($date >= $frenchEchoppe->getRenewalDate()) {
                    ++$count;
                    $output->writeln("--------------------------");
                    $output->writeln("French Echoppe : " . " fin le " . ($frenchEchoppe->getRenewalDate())->format("d-m-Y"));
                    $output->writeln(" ");
                    $mailView .= '<li>' . ' fin le <u style="color: red">' . ($frenchEchoppe->getRenewalDate())->format("d-m-Y") . '</u></li><br>';
                }
            }

            if (0 === $count) {
                $mailView .= '-';
            } else {
                $count = 0;
            }
            $mailView .= '</ul><h4> Ad :</h4><ul> ';
            /** @var Ad $ad */
            foreach ($services['ads'] as $ad) {
                if ($date >= $ad->getRenewalDate()) {
                    ++$count;
                    $output->writeln("--------------------------");
                    $output->writeln("Ad : " . " fin le " . ($ad->getRenewalDate())->format("d-m-Y"));
                    $output->writeln(" ");
                    $mailView .= '<li>' . ' fin le <u style="color: red">' . ($ad->getRenewalDate())->format("d-m-Y") . '</u></li><br>';
                }
            }

            if (0 === $count) {
                $mailView .= '-';
            } else {
                $count = 0;
            }
            $mailView .= '</ul><h4> Réseaux sociaux :</h4><ul> ';
            /** @var SocialNetwork $socialNetwork */
            foreach ($services['socialNetworks'] as $socialNetwork) {
                if ($date >= $socialNetwork->getRenewalDate()) {
                    ++$count;
                    $output->writeln("--------------------------");
                    $output->writeln("Réseaux sociaux : " . " fin le " . ($socialNetwork->getRenewalDate())->format("d-m-Y"));
                    $output->writeln(" ");
                    $mailView .= '<li>' . ' fin le <u style="color: red">' . ($socialNetwork->getRenewalDate())->format("d-m-Y") . '</u></li><br>';
                }
            }

            if (0 === $count) {
                $mailView .= '-';
            } else {
                $count = 0;
            }
            $mailView .= '</ul><h4> Serveurs :</h4><ul>';

            /** @var Server $server */
            foreach ($client->getServers() as $server) {
                if ($date >= $server->getRenewalDate()) {
                    ++$count;
                    $output->writeln("--------------------------");
                    $output->writeln("Serveur : " . $server->getName() . " fin le " . ($server->getRenewalDate())->format("d-m-Y"));
                    $output->writeln(" ");
                    $mailView .= '<li>' . $server->getName() . ' fin le <u style="color: red">' . ($server->getRenewalDate())->format("d-m-Y") . '</u></li><br>';
                }
            }
            if (0 === $count) {
                $mailView .= '-';
            }
            $mailView .= '</ul>';
        }

        SendMail::sendOrderMail($mailView, $mailSubject, $this->mailer);

        return Command::SUCCESS;
    }
}
