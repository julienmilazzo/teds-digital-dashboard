<?php

namespace App\Util;

use App\Entity\{Ad,
    ClickAndCollect,
    DomainName,
    FrenchEchoppe,
    Mail,
    Service,
    SiteClientToServicesBinder,
    SocialNetwork};
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;use Monolog\Handler\Curl\Util;

class GetterServices
{
    /**
     * @param Collection $siteClientToServicesBinders
     * @param EntityManagerInterface $entityManager
     * @return array[]
     */
    static function getServices(Collection $siteClientToServicesBinders, EntityManagerInterface $entityManager)
    {
        $domainNames =  [];
        $clickAndCollects =  [];
        $mails =  [];
        $ads =  [];
        $socialNetworks =  [];
        $frenchEchoppes =  [];

        $serviceRepositoryMapping = [
            Service::DOMAIN_NAME => DomainName::class,
            Service::CLICK_AND_COLLECT => ClickAndCollect::class,
            Service::MAIL => Mail::class,
            Service::AD => Ad::class,
            Service::SOCIAL_NETWORK => SocialNetwork::class,
            Service::FRENCH_ECHOPPE => FrenchEchoppe::class,
        ];

        /** @var SiteClientToServicesBinder $siteClientToServicesBinder */
        foreach ($siteClientToServicesBinders as $siteClientToServicesBinder) {
            $data = $entityManager->getRepository($serviceRepositoryMapping[$siteClientToServicesBinder->getType()])->findOneBy(['id' => $siteClientToServicesBinder->getServiceId()]);
            match ($siteClientToServicesBinder->getType()){
                Service::DOMAIN_NAME => $domainNames[] = $data,
                Service::CLICK_AND_COLLECT => $clickAndCollects[] = $data,
                Service::MAIL => $mails[] = $data,
                Service::AD => $ads[] = $data,
                Service::SOCIAL_NETWORK => $socialNetworks[] = $data,
                Service::FRENCH_ECHOPPE => $frenchEchoppes[] = $data,
            };
        }

        return ['domainNames' => $domainNames, 'clickAndCollects' => $clickAndCollects, 'mails' => $mails, 'frenchEchoppes' => $frenchEchoppes, 'ads' => $ads, 'socialNetworks' => $socialNetworks];
    }
}
