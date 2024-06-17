<?php
namespace gamboamartin\ks_ops\instalacion;


use gamboamartin\administrador\instalacion\_adm;
use gamboamartin\administrador\models\adm_accion;
use gamboamartin\administrador\models\adm_accion_basica;
use gamboamartin\administrador\models\adm_seccion;
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

        $adm_accion_basica_modelo = new adm_accion_basica(link: $link);
        $adm_accion_modelo = (new adm_accion(link: $link));
        $adm_seccion_modelo = (new adm_seccion(link: $link));

        $adm_seccion_id = $adm_seccion_modelo->adm_seccion_id(descripcion: __FUNCTION__);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener adm_seccion_id', data:  $adm_seccion_id);
        }


        $adm_acciones_basicas = $adm_accion_basica_modelo->registros_activos();
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al obtener acciones basicas', data:  $adm_acciones_basicas);
        }

        foreach ($adm_acciones_basicas as $adm_accion_basica) {
           $existe = $adm_accion_modelo->existe_accion(
               adm_accion: $adm_accion_basica['adm_accion_basica_descripcion'],adm_seccion: __FUNCTION__);
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error AL VERIFICAR SI EXISTE', data:  $existe);
            }
            if(!$existe){
                $inserta = $adm_seccion_modelo->inserta_accion(
                    accion_basica: $adm_accion_basica,registro_id:  $adm_seccion_id);
                if(errores::$error){
                    return (new errores())->error(mensaje: 'Error INSERTAR ACCION', data:  $inserta);
                }
            }
        }


        return $acl;

    }

    final public function instala(PDO $link): array|stdClass
    {

        $result = new stdClass();

        $adm_grupo = $this->adm_grupo(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar adm_grupo', data:  $adm_grupo);
        }
        $result->adm_grupo = $adm_grupo;

        return $result;

    }



}
