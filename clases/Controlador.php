<?php

class Controlador {

    static function handle() {
        $action = Request::req("action");
        $target = Request::req("target");

        $metodo = $action . ucfirst($target);
        if (method_exists(get_class(), $metodo)) {
            self::$metodo();
        } else {
            self::viewLogin();
        }
    }

    /*     * ************************************ VIEWS ************************************* */

    private static function viewLogin($mensaje = "", $tipo = "") {
        $plantilla = new Plantilla('plantillas/login/_login.html');
        $plantilla->insertPlantilla('plantillas/login/_formulario.html', "formulario");
        $plantilla->alerts($mensaje, $tipo);
        $plantilla->mostrar();
    }

    private static function viewIndex($mensaje = "", $tipo = "") {
        $sesion = self::autentificado();
        $user = $sesion->getUser();
        $nombre = $user->getAlias();
        $plantilla = new Plantilla('plantillas/index/Theme/index.html');
        $plantilla->insertUserTags($user);
        $plantilla->alerts($mensaje, $tipo);
        $plantilla->insertTag("main", "<h3><i class='fa fa-angle-right'></i> Bienvenido $nombre</h3><hr>");
        $plantilla->mostrar();
    }

    private static function viewEdit($mensaje = "", $tipo = "") {
        $sesion = self::autentificado();
        $user = $sesion->getUser();
        $plantilla = new Plantilla('plantillas/index/Theme/index.html');
        $plantilla->insertPlantilla('plantillas/index/user/_edit.html', "main");
        $plantilla->insertUserTags($user);
        $plantilla->alerts($mensaje, $tipo);
        $plantilla->mostrar();
    }

    private static function viewAddimagen($mensaje = "", $tipo = "") {
        $sesion = self::autentificado();
        $user = $sesion->getUser();
        $plantilla = new Plantilla('plantillas/index/Theme/index.html');
        $plantilla->insertPlantilla('plantillas/index/imagen/_add.html', "main");
        $plantilla->insertUserTags($user);
        $plantilla->alerts($mensaje, $tipo);
        $plantilla->mostrar();
    }

    private static function viewImagen($mensaje = "", $tipo = "") {
        $sesion = self::autentificado();
        $user = $sesion->getUser();
        $plantilla = new Plantilla('plantillas/index/Theme/index.html');
        $plantilla->insertPlantilla('plantillas/index/imagen/_view.html', "main");
        $plantilla->insertTag("botones", "");
        $plantilla->insertUserTags($user);
        if(Request::req("borrar")){
            $mensaje = "Imagen borrada";
            $tipo = "success";
        }
        $plantilla->alerts($mensaje, $tipo);

        $bd = new DataBase();
        $gestor = new ManageImagen($bd);
        $images = $gestor->getImagenes($user);
        if(count($images) == 0){
            $plantilla->insertTag("imagenes", "No hay imagenes");
        }else{
            $plantilla->insertImagenes($images);
        }
        $plantilla->mostrar();
    }
    
    private static function viewImagenadmin($mensaje = "", $tipo = "", $email = null) {
        $bd = new DataBase();
        $sesion = new Session();
        $sesion->administrador($sesion->getUser());
        $gestor = new ManageUser($bd);
        if($email != null){
            $user = $gestor->get($email);
        }else{
            $email = Request::get("email");
            $user = $gestor->get(Request::get("email"));
        }
        $plantilla = new Plantilla('plantillas/index/Theme/index.html');
        $plantilla->insertPlantilla('plantillas/index/imagen/_view.html', "main");
        if(Request::req("borrar")){
            $mensaje = "Imagen borrada";
            $tipo = "success";
        }
        $plantilla->alerts($mensaje, $tipo);

        $gestorImg = new ManageImagen($bd);
        $images = $gestorImg->getImagenes($user);
        if(count($images) == 0){
            $plantilla->insertTag("imagenes", "No hay imagenes");
        }else{
            $plantilla->insertImagenes($images, $email);
        }
        $plantilla->getBotones();
        $plantilla->insertUserTagsAdmin($user, $sesion->getUser());
        $plantilla->mostrar();
    }
    
