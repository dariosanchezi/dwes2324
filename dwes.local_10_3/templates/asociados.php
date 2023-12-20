<?php
require_once __DIR__ . '/../src/utils/File.class.php';
require_once __DIR__ . '/../src/exceptions/FileException.class.php';
require_once __DIR__ . '/../src/entity/Asociado.class.php';

session_start();

If ($_SERVER['REQUEST_METHOD'] === 'POST' ) {
    try {
        $no = trim(htmlspecialchars($_POST['nombre'])) ?? "";
        if ( $no==""  ) {
            $mensaje="Debe poner un nombre";
            $errores=[]; $nombre=""; $descripcion=""; 
        } else {
            if ( isset($_POST['captcha']) && ($_POST['captcha']!="")){
                if( $_SESSION['captchaGenerado'] != $_POST['captcha'] ){
                    $mensaje = "¡Ha introducido un código de seguridad incorrecto! Inténtelo de nuevo.";
                    $errores=[]; $nombre=""; $descripcion=""; 
                }else{
                    $nombre = trim(htmlspecialchars($_POST['nombre'])) ?? "";
                    $descripcion = trim(htmlspecialchars($_POST['descripcion'])) ?? "";

                    $tiposAceptados = ['image/jpeg', 'image/gif', 'image/png'];
                    $logo = new File('logo', $tiposAceptados);  // El nombre 'imagen' es el que se ha puesto en el formulario de galeria.view.php
                    $logo->saveUploadFile(Asociado::RUTA_LOGOS_ASOCIADOS );

                    $mensaje = "Se ha guardado el asociado correctamente.";
                }
            } else {
                $mensaje = "Introduzca el código de seguridad.";
                $errores=[]; $nombre=""; $descripcion=""; 
            }
        }
    } catch (FileException $fileException) {
        $errores[] = $fileException->getMessage();
    }
}
else {
    $errores=[]; $nombre=""; $descripcion=""; $mensaje="";
}

require_once __DIR__ . '/views/asociados.view.php';


