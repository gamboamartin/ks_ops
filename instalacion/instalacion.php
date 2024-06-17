<?php
namespace gamboamartin\ks_ops\instalacion;


use gamboamartin\administrador\instalacion\_adm;
use gamboamartin\administrador\models\adm_accion;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class instalacion
{


    private function adm_grupo(PDO $link): array|stdClass
    {


        $adm_menu_descripcion = 'ACL';
        $adm_sistema_descripcion = 'ks_ops';
        $etiqueta_label = 'Grupos';
        $adm_seccion_pertenece_descripcion = __FUNCTION__;
        $adm_namespace_name = 'gamboamartin/ks_ops';
        $adm_namespace_descripcion = 'gamboa.martin/ks_ops';

        $acl = (new _adm())->integra_acl(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_name: $adm_namespace_name, adm_namespace_descripcion: $adm_namespace_descripcion,
            adm_seccion_descripcion: __FUNCTION__,
            adm_seccion_pertenece_descripcion: $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion: $adm_sistema_descripcion,
            etiqueta_label: $etiqueta_label, link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acl', data:  $acl);
        }

        $adm_accion_modelo = (new adm_accion(link: $link));

        $adm_acciones_basicas = $adm_accion_modelo->inserta_acciones_basicas(adm_seccion: __FUNCTION__);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acciones basicas', data:  $adm_acciones_basicas);
        }


        return $acl;

    }

    private function adm_seccion(PDO $link): array|stdClass
    {

        $adm_menu_descripcion = 'ACL';
        $adm_sistema_descripcion = 'ks_ops';
        $etiqueta_label = 'Usuarios';
        $adm_seccion_pertenece_descripcion = __FUNCTION__;
        $adm_namespace_name = 'gamboamartin/ks_ops';
        $adm_namespace_descripcion = 'gamboa.martin/ks_ops';

        $acl = (new _adm())->integra_acl(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_name: $adm_namespace_name, adm_namespace_descripcion: $adm_namespace_descripcion,
            adm_seccion_descripcion: __FUNCTION__,
            adm_seccion_pertenece_descripcion: $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion: $adm_sistema_descripcion,
            etiqueta_label: $etiqueta_label, link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acl', data:  $acl);
        }

        $adm_accion_modelo = (new adm_accion(link: $link));


        $adm_acciones_basicas = $adm_accion_modelo->inserta_acciones_basicas(adm_seccion: __FUNCTION__);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acciones basicas', data:  $adm_acciones_basicas);
        }

        return $acl;

    }

    private function adm_usuario(PDO $link): array|stdClass
    {

        $adm_menu_descripcion = 'ACL';
        $adm_sistema_descripcion = 'ks_ops';
        $etiqueta_label = 'Usuarios';
        $adm_seccion_pertenece_descripcion = __FUNCTION__;
        $adm_namespace_name = 'gamboamartin/ks_ops';
        $adm_namespace_descripcion = 'gamboa.martin/ks_ops';

        $acl = (new _adm())->integra_acl(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_name: $adm_namespace_name, adm_namespace_descripcion: $adm_namespace_descripcion,
            adm_seccion_descripcion: __FUNCTION__,
            adm_seccion_pertenece_descripcion: $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion: $adm_sistema_descripcion,
            etiqueta_label: $etiqueta_label, link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acl', data:  $acl);
        }

        $adm_accion_modelo = (new adm_accion(link: $link));


        $adm_acciones_basicas = $adm_accion_modelo->inserta_acciones_basicas(adm_seccion: __FUNCTION__);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acciones basicas', data:  $adm_acciones_basicas);
        }

        return $acl;

    }

    final public function instala(PDO $link): array|stdClass
    {

        $result = new stdClass();

        $adm_seccion = $this->adm_seccion(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar adm_seccion', data:  $adm_seccion);
        }
        $result->adm_seccion = $adm_seccion;

        $adm_grupo = $this->adm_grupo(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar adm_grupo', data:  $adm_grupo);
        }
        $result->adm_grupo = $adm_grupo;

        $adm_usuario = $this->adm_usuario(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar adm_usuario', data:  $adm_usuario);
        }
        $result->adm_usuario = $adm_usuario;

        return $result;

    }



}
