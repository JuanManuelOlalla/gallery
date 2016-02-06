<?php

class Session {

    private static $iniciada = false;

    //private $trusted = true;
    // IP del cliente, el navegador
    function __construct() {
        if (!self::$iniciada) {
            session_start();
            //$this->_control();
        }
        self::$iniciada = true;
    }
    
    private function _control() {
        $ip = $this->get("_ip");
        $cliente = $this->get("_cliente");
        if ($ip == null && $cliente == null) {
            $this->set("_ip", Server::getCLientAdress());
            $this->set("_cliente", Server::getUserAgent());
        } else {
            if ($ip != Server::getClientAdress() || $cliente != Server::getUserAgent()) {
                $this->destroy();
                //$this->trusted = false;
            }
        }
    }

    function isLogged() {
        return $this->getUser() != null;
    }

    function get($nombre) {
        if (isset($_SESSION[$nombre])) {
            return $_SESSION[$nombre];
        }
        return null;
    }

    function getUser() {
        return $this->get("_user");
    }

    function set($nombre, $valor) {
        $_SESSION[$nombre] = $valor;
    }

    function setUser(User $user) {
        $this->set("_user", $user);
    }

    function delete($nombre) {
        if (isset($_SESSION[$nombre])) {
            unset($_SESSION[$nombre]);
        }
    }

    function destroy() {
        session_destroy();
    }

    function sendRedirect($destino = "index.php", $final = true) {
        header("Location:$destino");
        if($final === true){
            exit();
        }
    }
    
    function isAutentificado() {
        return isset($_SESSION["_user"]);
    }
    
    function autentificado($destino = "index.php") {
        if (!$this->isAutentificado()) {
            $this->sendRedirect($destino);
        }
        if($this->getUser()->getActivo()!=1){
            $this->sendRedirect($destino."?error=Desactivado");
        }
    }
    
    function administrador($user = 1) {
        if($user=1){
            $user = $this->getUser();
        }
        if (!$this->isAutentificado() && $user->getAdministrador()!=1) {
            $this->destroy();
        }
    }
    
    function login(User $user){
        $this->setUser($user);
        /*if($user->getAdministrador()==1){
            header("Location:viewadmin.php");
        }elseif($user->getPersonal()==1){
            header("Location:viewpersonal.php");
        }else{
            header("Location:viewuser.php");
        }*/
    }

}