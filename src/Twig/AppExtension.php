<?php

namespace App\Twig;

use App\Entity\Client;
use Doctrine\Common\Collections\Collection;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('sort', [$this, 'howIsSorted']),
            new TwigFunction('direction', [$this, 'howIsDirected']),
            new TwigFunction('hasOneIn', [$this, 'hasOneIn']),
        ];
    }

    /**
     * @param $orderedType
     * @param $expectedType
     * @param $orderBy
     * @return string
     */
    public function howIsSorted($orderedType, $expectedType, $orderBy = null)
    {
        $sort = (null === $orderBy || 'ASC' === $orderBy) ? 'ASC' : 'DESC';
        return $orderedType === $expectedType ? $sort : '';
    }

    /**
     * @param $orderedType
     * @param $expectedType
     * @param $orderBy
     * @return string
     */
    public function howIsDirected($orderedType, $expectedType, $orderBy = null)
    {
        $direction = (null === $orderBy || 'ASC' === $orderBy) ? 'down' : 'up';
        return $orderedType === $expectedType ? $direction : 'up';
    }

    /**
     * @param Client $client
     * @param array $services
     * @return bool
     */
    public function hasOneIn(Client $client, array $services) {
        $count = 0;
        foreach ($services as $service) {
            if ($service->getClient() === $client) {
                ++$count;
            }
        }
        return ($count >= 1);
    }
}

