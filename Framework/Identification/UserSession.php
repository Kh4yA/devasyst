<?php

namespace App\Framework\Identification;

use App\Framework\Session\PHPSession;

class UserSession 
{
    private $session;
    private $sessionKey = 'user';

    public function __construct()
    {
        $this->session = new PHPSession();
    }

    public function mail(string $value){
        $email = $this->session->get($this->sessionKey, []);
        $email['mail'] = $value;
        $this->session->set($this->sessionKey, $email);
    }
    public function token(string $value){
        $token = $this->session->get($this->sessionKey, []);
        $token['token'] = $value;
        $this->session->set($this->sessionKey, $token);
    }
    public function csrf_token(string $value){
        $csrf_token = $this->session->get($this->sessionKey, []);
        $csrf_token['csrf_token'] = $value;
        $this->session->set($this->sessionKey, $csrf_token);
    }
    public function type(string $value){
        $type = $this->session->get($this->sessionKey, []);
        $type['type'] = $value;
        $this->session->set($this->sessionKey, $type);
    }
    public function get(string $key){
        $data = $this->session->get($this->sessionKey, []);
        if(array_key_exists($key, $data)){
            return $data[$key];
        }
        return null;
    }
}