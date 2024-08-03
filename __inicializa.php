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


$direcciones = new gamboamartin\direccion_postal\instalacion\instalacion();

$instala = $direcciones->instala(link: $link);
if(errores::$error){
    if($link->inTransaction()) {
        $link->rollBack();
    }
    $error = (new errores())->error(mensaje: 'Error al instalar direcciones', data: $instala);
    print_r($error);
    exit;
}

$cat_sat = new gamboamartin\cat_sat\instalacion\instalacion(link: $link);

$instala = $cat_sat->instala(link: $link);
if(errores::$error){
    if($link->inTransaction()) {
        $link->rollBack();
    }
    $error = (new errores())->error(mensaje: 'Error al instalar cat_sat', data: $instala);
    print_r($error);
    exit;
}

$organigrama = new \gamboamartin\organigrama\instalacion\instalacion();

$instala = $organigrama->instala(link: $link);
if(errores::$error){
    if($link->inTransaction()) {
        $link->rollBack();
    }
    $error = (new errores())->error(mensaje: 'Error al instalar organigrama', data: $instala);
    print_r($error);
    exit;
}


$comercial = new gamboamartin\comercial\instalacion\instalacion();

$instala = $comercial->instala(link: $link);
if(errores::$error){
    if($link->inTransaction()) {
        $link->rollBack();
    }
    $error = (new errores())->error(mensaje: 'Error al instalar comercial', data: $instala);
    print_r($error);
    exit;
}


$facturacion = new \gamboamartin\facturacion\instalacion\instalacion();

$instala = $facturacion->instala(link: $link);
if(errores::$error){
    if($link->inTransaction()) {
        $link->rollBack();
    }
    $error = (new errores())->error(mensaje: 'Error al instalar facturacion', data: $instala);
    print_r($error);
    exit;
}

$empleado = new \gamboamartin\empleado\instalacion\instalacion();

$instala = $empleado->instala(link: $link);
if(errores::$error){
    if($link->inTransaction()) {
        $link->rollBack();
    }
    $error = (new errores())->error(mensaje: 'Error al instalar $empleado', data: $instala);
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


