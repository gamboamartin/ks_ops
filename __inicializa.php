<?php

use base\conexion;
use gamboamartin\errores\errores;
use gamboamartin\ks_ops\instalacion\instalacion;

$_SESSION['usuario_id'] = 2;

require "init.php";
require 'vendor/autoload.php';

$con = new conexion();
$link = conexion::$link;

$link->beginTransaction();
$administrador = new gamboamartin\administrador\instalacion\instalacion();

$instala = $administrador->instala(link: $link);
if(errores::$error){
    if($link->inTransaction()) {
        $link->rollBack();
    }
    $error = (new errores())->error(mensaje: 'Error al instalar administrador', data: $instala);
    print_r($error);
    exit;
}


$ks_ops = new instalacion();

$instala = $ks_ops->instala(link: $link);
if(errores::$error){
    if($link->inTransaction()) {
        $link->rollBack();
    }
    $error = (new errores())->error(mensaje: 'Error al instalar instala', data: $instala);
    print_r($error);
    exit;
}



if($link->inTransaction()) {
    $link->commit();
}


