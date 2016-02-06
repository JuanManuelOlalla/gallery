<?php

class Imagen {

    private $nombre, $ruta, $autor;

    function __construct($nombre = null, $ruta = null, $autor = null) {
        $this->nombre = $nombre;
        $this->ruta = $ruta;
        $this->autor = $autor;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getRuta() {
        return $this->ruta;
    }

    function getAutor() {
        return $this->autor;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setRuta($ruta) {
        $this->ruta = $ruta;
    }

    function setAutor($autor) {
        $this->autor = $autor;
    }

    function getJson() {
        $cadena = '{';
        foreach ($this as $indice => $valor) {
            $cadena .= '"' . $indice . '":"' . $valor . '",';
        }
        $cadena = substr($cadena, 0, -1);
        $cadena .= '}';
        return $cadena;
    }

    function set($valores, $inicio = 0) { //Generico total
        $i = 0;
        foreach ($this as $indice => $valor) {
            $this->$indice = $valores[$i + $inicio];
            $i++;
        }
    }

    public function __toString() {
        $r = "";
        foreach ($this as $key => $valor) {
            $r .= "$valor ";
        }
        return $r;
    }

    function getArray($valores = true) {
        $array = array();
        foreach ($this as $key => $valor) {
            if ($valores === true) {
                $array[$key] = $valor;
            } else {
                $array[$key] = null;
            }
        }
        return $array;
    }

    function read() { //añadimos a cada propiedad de la clase ($this->$key) su valor si lo pasamos con el mismo nombre
        foreach ($this as $key => $valor) {
            $this->$key = Request::req($key);
        }
    }

}
