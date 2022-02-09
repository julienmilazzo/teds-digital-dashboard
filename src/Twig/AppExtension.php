<?php

namespace App\Twig;

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
}

