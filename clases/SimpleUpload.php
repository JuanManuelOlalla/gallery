<?php

class SimpleUpload {

    private $input;
    private $files;
    private $destino;
    private $email;
    private $maximo;
    private $error_php;
    private $extensiones = array(
        "jpg" => 1,
        "gif" => 1,
        "png" => 1,
        "jpeg" => 1,
    );
    private $extension;

    function __construct($imagen, $email) {
        $this->input = $imagen;
        $this->email = $email;
        $this->extension = array();
        $this->error_php = UPLOAD_ERR_OK;
        $this->files = $_FILES[$imagen];
    }

    function getInput() {
        return $this->input;
    }

    function getFiles() {
        return $this->files;
    }

    function getError_php() {
        return $this->error_php;
    }

    function setInput($input) {
        $this->input = $input;
    }

    function setFiles($files) {
        $this->files = $files;
    }

    function setError_php($error_php) {
        $this->error_php = $error_php;
    }
        
    public function getErrorPHP() {
        return $this->errorPHP;
    }

    function setDestino($destino) {
        $this->destino = $destino;
    }

    public function getDestino() {
        return $this->destino;
    }

    public function setEmail($param) {
        $this->email = $param;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setMaximo($maximo) {
        $this->maximo = $maximo;
    }
    
    public function getExtension(){
        return $this->extension;
        
    }

    public function subida() {
        $this->files = $_FILES[$this->input];
        $this->errorPHP = $this->files["error"];

        $partes = pathinfo($this->files["name"]);
        $this->extension = $partes['extension'];
        
        move_uploaded_file($_FILES[$this->input]["tmp_name"], $this->destino.".".$this->extension);
    }

}