    private static function viewTemas($mensaje = "", $tipo = "") {
        $sesion = self::autentificado();
        $user = $sesion->getUser();
        $plantilla = new Plantilla('plantillas/index/Theme/index.html');
        $plantilla->insertPlantilla('plantillas/index/imagen/_temas.html', "main");
        $plantilla->insertUserTags($user);
        $plantilla->alerts($mensaje, $tipo);
        $plantilla->insertTemas($user);
        $plantilla->mostrar();
    }
    
    private static function viewUser() {
        $email = Request::get("user");
        $bd = new DataBase();
        $gestor = new ManageUser($bd);
        $user = $gestor->get($email);
        
        if($user->getPrivado()==1){
            $sesion = new Session();
            if($sesion->getUser()->getEmail() != $email && $sesion->getUser()->getAdministrador() != 1){
                self::viewLogin("Esta galería es privada", "danger");
                exit();
            }
        }
        
        $tema = $user->getPlantilla();
        $plantilla = new Plantilla("plantillas/index/Theme/_tema$tema.html");
        $plantilla->insertTemaTags($user);
        $plantilla->mostrar();
        
    }
    
    private static function viewUsuarios($mensaje = "", $tipo = "") {
        $sesion = new Session();
        $sesion->autentificado();
        $user = $sesion->getUser();
        $bd = new DataBase();
        $gestor = new ManageUser($bd);
        $usuarios = $gestor->getList("privado!=1 and activo=1");
        
        $plantilla = new Plantilla('plantillas/index/Theme/index.html');
        $plantilla->insertPlantilla('plantillas/index/user/_view.html', "main");
        $plantilla->insertUserTags($user);
        $plantilla->alerts($mensaje, $tipo);
        $plantilla->insertUsuarios($usuarios);
        $plantilla->mostrar();
    }
    private static function viewAdmin($mensaje = "", $tipo = "") {
        $sesion = new Session();
        $sesion->autentificado();
        $user = $sesion->getUser();
        $bd = new DataBase();
        $gestor = new ManageUser($bd);
        $usuarios = $gestor->getList();
        
        $plantilla = new Plantilla('plantillas/index/Theme/index.html');
        $plantilla->insertPlantilla('plantillas/index/user/_view.html', "main");
        $plantilla->insertUserTags($user);
        $plantilla->alerts($mensaje, $tipo);
        $plantilla->insertUsuariosAdmin($usuarios);
        $plantilla->mostrar();
    }
    
    private static function viewEditadmin($mensaje = "", $tipo = "", $email = null) {
        $sesion = new Session();
        $bd = new DataBase();
        $admin = $sesion->getUser();
        $sesion->administrador($admin);
        if($email == null){
            $email = Request::get("user");
        }
        $gestor = new ManageUser($bd);
        $user = $gestor->get($email);
        $plantilla = new Plantilla('plantillas/index/Theme/index.html');
        $plantilla->insertPlantilla('plantillas/index/user/_editadmin.html', "main");
        $plantilla->alerts($mensaje, $tipo);
        $plantilla->insertUserTagsAdmin($user, $admin);
        $plantilla->mostrar();
    }
    
    /*     * ************************************ CAMBIAR TEMA ************************************* */
    
    private static function editTema() {
        $sesion = new Session();
        $sesion->autentificado();
        $user = $sesion->getUser();
        $user->setPlantilla(Request::get("s"));
        $bd = new DataBase();
        $gestor = new ManageUser($bd);
        $r = $gestor->setPlantilla($user);
        if($r==1){
            self::viewTemas("Tema cambiado", "success");
        }else{
            self::viewTemas("No se ha cambiado el tema", "danger");
        }
    }
    

    /*     * ************************************ SUBIR IMAGEN ************************************* */

    private static function subirImagen() {
        //subir las imagenes
        $sesion = new Session();
        $sesion->autentificado();
        $user = $sesion->getUser();
        $email = $user->getEmail();
        $subir = new FileUpload("inputdim1", $email);
        $destino = "plantillas/gallery/images/";
        $subir->setDestino($destino);
        $subir->setMaximo(999999999999);
        $imagenes = $subir->subida();

        //guardar imagenes en la bd
        $bd = new DataBase();
        $gestor = new ManageImagen($bd);
        for ($i = 0; $i < count($imagenes); $i++) {
            $nombre = $imagenes[$i];
            $imagen = new Imagen($nombre, $destino . $nombre, $email);
            $gestor->insert($imagen);
        }
    }
    
