<?php

class FileUpload {

    private $input;
    private $files;
    private $destino;
    private $email;
    private $maximo;
    private $extension;
    private $tipo;
    private $error_php;
    private $arrayDeImagenes = array(
        "jpg" => 1,
        "gif" => 1,
        "png" => 1,
        "jpeg" => 1,
    );

    function __construct($imagen, $email) {
        $this->input = $imagen;
        $this->email = $email;
        $this->tipo = array();
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

    function getExtension() {
        return $this->extension;
    }

    function getTipo() {
        return $this->tipo;
    }

    function getError_php() {
        return $this->error_php;
    }

    function getArrayDeImagenes() {
        return $this->arrayDeImagenes;
    }

    function setInput($input) {
        $this->input = $input;
    }

    function setFiles($files) {
        $this->files = $files;
    }
    
    function setExtension($extension) {
        $this->extension = $extension;
    }

    function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    function setError_php($error_php) {
        $this->error_php = $error_php;
    }

    function setArrayDeImagenes($arrayDeImagenes) {
        $this->arrayDeImagenes = $arrayDeImagenes;
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

    public function getMensajeError() {
        return $this->error_php;
    }

    ////////////////////////////////////////////
    
    
    public function addExtension($param) {
        if (is_array($param)) {
            $this->extension = array_merge($this->extension, $param);
        } else {
            $this->extension[] = $param;
        }
    }

    public function addTipo($tipo) {
        if (!$this->isTipo($tipo)) {
            $this->arrayDeTipos[$tipo] = 1;
            return true;
        }
        return false;
    }

    public function isTipo($tipo) {
        return isset($this->arrayDeTipos[$tipo]);
    }

    public function removeTipo($tipo) {
        if ($this->isTipo($tipo)) {
            unset($this->arrayDeTipos[$tipo]);
            return true;
        }
        return false;
    }

    public function isImagen($tipo) {
        return isset($this->arrayDeImagenes[$tipo]);
    }

    private function isInput() {
        if (!isset($_FILES[$this->input])) {
            $this->error_php = "NO existe el campo";
            return false;
        }
        return true;
    }

    private function isError() {
        if ($this->files["error"] != UPLOAD_ERR_OK) {
            return true;
        }
        return false;
    }

    private function isTamano($param) {
        if ($param > $this->maximo) {
            $this->error_php = "sobre pasa tamaño";
            return false;
        }
        return true;
    }

    private function isExtension($param) {
        if (sizeof($this->extension) > 0 &&
                !in_array($param, $this->extension)) {
            $this->error_php = "extension no valida";
            return false;
        }
        return true;
    }

    private function isCarpeta() {
        if (!file_exists($this->destino) && !is_dir($this->destino)) {
            return false;
        }
        return true;
    }

    private function crearCarpeta() {
        return mkdir($this->destino, 7777, true);
    }

    public function subida() {
        
        if (!$this->isCarpeta()) {
            $this->crearCarpeta();
        }

        $imagenes = array();
        
        $numb ="";
        foreach ($_FILES[$this->input]["error"] as $key => $error) {
            
            if (true) {
                
                $this->files = $_FILES[$this->input];
                $this->errorPHP = $this->files["error"];

                $partes = pathinfo($this->files["name"][$key]);
                $extension = $partes['extension'];
                $nombre = $partes['filename'];

                if (!$this->isImagen($extension) || !$this->isTamano($this->files["size"][$key])) {
                    $flag = false;
                }else{
                    $flag = true;
                }

                $destino = $this->destino . $this->email . "." . $extension;
                for ($i=1; file_exists($destino); $i++) {
                    $destino = $this->destino . $this->email . "_$i." . $extension;
                    $numb=$i;
                }

                if($flag !== false){
                    if($numb!=""){
                        array_push($imagenes, $this->email . "_$numb." . $extension);
                    }else{
                        array_push($imagenes, $this->email . "." . $extension);
                    }
                    move_uploaded_file($_FILES[$this->input]["tmp_name"][$key], $destino);
                }
            }
        }
        return $imagenes;
    }

}
