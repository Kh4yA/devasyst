<?php

namespace App\Utils;

class Pagination
{
    protected $item;
    protected $elementByPage;
    protected $currentPage;
    protected $nbPage;
    protected $offset;

    public function __construct($item,int $elementByPage = 10)
    {
        $this->item = $item;
        $this->elementByPage = $elementByPage;
        $this->pagination();
    }
    /**
     * Role : Prepare les principales données pour gerer la pagination
     *  @param $item ( données a paginer )
     *  @param $limit ( nombre de données a afficher a 10 par defaut)
     *  @return array ($nbEntry, $nbPage, $currentPage, $offset)
     */
    public function pagination()
    {
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $nbEntry = !empty($this->item) ? count($this->item) : 0;
        $this->nbPage = (int) ceil($nbEntry / $this->elementByPage);
        $this->currentPage = ($currentPage > $this->nbPage) ? 1 : $currentPage;
        $this->offset = ($currentPage - 1) * $this->elementByPage;
    }
    /**
     * Récupère les éléments de la page courante.
     */
    public function getPaginatedItems()
    {
        return array_slice($this->item, $this->offset, $this->elementByPage);
    }
    /**
     * Get the value of currentPage
     */ 
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * Get the value of nbPage
     */ 
    public function getNbPage()
    {
        return $this->nbPage;
    }

    /**
     * Get the value of offset
     */ 
    public function getOffset()
    {
        return $this->offset;
    }
}
