<?php

use App\Entity\{ClickAndCollect, DomainName, Service, SiteClientToServicesBinder};
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

class GetterServices
{
    /**
     * @param Collection $siteClientToServicesBinders
     * @param EntityManagerInterface $entityManager
     * @return array[]
     */
    static function getClientServices(Collection $siteClientToServicesBinders, EntityManagerInterface $entityManager)
    {
        $domainNames =  [];
        $clickAndCollects =  [];

        $serviceRepositoryMapping = [
            Service::DOMAIN_NAME => DomainName::class,
            Service::CLICK_AND_COLLECT => ClickAndCollect::class
        ];

        /** @var SiteClientToServicesBinder $siteClientToServicesBinder */
        foreach ($siteClientToServicesBinders as $siteClientToServicesBinder) {
            $data = $entityManager->getRepository($serviceRepositoryMapping[$siteClientToServicesBinder->getType()])->findOneBy(['id' => $siteClientToServicesBinder->getServiceId()]);
            match ($siteClientToServicesBinder->getType()){
                Service::DOMAIN_NAME => $domainNames[] = $data,
                Service::CLICK_AND_COLLECT => $clickAndCollects[] = $data,
            };
        }

        return [$domainNames, $clickAndCollects];
    }

    /**
     * @param Collection $siteClientToServicesBinders
     * @param EntityManagerInterface $entityManager
     * @return array[]
     */
    static function getSiteServices(Collection $siteClientToServicesBinders, EntityManagerInterface $entityManager)
    {
        $domainNames =  [];
        $clickAndCollects =  [];

        $serviceRepositoryMapping = [
            Service::DOMAIN_NAME => DomainName::class,
            Service::CLICK_AND_COLLECT => ClickAndCollect::class
        ];

        /** @var SiteClientToServicesBinder $siteClientToServicesBinder */
        foreach ($siteClientToServicesBinders as $siteClientToServicesBinder) {
            $data = $entityManager->getRepository($serviceRepositoryMapping[$siteClientToServicesBinder->getType()])->findOneBy(['id' => $siteClientToServicesBinder->getServiceId()]);
            match ($siteClientToServicesBinder->getType()){
                Service::DOMAIN_NAME => $domainNames[] = $data,
                Service::CLICK_AND_COLLECT => $clickAndCollects[] = $data,
            };
        }

        return [$domainNames, $clickAndCollects];
    }
}
