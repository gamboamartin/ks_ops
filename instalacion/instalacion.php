<?php
namespace gamboamartin\ks_ops\instalacion;


use gamboamartin\administrador\instalacion\_adm;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class instalacion
{

    private function adm_accion(PDO $link): array|stdClass
    {

        $adm_menu_descripcion = 'ACL';
        $adm_sistema_descripcion = 'ks_ops';
        $etiqueta_label = 'Acciones';
        $adm_seccion_pertenece_descripcion = __FUNCTION__;
        $adm_namespace_name = 'gamboamartin/ks_ops';
        $adm_namespace_descripcion = 'gamboa.martin/ks_ops';

        $adm_acciones_basicas = (new _adm())->acl_base(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_descripcion:  $adm_namespace_descripcion,adm_namespace_name:  $adm_namespace_name,
            adm_seccion_descripcion: __FUNCTION__,
            adm_seccion_pertenece_descripcion:  $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion:  $adm_sistema_descripcion, etiqueta_label: $etiqueta_label,link:  $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acciones basicas', data:  $adm_acciones_basicas);
        }


        return $adm_acciones_basicas;

    }

    private function adm_grupo(PDO $link): array|stdClass
    {


        $adm_menu_descripcion = 'ACL';
        $adm_sistema_descripcion = 'ks_ops';
        $etiqueta_label = 'Grupos';
        $adm_seccion_pertenece_descripcion = __FUNCTION__;
        $adm_namespace_name = 'gamboamartin/ks_ops';
        $adm_namespace_descripcion = 'gamboa.martin/ks_ops';

        $adm_acciones_basicas = (new _adm())->acl_base(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_descripcion:  $adm_namespace_descripcion,adm_namespace_name:  $adm_namespace_name,
            adm_seccion_descripcion: __FUNCTION__,
            adm_seccion_pertenece_descripcion:  $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion:  $adm_sistema_descripcion, etiqueta_label: $etiqueta_label,link:  $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acciones basicas', data:  $adm_acciones_basicas);
        }


        return $adm_acciones_basicas;

    }

    private function adm_seccion(PDO $link): array|stdClass
    {

        $adm_menu_descripcion = 'ACL';
        $adm_sistema_descripcion = 'ks_ops';
        $etiqueta_label = 'Secciones';
        $adm_seccion_pertenece_descripcion = __FUNCTION__;
        $adm_namespace_name = 'gamboamartin/ks_ops';
        $adm_namespace_descripcion = 'gamboa.martin/ks_ops';

        $adm_acciones_basicas = (new _adm())->acl_base(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_descripcion:  $adm_namespace_descripcion,adm_namespace_name:  $adm_namespace_name,
            adm_seccion_descripcion: __FUNCTION__,
            adm_seccion_pertenece_descripcion:  $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion:  $adm_sistema_descripcion, etiqueta_label: $etiqueta_label,link:  $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acciones basicas', data:  $adm_acciones_basicas);
        }


        return $adm_acciones_basicas;



    }

    private function adm_usuario(PDO $link): array|stdClass
    {

        $adm_menu_descripcion = 'ACL';
        $adm_sistema_descripcion = 'ks_ops';
        $etiqueta_label = 'Usuarios';
        $adm_seccion_pertenece_descripcion = __FUNCTION__;
        $adm_namespace_name = 'gamboamartin/ks_ops';
        $adm_namespace_descripcion = 'gamboa.martin/ks_ops';

        $adm_acciones_basicas = (new _adm())->acl_base(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_descripcion:  $adm_namespace_descripcion,adm_namespace_name:  $adm_namespace_name,
            adm_seccion_descripcion: __FUNCTION__,
            adm_seccion_pertenece_descripcion:  $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion:  $adm_sistema_descripcion, etiqueta_label: $etiqueta_label,link:  $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acciones basicas', data:  $adm_acciones_basicas);
        }


        return $adm_acciones_basicas;

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


        $adm_accion = $this->adm_accion(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar adm_accion', data:  $adm_accion);
        }
        $result->adm_grupo = $adm_grupo;

        $adm_usuario = $this->adm_usuario(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar adm_usuario', data:  $adm_usuario);
        }
        $result->adm_usuario = $adm_usuario;

        $not_emisor = $this->not_emisor(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar not_emisor', data:  $not_emisor);
        }
        $result->not_emisor = $not_emisor;

        $not_rel_mensaje = $this->not_rel_mensaje(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar not_rel_mensaje', data:  $not_rel_mensaje);
        }
        $result->not_rel_mensaje = $not_rel_mensaje;

        $pr_etapa = $this->pr_etapa(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar pr_etapa', data:  $pr_etapa);
        }
        $result->pr_etapa_proceso = $pr_etapa;

        $pr_proceso = $this->pr_proceso(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar pr_proceso', data:  $pr_proceso);
        }
        $result->pr_proceso = $pr_proceso;


        $pr_etapa_proceso = $this->pr_etapa_proceso(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar not_emisor', data:  $pr_etapa_proceso);
        }
        $result->pr_etapa_proceso = $pr_etapa_proceso;

        return $result;

    }

    private function not_emisor(PDO $link): array|stdClass
    {

        $adm_menu_descripcion = 'NOTIFICACIONES';
        $adm_sistema_descripcion = 'ks_ops';
        $etiqueta_label = 'Emisores';
        $adm_seccion_pertenece_descripcion = __FUNCTION__;
        $adm_namespace_name = 'gamboamartin/ks_ops';
        $adm_namespace_descripcion = 'gamboa.martin/ks_ops';

        $adm_acciones_basicas = (new _adm())->acl_base(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_descripcion:  $adm_namespace_descripcion,adm_namespace_name:  $adm_namespace_name,
            adm_seccion_descripcion: __FUNCTION__,
            adm_seccion_pertenece_descripcion:  $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion:  $adm_sistema_descripcion, etiqueta_label: $etiqueta_label,link:  $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acciones basicas', data:  $adm_acciones_basicas);
        }


        return $adm_acciones_basicas;

    }

    private function not_rel_mensaje(PDO $link): array|stdClass
    {

        $adm_menu_descripcion = 'NOTIFICACIONES';
        $adm_sistema_descripcion = 'ks_ops';
        $etiqueta_label = 'Relacion de Mensajes';
        $adm_seccion_pertenece_descripcion = __FUNCTION__;
        $adm_namespace_name = 'gamboamartin/ks_ops';
        $adm_namespace_descripcion = 'gamboa.martin/ks_ops';

        $adm_acciones_basicas = (new _adm())->acl_base(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_descripcion:  $adm_namespace_descripcion,adm_namespace_name:  $adm_namespace_name,
            adm_seccion_descripcion: __FUNCTION__,
            adm_seccion_pertenece_descripcion:  $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion:  $adm_sistema_descripcion, etiqueta_label: $etiqueta_label,link:  $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acciones basicas', data:  $adm_acciones_basicas);
        }


        return $adm_acciones_basicas;

    }

    private function pr_etapa(PDO $link): array|stdClass
    {

        $adm_menu_descripcion = 'PROCESOS';
        $adm_sistema_descripcion = 'ks_ops';
        $etiqueta_label = 'Etapa';
        $adm_seccion_pertenece_descripcion = __FUNCTION__;
        $adm_namespace_name = 'gamboamartin/ks_ops';
        $adm_namespace_descripcion = 'gamboa.martin/ks_ops';

        $adm_acciones_basicas = (new _adm())->acl_base(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_descripcion:  $adm_namespace_descripcion,adm_namespace_name:  $adm_namespace_name,
            adm_seccion_descripcion: __FUNCTION__,
            adm_seccion_pertenece_descripcion:  $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion:  $adm_sistema_descripcion, etiqueta_label: $etiqueta_label,link:  $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acciones basicas', data:  $adm_acciones_basicas);
        }


        return $adm_acciones_basicas;

    }

    private function pr_etapa_proceso(PDO $link): array|stdClass
    {

        $adm_menu_descripcion = 'PROCESOS';
        $adm_sistema_descripcion = 'ks_ops';
        $etiqueta_label = 'Etapa Proceso';
        $adm_seccion_pertenece_descripcion = __FUNCTION__;
        $adm_namespace_name = 'gamboamartin/ks_ops';
        $adm_namespace_descripcion = 'gamboa.martin/ks_ops';

        $adm_acciones_basicas = (new _adm())->acl_base(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_descripcion:  $adm_namespace_descripcion,adm_namespace_name:  $adm_namespace_name,
            adm_seccion_descripcion: __FUNCTION__,
            adm_seccion_pertenece_descripcion:  $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion:  $adm_sistema_descripcion, etiqueta_label: $etiqueta_label,link:  $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acciones basicas', data:  $adm_acciones_basicas);
        }


        return $adm_acciones_basicas;

    }

    private function pr_proceso(PDO $link): array|stdClass
    {

        $adm_menu_descripcion = 'PROCESOS';
        $adm_sistema_descripcion = 'ks_ops';
        $etiqueta_label = 'Proceso';
        $adm_seccion_pertenece_descripcion = __FUNCTION__;
        $adm_namespace_name = 'gamboamartin/ks_ops';
        $adm_namespace_descripcion = 'gamboa.martin/ks_ops';

        $adm_acciones_basicas = (new _adm())->acl_base(adm_menu_descripcion: $adm_menu_descripcion,
            adm_namespace_descripcion:  $adm_namespace_descripcion,adm_namespace_name:  $adm_namespace_name,
            adm_seccion_descripcion: __FUNCTION__,
            adm_seccion_pertenece_descripcion:  $adm_seccion_pertenece_descripcion,
            adm_sistema_descripcion:  $adm_sistema_descripcion, etiqueta_label: $etiqueta_label,link:  $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acciones basicas', data:  $adm_acciones_basicas);
        }


        return $adm_acciones_basicas;

    }



}
