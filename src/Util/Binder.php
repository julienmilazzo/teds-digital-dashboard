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

        $classes = [Ad::class, ClickAndCollect::class, DomainName::class];
        $services = array_filter($classes, function ($class) use ($service) {
            return ($service instanceof $class);
        });
        if (count($services)) {
            $siteClientToServicesBinder->setSite($service->getSite() ?: null);
        }

        $serviceRepositoryMapping = [
            Ad::class => Service::AD,
            SocialNetwork::class => Service::SOCIAL_NETWORK,
            ClickAndCollect::class => Service::CLICK_AND_COLLECT,
            FrenchEchoppe::class => Service::FRENCH_ECHOPPE,
            DomainName::class => Service::DOMAIN_NAME,
            Mail::class => Service::MAIL,
        ];
        $siteClientToServicesBinder->setType($serviceRepositoryMapping[get_class($service)]);

        $entityManager->persist($siteClientToServicesBinder);
        $entityManager->flush();
        $service->setSiteClientToServicesBinderId($siteClientToServicesBinder->getId());

        $entityManager->persist($service);
        $entityManager->flush();
    }
}