    /*     * ************************************ BORRAR IMAGEN ************************************* */

    private static function deleteImagen() {
        $sesion = new Session();
        $sesion->autentificado();
        $bd = new DataBase();
        $gestor = new ManageImagen($bd);
        $nombre = Request::get("nombre");
        $r = $gestor->delete($nombre);
        $ruta = Request::get("ruta");
        $email = Request::get("email");
        
        if($r = 1){
            unlink($ruta);
            if($email != null){
                header("Location: index.php?action=view&target=imagenadmin&email=$email");
            }else{
                header("Location: index.php?action=view&target=imagen&borrar=true");
                //self::viewImagen("Imagen borrada", "success");
            }
            exit();
        }else{
            if($email != null){
                self::viewImagenadmin("La imagen no se ha podido borrar", "danger", $email);
            }else{
                self::viewImagen("La imagen no se ha podido borrar", "danger");
            }
            exit();
        }
        
    }
    
    /*     * ************************************ EDITAR USUARIO ************************************* */

    private static function editUser() {

        $sesion = new Session();
        $sesion->autentificado();
        $bd = new DataBase();
        $gestor = new ManageUser($bd);
        $user = $sesion->getUser();

        $nuevoUsuario = new User();
        $nuevoUsuario->read();

        $nuevoUsuario->setActivo(1);
        $nuevoUsuario->setAdministrador($user->getAdministrador());
        $nuevoUsuario->setFechalta($user->getFechalta());
        $nuevoUsuario->setAvatar($user->getAvatar());
        $nuevoUsuario->setPlantilla($user->getPlantilla());

        $privado = isset($_POST['privado']) && Request::req("privado") ? "1" : "0";
        $nuevoUsuario->setPrivado($privado);

        $clave = Request::post("clave");
        $claveNueva = Request::post("claveNueva");
        $claveConfirmada = Request::post("claveConfirmada");
        $cambioDeClave = false;
        if ($claveNueva == $claveConfirmada) {
            if($claveNueva != ""){
                $cambioDeClave = strlen($claveNueva) > 0;
            }
        } else {
            $bd->closeConnection();
            self::viewEdit("Las contraseñas no coinciden", "danger");
            exit();
        }
        $cambioDeCorreo = $nuevoUsuario->getEmail() != $user->getEmail();

        if ($cambioDeClave) {
            $nuevoUsuario->setClave(sha1($claveNueva));
            if (sha1($clave) == $user->getClave()) {
                $r = $gestor->set($nuevoUsuario, $user->getEmail());
            } else {
                $bd->closeConnection();
                self::viewEdit("Las contraseña actual no es la misma", "danger");
                exit();
            }
        } else {
            $r = $gestor->setSin($nuevoUsuario, $user->getEmail());
            $nuevoUsuario->setClave($user->getClave());
        }

        if ($cambioDeCorreo && $r > 0) {
            $r = $gestor->desactivar($nuevoUsuario->getEmail());
            $id = md5(Constant::PEZARANA . $nuevoUsuario->getEmail());
            $direccion = Server::getEnlaceCarpeta("index.php?id=$id&email=" . $nuevoUsuario->getEmail() . "&action=activar&target=user");
            $direccion = "<a href='$direccion'>Has cambiado el email, necesitas reactivar la cuenta: " . $nuevoUsuario->getEmail() . "</a>";
            self::viewLogin($direccion, "info");
            $sesion->destroy();
            $bd->closeConnection();
            exit;
        }
        if($r==1){
            $sesion->setUser($nuevoUsuario);
            self::viewEdit("Se ha editado el perfil correctamente", "success");
        }else{
            self::viewEdit("Error al editar", "danger");
        }

        $bd->closeConnection();
    }
    
