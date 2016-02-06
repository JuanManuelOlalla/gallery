<?php

class ManageImagen {
    
    private $bd = null;
    private $tabla = "imagen";
    
    function __construct(DataBase $bd) {
        $this->bd = $bd;
    }

    function get($nombre){
        $parametros = array();
        $parametros["nombre"] = $nombre;
        $this->bd->select($this->tabla, "*", "nombre = :nombre", $parametros);
        $fila = $this->bd->getRow();
        $imagen = new Imagen();
        $imagen->set($fila);
        return $imagen;
    }
    
    function delete($nombre){
        //devolver filas borradas
        $parametros = array();
        $parametros["nombre"] = $nombre;
        return $this->bd->delete($this->tabla, $parametros);
    }
    
    function erase(Imagen $imagen){
        return $this->delete($imagen->getNombre());
    }
    
    function insert(Imagen $imagen){
        $parametros = $imagen->getArray();
        return $this->bd->insert($this->tabla, $parametros, false);
    }
    
    function getList() {
        $this->bd->select($this->tabla, "*", "1=1", array(), "nombre, autor");
        $r = array();
        while($fila = $this->bd->getRow()){
            $imagen = new Imagen();
            $imagen->set($fila);
            $r[] = $imagen;
        }
        return $r;
    }
    
    function getImagenes(User $user){
        $autor = $user->getEmail();
        $this->bd->select($this->tabla, "*", "autor='$autor'", array(), "nombre, autor");
        $r = array();
        while($fila = $this->bd->getRow()){
            $imagen = new Imagen();
            $imagen->set($fila);
            $r[] = $imagen;
        }
        return $r;
    }
    
    function getRutas(User $user){
        $autor = $user->getEmail();
        $this->bd->select($this->tabla, "*", "autor='$autor'", array(), "nombre, autor");
        $r = array();
        while($fila = $this->bd->getRow()){
            $imagen = new Imagen();
            $imagen->set($fila);
            $r[] = $imagen->getRuta();
        }
        return $r;
    }
    
}
