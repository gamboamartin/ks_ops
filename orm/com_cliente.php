<?php
namespace gamboamartin\ks_ops\models;
use gamboamartin\errores\errores;
use stdClass;

class com_cliente extends \gamboamartin\comercial\models\com_cliente {

    final public function alta_bd(array $keys_integra_ds = array('codigo', 'descripcion')): array|stdClass
    {
        $alta_bd = parent::alta_bd(keys_integra_ds: $keys_integra_ds);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al insertar cliente',data:  $alta_bd);
        }
        return $alta_bd;

    }


}