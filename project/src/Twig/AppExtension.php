<?php

namespace App\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    /**
     * @var UrlGeneratorInterface
     */
    private $generator;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @param UrlGeneratorInterface $generator
     * @param RequestStack $requestStack
     */
    public function __construct(UrlGeneratorInterface $generator, RequestStack $requestStack)
    {
        $this->generator = $generator;
        $this->requestStack = $requestStack;
    }

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

