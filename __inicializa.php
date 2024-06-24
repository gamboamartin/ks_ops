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

$proceso = new \gamboamartin\proceso\instalacion\instalacion();

$instala = $proceso->instala(link: $link);
if(errores::$error){
    if($link->inTransaction()) {
        $link->rollBack();
    }
    $error = (new errores())->error(mensaje: 'Error al instalar proceso', data: $instala);
    print_r($error);
    exit;
}

$documento = new \gamboamartin\documento\instalacion\instalacion();

$instala = $documento->instala(link: $link);
if(errores::$error){
    if($link->inTransaction()) {
        $link->rollBack();
    }
    $error = (new errores())->error(mensaje: 'Error al instalar documento', data: $instala);
    print_r($error);
    exit;
}


$notificaciones = new gamboamartin\notificaciones\instalacion\instalacion();

$instala = $notificaciones->instala(link: $link);
if(errores::$error){
    if($link->inTransaction()) {
        $link->rollBack();
    }
    $error = (new errores())->error(mensaje: 'Error al instalar notificaciones', data: $instala);
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


