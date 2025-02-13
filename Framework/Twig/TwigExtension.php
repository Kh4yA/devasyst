<?php

namespace App\Framework\Twig;

use App\Framework\Service\FilterMemory;
use App\Framework\Service\Flash;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigExtension extends AbstractExtension
{
    private $flash;
    private $filter;

    // Injection du service Flash dans le constructeur
    public function __construct(Flash $flash, FilterMemory $filter)
    {
        $this->flash = $flash;
        $this->filter = $filter;
    }


    /**
     * Utiliser getFunctions() au lieu de getFunction()
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('flash', [$this, 'getFlash']),
            new TwigFunction('filterMemory', [$this, 'filterMemory']),
        ];
    }

    /**
     * Méthode qui sera appelée par la fonction Twig
     * @param [type] $type
     * @return string|null
     */
    public function getFlash($type): ?string
    {
        return $this->flash->get($type);
    }

    /**
     * Méthode qui sera appelée par la fonction Twig
     * @param [type] $type 
     * @return string|null
     */
    public function filterMemory(string $type): ?string
    {
        return  $this->filter->get($type);
    }
}
