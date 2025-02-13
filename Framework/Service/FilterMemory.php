<?php

namespace App\Framework\Service;

use App\Framework\Session\PHPSession;

class FilterMemory
{

    private object $session;
    private string $sessionKey = '';

    public function __construct($sessionKey = ''){
        $this->sessionKey = $sessionKey;
        $this->session = new PHPSession();
        }


    public function querySearch($value)
    {
        $query = $this->session->get($this->sessionKey, []);
        $query['querySearch'] = $value;
        $this->session->set($this->sessionKey, $query);

    }
    public function dateOn($value)
    {
        $dateOn = $this->session->get($this->sessionKey, []);
        $dateOn['dateOn'] = $value;
        $this->session->set($this->sessionKey, $dateOn);
    }
    public function dateEnd($value)
    {
        $dateEnd = $this->session->get($this->sessionKey, []);
        $dateEnd['dateEnd'] = $value;
        $this->session->set($this->sessionKey, $dateEnd);
    }
    public function nbLine($value)
    {
        $nbLine = $this->session->get($this->sessionKey, []);
        $nbLine['nbLine'] = $value;
        $this->session->set($this->sessionKey, $nbLine);
    }
    public function byStatut($value)
    {
        $byStatut = $this->session->get($this->sessionKey, []);
        $byStatut['byStatut'] = $value;
        $this->session->set($this->sessionKey, $byStatut);
    }
    public function byType($value)
    {
        $byType = $this->session->get($this->sessionKey, []);
        $byType['byType'] = $value;
        $this->session->set($this->sessionKey, $byType);
    }
    public function byPharma($value)
    {
        $byPharma = $this->session->get($this->sessionKey, []);
        $byPharma['byPharma'] = $value;
        $this->session->set($this->sessionKey, $byPharma);
    }
    public function currentPage($value)
    {
        $currentPage = $this->session->get($this->sessionKey, []);
        $currentPage['currentPage'] = $value;
        $this->session->set($this->sessionKey, $currentPage);
    }
    public function byColumn($value)
    {
        $byColumn = $this->session->get($this->sessionKey, []);
        $byColumn['byColumn'] = $value;
        $this->session->set($this->sessionKey, $byColumn);
    }
    public function byOrder($value)
    {
        $byOrder = $this->session->get($this->sessionKey, []);
        $byOrder['byOrder'] = $value;
        $this->session->set($this->sessionKey, $byOrder);
    }
    public function byAsc($value)
    {
        $byAsc = $this->session->get($this->sessionKey, []);
        $byAsc['byAsc'] = $value;
        $this->session->set($this->sessionKey, $byAsc);
    }
    public function byDesc($value)
    {
        $byDesc = $this->session->get($this->sessionKey, []);
        $byDesc['byDesc'] = $value;
        $this->session->set($this->sessionKey, $byDesc);
    }
    public function get(string $type): ?string
    {
        $data = $this->session->get($this->sessionKey, []);
        if (array_key_exists($type, $data)) {
            return $data[$type];
        }
        return null; // Si la clé n'existe pas      
    }
        public function delete(string $key): void
        {
            $data = $this->session->get($this->sessionKey, []);
            if (array_key_exists($key, $data)) {
                unset($data[$key]);
            }
            $this->session->set($this->sessionKey, $data);
        }
}