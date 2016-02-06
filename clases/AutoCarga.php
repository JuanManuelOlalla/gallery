<?php

class AutoCarga {
    static function cargar($clase){
        $archivo = "../../clases/" . str_replace('\\', '/', $clase) . ".php";
        if(file_exists($archivo)){
            require $archivo;
        }else {
            $archivo = "clases/" . $clase . ".php";
            require $archivo;
        }
}
}
spl_autoload_register('AutoCarga::cargar');