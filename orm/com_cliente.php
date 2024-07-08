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

    final public function modifica_bd(array $registro, int $id, bool $reactiva = false,
                                      array $keys_integra_ds = array('codigo', 'descripcion'),
                                      bool $valida_conf_tipo_persona = true,
                                      bool $valida_metodo_pago = true): array|stdClass
    {
        $r_modifica = parent::modifica_bd(registro: $registro, id: $id, reactiva: $reactiva,
            keys_integra_ds: $keys_integra_ds, valida_conf_tipo_persona: $valida_conf_tipo_persona,
            valida_metodo_pago: $valida_metodo_pago);
        
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al modificar cliente',data:  $r_modifica);
        }
        return $r_modifica;

    }


}