    private static function editUseradmin() {

        $sesion = new Session();
        $sesion->administrador();
        $bd = new DataBase();
        $gestor = new ManageUser($bd);
        $user = $gestor->get(Request::get("user"));

        $nuevoUsuario = new User();
        $nuevoUsuario->read();

        $privado = isset($_POST['privado']) && Request::req("privado") ? "1" : "0";
        $nuevoUsuario->setPrivado($privado);
        $administrador = isset($_POST['administrador']) && Request::req("administrador") ? "1" : "0";
        $nuevoUsuario->setAdministrador($administrador);
        
        $nuevoUsuario->setFechalta($user->getFechalta());
        $nuevoUsuario->setAvatar($user->getAvatar());
        
        $claveNueva = Request::post("claveNueva");
        $cambioDeClave = strlen($claveNueva) > 0;
        
        $cambioDeCorreo = $nuevoUsuario->getEmail() != $user->getEmail();

        if ($cambioDeClave) {
            $nuevoUsuario->setClave(sha1($claveNueva));
            $r = $gestor->set($nuevoUsuario, $user->getEmail());
        } else {
            $r = $gestor->setSin($nuevoUsuario, $user->getEmail());
        }
        self::viewEditadmin("Editado {email} correctamente", "success", $nuevoUsuario->getEmail());
        $bd->closeConnection();
    }
    
    
    private static function editAvatar() {
        $sesion = new Session();
        $sesion->autentificado();
        $bd = new DataBase();
        $gestor = new ManageUser($bd);
        $user = $sesion->getUser();
        $subir = new SimpleUpload("avatar", $user->getEmail());
        $cambioAvatar = $_FILES["avatar"]['name'];
        if ($cambioAvatar != "" || $cambioAvatar != null) {
            $destino = "plantillas/index/user/avatar/" . $user->getEmail();
            $subir->setDestino($destino);
            $subir->subida();
            $user->setAvatar($user->getEmail() . "." . $subir->getExtension());
            $gestor->setAvatar($user);
            self::viewEdit("Avatar cambiado correctamente", "success");
        } else {
            self::viewEdit("Error al cambiar de avatar", "danger");
        }
    }

    private static function editAvataradmin() {
        $sesion = new Session();
        $sesion->administrador();
        $bd = new DataBase();
        $gestor = new ManageUser($bd);
        $user = $gestor->get(Request::get("user"));
        $subir = new SimpleUpload("avatar", $user->getEmail());
        $cambioAvatar = $_FILES["avatar"]['name'];
        if ($cambioAvatar != "" || $cambioAvatar != null) {
            $destino = "plantillas/index/user/avatar/" . $user->getEmail();
            $subir->setDestino($destino);
            $subir->subida();
            $user->setAvatar($user->getEmail() . "." . $subir->getExtension());
            $gestor->setAvatar($user);
            self::viewEditadmin("Avatar cambiado correctamente", "success", $user->getEmail());
        } else {
            self::viewEditadmin("Error al cambiar de avatar", "danger", $user->getEmail());
        }
    }

    private static function bajaUser() {
        $sesion = new Session();
        $sesion->autentificado();
        $bd = new DataBase();
        $gestor = new ManageUser($bd);
        $user = $sesion->getUser();

        $r = $gestor->desactivar($user->getEmail());
        $sesion->destroy();

        if ($r == 1) {
            $id = md5(Constant::PEZARANA . $user->getEmail());
            $email = $user->getEmail();
            $direccion = Server::getEnlaceCarpeta("index.php?id=$id&email=$email&action=activar&target=user");
            $direccion = "<a href='$direccion'>Has dado de baja tu cuenta: $email, para recuperarla revisa tu correo</a>";
            self::viewLogin($direccion, "info");
        } else {
            self::viewLogin("No se pudo crear el usuario, prueba con otro email", "danger");
        }
    }

    /*     * ************************************ NUEVO USUARIO ************************************* */

    private static function insertUser() {
        $bd = new DataBase();
        $gestor = new ManageUser($bd);
        $user = new User();

        $user->read();
        $clave2 = Request::req("clave2");
        $fechalta = date("Y-m-d H:i:s");
        $user->setFechalta($fechalta);
        $user->setActivo(0);
        $user->setAvatar("default-avatar.jpg");
        $user->setPlantilla(1);

        if ($user->getClave() === $clave2) {
            $r = $gestor->insert($user);
            if ($r == 1) {
                $id = md5(Constant::PEZARANA . $user->getEmail());
                $email = $user->getEmail();
                $direccion = Server::getEnlaceCarpeta("index.php?id=$id&email=$email&action=activar&target=user");
                $direccion = "<a href='$direccion'>Activar: $email</a>";
                self::viewLogin($direccion, "info");
            } else {
                self::viewLogin("No se pudo crear el usuario, prueba con otro email", "danger");
            }
        } else {
            self::viewLogin("Las claves no coinciden", "danger");
        }
        $bd->closeConnection();
    }

