<?php

class QueryString {

    private $params;

    function __construct() {
        $this->params = $_GET;
    }

    function get($nombre) {
        return $this->params[$nombre];
    }

    function getParamsWithout($parametrosDelete) {
        return $this->getParams(array(), $parametrosDelete);
    }

    function getParams($parametrosAdd = array(), $parametrosDelete = array()) {
        $copia = $this->params;
        foreach ($parametrosDelete as $key => $valor) {
            unset($copia[$key]);
        }
        foreach ($parametrosAdd as $key => $valor) {
            $copia[$key] = $valor;
        }
        $r = "";
        foreach ($copia as $key => $value) {
            $r .= $key . "=" . urldecode($value) . "&";
        }
        return substr($r, 0, -1);
    }

    function set($nombre, $valor) {
        $this->params[$nombre] = $valor;
    }

    function delete($nombre) {
        unset($this->params[$nombre]);
    }

    function __toString() {
        $r = "";
        foreach ($this->params as $key => $valor) {
            $r .= "$key=$valor&";
        }
        return $r;
    }

}
