<?php
class User {
    
    private $email, $clave, $alias, $fechalta, $activo, $administrador, $personal, $avatar, $descripcion, $privado, $plantilla;
    
    function __construct($email=null, $clave=null, $alias=null, $fechalta=null, $activo=0, $administrador=0, $personal=0, $avatar=null, $descripcion=null, $privado=0, $plantilla=1) {
        $this->email = $email;
        $this->clave = $clave;
        $this->alias = $alias;
        $this->fechalta = $fechalta;
        $this->activo = $activo;
        $this->administrador = $administrador;
        $this->personal = $personal;
        $this->avatar = $avatar;
        $this->descripcion = $descripcion;
        $this->privado = $privado;
        $this->plantilla = $plantilla;
    }
    
    function getEmail() {
        return $this->email;
    }

    function getClave() {
        return $this->clave;
    }

    function getAlias() {
        return $this->alias;
    }

    function getFechalta() {
        return $this->fechalta;
    }

    function getActivo() {
        return $this->activo;
    }

    function getAdministrador() {
        return $this->administrador;
    }

    function getPersonal() {
        return $this->personal;
    }

    function getAvatar() {
        return $this->avatar;
    }

    function getDescripcion() {
        return $this->descripcion;
    }
    
    function getPrivado() {
        return $this->privado;
    }

    function getPlantilla() {
        return $this->plantilla;
    }
    
    function setEmail($email) {
        $this->email = $email;
    }

    function setClave($clave) {
        $this->clave = $clave;
    }

    function setAlias($alias) {
        $this->alias = $alias;
    }

    function setFechalta($fechalta) {
        $this->fechalta = $fechalta;
    }

    function setActivo($activo) {
        $this->activo = $activo;
    }

    function setAdministrador($administrador) {
        $this->administrador = $administrador;
    }

    function setPersonal($personal) {
        $this->personal = $personal;
    }
    
    function setAvatar($avatar) {
        $this->avatar = $avatar;
    }

    function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }
    
    function setPrivado($privado) {
        $this->privado = $privado;
    }

    function setPlantilla($plantilla) {
        $this->plantilla = $plantilla;
    }
    
    function getJson(){
        $cadena = '{';
        foreach ($this as $indice => $valor) {
            $cadena .= '"'.$indice.'":"'.$valor.'",';
        }
        $cadena = substr($cadena, 0, -1);
        $cadena .= '}';
        return $cadena;
    }
    
    function set($valores, $inicio = 0){ //Generico total
        $i = 0;
        foreach ($this as $indice => $valor) {
            $this->$indice = $valores[$i+$inicio];
            $i++;
        }
    }
    
    public function __toString(){
        $r = "";
        foreach ($this as $key => $valor) {
            $r .= "$valor ";
        }
        return $r;
    }
    
    function getArray($valores=true){
        $array = array();
        foreach ($this as $key => $valor) {
            if($valores === true){
                $array[$key] =  $valor;
            }else{
                $array[$key] =  null;
            }
        }
        return $array;
    }
    
    function read(){ //añadimos a cada propiedad de la clase ($this->$key) su valor si lo pasamos con el mismo nombre
        foreach ($this as $key => $valor) {
            $this->$key = Request::req($key);
        }
    }
}