    /*     * ************************************ ACTIVAR USUARIO ************************************* */

    private static function activarUser() {
        $sesion = new Session();
        $bd = new DataBase();
        $gestor = new ManageUser($bd);
        $id = Request::req("id");
        $email = Request::req("email");

        if (md5(Constant::PEZARANA . $email) == $id) {
            $r = $gestor->activar($email);
            if ($r == 1) {
                $sesion->login($gestor->get($email));
            } else {
                self::viewLogin("No se ha activado 2", "danger");
            }
            self::viewIndex("Usuario Activado", "success");
        } else {
            self::viewLogin("No se ha activado", "danger");
        }
        $bd->closeConnection();
    }

    /*     * ************************************ LOGIN Y LOGOUT ************************************* */

    private static function loginUser() {
        $bd = new DataBase();
        $gestor = new ManageUser($bd);

        $email = Request::req("email");
        $clave = Request::req("clave");

        $user = $gestor->login($email, $clave);
        $bd->closeConnection();

        if ($user == false) {
            self::viewLogin("Login incorrecto o usuario inactivo", "danger");
        } else {
            $sesion = new Session();
            $sesion->login($user);
            self::viewIndex();
        }
    }

    private static function logoutUser() {
        $sesion = new Session();
        $sesion->destroy();
        self::viewLogin("Se ha desconectado", "info");
    }

    /*     * ************************************ AUTENTIFICADO & ACTIVO ************************************* */

    private static function autentificado() {
        $sesion = new Session();
        if (!$sesion->isAutentificado()) {
            $sesion->destroy();
            self::viewLogin("No tiene permisos", "danger");
            exit();
        } elseif ($sesion->getUser()->getActivo() != 1) {
            $sesion->destroy();
            self::viewLogin("El usuario no esta activo", "danger");
            exit();
        }
        return $sesion;
    }

    /*     * ************************************ RECUPERAR CONTRASEÑA ************************************* */

    private static function recoverUser() {
        $email = Request::req("email");
        $bd = new DataBase();
        $gestor = new ManageUser($bd);
        $user = $gestor->get($email);

        if ($user->getEmail() == $email && $user->getActivo() == 1) {
            $id = md5(Constant::PEZARANA . $email);
            $direccion = Server::getEnlaceCarpeta("index.php?id=$id&email=$email&action=view&target=cambiarclave");
            $direccion = "<a href='$direccion'>Recuperar contraseña de: $email</a>";
            self::viewLogin($direccion, "info");
        } else {
            self::viewLogin("Email incorrecto o usuario inactivo", "danger");
        }
    }

    private static function viewCambiarclave($mensaje = "", $tipo = "") {
        $plantilla = new Plantilla('plantillas/login/_cambiarclave.html');
        $datos = array(
            "id" => Request::req("id"),
            "email" => Request::req("email")
        );
        $plantilla->insertTags($datos);
        $plantilla->alerts($mensaje, $tipo);
        $plantilla->mostrar();
    }

    private static function cambiarClave() {
        $sesion = new Session();
        $bd = new DataBase();
        $gestor = new ManageUser($bd);
        $claveNueva = Request::req("claveNueva");
        $claveConfirmada = Request::req("claveConfirmada");
        $id = Request::req("id");
        $email = Request::req("email");

        if ($claveNueva != $claveConfirmada) {
            self::viewCambiarclave("Las contraseñas no coinciden", "danger");
            exit();
        }

        if (md5(Constant::PEZARANA . $email) == $id) {
            $user = $gestor->get($email);
            $user->setClave(sha1($claveNueva));
            $r = $gestor->set($user, $email);

            if ($r == 1) {
                $sesion->login($gestor->get($email));
                self::viewIndex("Contraseña cambiada", "success");
            } else {
                self::viewCambiarclave("Problemas al cambiar la clave", "danger");
            }
        } else {
            self::viewCambiarclave("ID incorrecto", "danger");
        }
    }

}
