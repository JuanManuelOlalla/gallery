<?php

class Pager {

    private $registros, $rpp, $paginaActual; //Registros es la cantidad de rows rellenas

    function __construct($registros, $rpp = Constant::NRPP, $paginaActual = 1) {
        if($rpp===null){
            $rpp = Constant::NRPP;
        }
        if($paginaActual===null){
            $paginaActual = 1;
        }
        $this->registros = $registros;
        $this->rpp = $rpp;
        $this->paginaActual = $paginaActual;
    }

    function getRegistros() {
        return $this->registros;
    }

    function getRpp() {
        return $this->rpp;
    }

    function getPaginaActual() {
        return $this->paginaActual;
    }

    function getPrimera() {
        return 1;
    }
    
    function getUltima() {
        return ceil($this->registros / $this->rpp);
    }
    
    function getAnterior() {
        return $this->paginaActual - 1;
    }

    function getSelect($id, $name = null) {
        if($name===null){
            $name = $id;
        }
        $parametros = [10 => "10 rpp", 50 => "50 rpp", 100 => "100 rpp"];
        return Util::getSelect($name, $parametros, $this->rpp, false, "", $id);
    }

    function getSiguiente() {
        return max($this->paginaActual ,$this->paginaActual + 1);
    }

    function getPaginas() {
        return ceil($this->registros / $this->rpp);
    }

    function setRegistros($registros) {
        $this->registros = $registros;
    }

    function setRpp($rpp) {
        $this->rpp = $rpp;
    }

    function setPaginaActual($paginaActual) {
        $this->paginaActual = $paginaActual;
    }

    function getEnlacesPaginas($rango, $enlace, $nombreParametroPagina, $pagina = 0) {
        
    }

}
