<?php
namespace gamboamartin\ks_ops\models;
use gamboamartin\errores\errores;
use stdClass;

class com_cliente extends \gamboamartin\comercial\models\com_cliente {

    final public function alta_bd(array $keys_integra_ds = array('codigo', 'descripcion')): array|stdClass
    {
        $registro_original = $this->registro;
        $alta_bd = parent::alta_bd(keys_integra_ds: $keys_integra_ds);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al insertar cliente',data:  $alta_bd);
        }

        if(isset($registro_original['cat_sat_actividad_economica_id'])){

            $ks_cliente_ins = $this->ks_cliente_ins(
                cat_sat_actividad_economica_id: $registro_original['cat_sat_actividad_economica_id'],
                com_cliente_id: $alta_bd->registro_id);

            $ks_cliente_alta = (new ks_cliente(link: $this->link))->alta_registro(registro: $ks_cliente_ins);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al insertar ks_cliente',data:  $ks_cliente_alta);
            }

        }

        return $alta_bd;

    }

    private function ks_cliente_ins(int $cat_sat_actividad_economica_id, int $com_cliente_id): array
    {
        $ks_cliente_ins['com_cliente_id'] = $com_cliente_id;
        $ks_cliente_ins['cat_sat_actividad_economica_id'] = $cat_sat_actividad_economica_id;

        return $ks_cliente_ins;

    }

    final public function modifica_bd(array $registro, int $id, bool $reactiva = false,
                                      array $keys_integra_ds = array('codigo', 'descripcion'),
                                      bool $valida_conf_tipo_persona = true,
                                      bool $valida_metodo_pago = true): array|stdClass
    {
        $registro_original = $registro;
        $r_modifica = parent::modifica_bd(registro: $registro, id: $id, reactiva: $reactiva,
            keys_integra_ds: $keys_integra_ds, valida_conf_tipo_persona: $valida_conf_tipo_persona,
            valida_metodo_pago: $valida_metodo_pago);

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al modificar cliente',data:  $r_modifica);
        }

        if(isset($registro_original['cat_sat_actividad_economica_id'])){
            $filtro['com_cliente.id'] = $id;
            $existe_ks_cliente = (new ks_cliente(link: $this->link))->existe(filtro: $filtro);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al validar existe_ks_cliente',data:  $existe_ks_cliente);
            }
            if(!$existe_ks_cliente){
                $ks_cliente_ins = $this->ks_cliente_ins(
                    cat_sat_actividad_economica_id: $registro_original['cat_sat_actividad_economica_id'],
                    com_cliente_id: $id);

                $ks_cliente_alta = (new ks_cliente(link: $this->link))->alta_registro(registro: $ks_cliente_ins);
                if(errores::$error){
                    return $this->error->error(mensaje: 'Error al insertar ks_cliente',data:  $ks_cliente_alta);
                }
            }
            else{
                $ks_cliente_id = (new ks_cliente(link: $this->link))->id_by_cliente(com_cliente_id: $id);
                if(errores::$error){
                    return $this->error->error(mensaje: 'Error al obtener ks_cliente',data:  $ks_cliente_id);
                }
                $ks_cliente_upd['cat_sat_actividad_economica_id'] = $registro_original['cat_sat_actividad_economica_id'];
                $ks_cliente_update = (new ks_cliente(link: $this->link))->modifica_bd(registro: $ks_cliente_upd,id: $ks_cliente_id);
                if(errores::$error){
                    return $this->error->error(mensaje: 'Error al actualizar ks_cliente',data:  $ks_cliente_update);
                }
            }
        }

        return $r_modifica;

    }


}