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
        ];
    }

    public function howIsSorted($orderedType, $expectedType, $orderBy = null)
    {
        $returnArray = [];
        $returnArray[] = (null === $orderBy || 'ASC' === $orderBy) ? 'ASC' : 'DESC';
        $returnArray[] = ('ASC' === $returnArray[0]) ? 'down' : 'up';
        return $orderedType === $expectedType ? $returnArray : ['', 'up'];
    }
}

