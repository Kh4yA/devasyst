<?php

namespace App\Framework\Service;

use App\Framework\Session\PHPSession;

class Flash {

    private object $session;
    private string $sessionKey = 'flash';
    private $message;

    public function __construct(){
        $this->session = new PHPSession();
        }
        /**
         * Enregistre un message succes
         * @param string $message
         * @return void
         */
        public function success(string $message){
            $flash = $this->session->get($this->sessionKey, []);
            $flash['success'] = $message;
            $this->session->set($this->sessionKey, $flash);
        }
        /**
         * Enregistre le message d'erreur
         * @param string $message
         * @return void
         */
        public function error(string $message){
            $flash = $this->session->get($this->sessionKey, []);
            $flash['error'] = $message;
            $this->session->set($this->sessionKey, $flash);
        }
        /**
         * Enrgiste un message warning
         * @param string $message
         * @return void
         */
        public function warning(string $message){
            $flash = $this->session->get($this->sessionKey, []);
            $flash['warning'] = $message;
            $this->session->set($this->sessionKey, $flash);
        }
        /**
         * Undocumented function
         *
         * @param string $type
         * @return string|null
         */
        public function get(string $type): ?string
        {
            if(is_null($this->message)){
                $this->message = $this->session->get($this->sessionKey, []);
                $this->session->delete($this->sessionKey);
            }
            if (array_key_exists($type, $this->message)) {
                return $this->message[$type];
            }
            return null;
        }
}