<?php

namespace App\Util;

use App\Entity\Service;
use App\Entity\SiteClientToServicesBinder;
use Doctrine\ORM\EntityManagerInterface;

class Binder
{
    /**
     * @param $service
     * @param EntityManagerInterface $entityManager
     * @return void
     */
    static function set($service, EntityManagerInterface $entityManager)
    {
        $siteClientToServicesBinder = new SiteClientToServicesBinder();
        $siteClientToServicesBinder
            ->setClient($service->getClient())
            ->setServiceId($service->getId());

        if ('App\Entity\Ad' === get_class($service) || 'App\Entity\ClickAndCollect' === get_class($service) || 'App\Entity\DomainName' === get_class($service)) {
            $siteClientToServicesBinder->setSite($service->getSite() ?: null);
        }

        match (get_class($service)) {
            'App\Entity\Ad' => $siteClientToServicesBinder->setType(Service::AD),
            'App\Entity\SocialNetwork' => $siteClientToServicesBinder->setType(Service::SOCIAL_NETWORK),
            'App\Entity\ClickAndCollect' => $siteClientToServicesBinder->setType(Service::CLICK_AND_COLLECT),
            'App\Entity\FrenchEchoppe' => $siteClientToServicesBinder->setType(Service::FRENCH_ECHOPPE),
            'App\Entity\DomainName' => $siteClientToServicesBinder->setType(Service::DOMAIN_NAME),
            'App\Entity\Mail' => $siteClientToServicesBinder->setType(Service::MAIL),
        };

        $entityManager->persist($siteClientToServicesBinder);
        $entityManager->flush();
        $service->setSiteClientToServicesBinderId($siteClientToServicesBinder->getId());

        $entityManager->persist($service);
        $entityManager->flush();
    }
}
