<?php
namespace gamboamartin\ks_ops\models;
use gamboamartin\cat_sat\models\cat_sat_periodicidad;
use gamboamartin\cat_sat\models\cat_sat_regimen_fiscal;
use gamboamartin\cat_sat\models\cat_sat_tipo_persona;
use gamboamartin\direccion_postal\models\dp_municipio;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class com_cliente extends \gamboamartin\comercial\models\com_cliente {

    public function __construct(PDO $link)
    {
        parent::__construct(link: $link);
        $this->extension_estructura['ks_cliente']['key'] = 'com_cliente_id';
        $this->extension_estructura['ks_cliente']['enlace'] = 'com_cliente';
        $this->extension_estructura['ks_cliente']['key_enlace'] = 'id';

        $this->extension_estructura['cat_sat_actividad_economica']['key'] = 'id';
        $this->extension_estructura['cat_sat_actividad_economica']['enlace'] = 'ks_cliente';
        $this->extension_estructura['cat_sat_actividad_economica']['key_enlace'] = 'cat_sat_actividad_economica_id';

        $this->extension_estructura['cat_sat_periodicidad']['key'] = 'id';
        $this->extension_estructura['cat_sat_periodicidad']['enlace'] = 'ks_cliente';
        $this->extension_estructura['cat_sat_periodicidad']['key_enlace'] = 'cat_sat_periodicidad_id';
    }

    final public function alta_bd(array $keys_integra_ds = array('codigo', 'descripcion')): array|stdClass
    {
        $predefinidos = $this->valores_predeterminados();
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener valores predeterminados',data:  $predefinidos);
        }

        $registro_original = $this->registro;
        $alta_bd = parent::alta_bd(keys_integra_ds: $keys_integra_ds);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al insertar cliente',data:  $alta_bd);
        }

        if(isset($registro_original['cat_sat_actividad_economica_id'])){

            $ks_cliente_ins = $this->ks_cliente_ins(
                cat_sat_actividad_economica_id: $registro_original['cat_sat_actividad_economica_id'],
                com_cliente_id: $alta_bd->registro_id, iva: $registro_original['iva']);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al inicializar valores',data:  $ks_cliente_ins);
            }

            $ks_cliente_alta = (new ks_cliente(link: $this->link))->alta_registro(registro: $ks_cliente_ins);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al insertar ks_cliente',data:  $ks_cliente_alta);
            }

        }

        return $alta_bd;
    }

    public function valores_predeterminados() : array
    {
        if (!isset($this->registro['numero_exterior'])) {
            $this->registro['numero_exterior'] = 1;
        }

        if (isset($this->registro['tipo_persona'])) {
            $cat_sat_tipo_persona = (new cat_sat_tipo_persona(link: $this->link))->filtro_and(
                filtro: ['cat_sat_tipo_persona.descripcion' => $this->registro['tipo_persona']]);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al obtener cat_sat_tipo_persona',data:  $cat_sat_tipo_persona);
            }

            if (count($cat_sat_tipo_persona->registros) == 0) {
                return $this->error->error(mensaje: 'No se encontró el tipo de persona: '.$this->registro['tipo_persona'],
                    data:  $this->registro);
            }

            $this->registro['cat_sat_tipo_persona_id'] = $cat_sat_tipo_persona->registros[0]['cat_sat_tipo_persona_id'];
            unset($this->registro['tipo_persona']);
        }

        if (isset($this->registro['regimen_fiscal'])) {
            $cat_sat_regimen_fiscal = (new cat_sat_regimen_fiscal(link: $this->link))->filtro_and(
                filtro: ['cat_sat_regimen_fiscal.descripcion' => $this->registro['regimen_fiscal']]);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al obtener cat_sat_regimen_fiscal',data:  $cat_sat_regimen_fiscal);
            }

            if (count($cat_sat_regimen_fiscal->registros) == 0) {
                return $this->error->error(mensaje: 'No se encontró el régimen fiscal: '.$this->registro['regimen_fiscal'],
                    data:  $this->registro);
            }

            $this->registro['cat_sat_regimen_fiscal_id'] = $cat_sat_regimen_fiscal->registros[0]['cat_sat_regimen_fiscal_id'];
            unset($this->registro['regimen_fiscal']);
        }


        if(isset($this->registro['municipio'])) {
            $dp_municipio = (new dp_municipio(link: $this->link))->filtro_and(
                filtro: ['dp_municipio.descripcion' => $this->registro['municipio']]);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al obtener dp_municipio',data:  $dp_municipio);
            }

            if (count($dp_municipio->registros) == 0) {
                return $this->error->error(mensaje: 'No se encontró el municipio: '.$this->registro['municipio'],
                    data:  $this->registro);
            }

            $this->registro['dp_municipio_id'] = $dp_municipio->registros[0]['dp_municipio_id'];
            unset($this->registro['municipio']);
        }

        if (!isset($this->registro['dp_municipio_id'])) {
            $this->registro['dp_municipio_id'] = (new dp_municipio(link: $this->link))->id_predeterminado();
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al obtener id municipio predeterminado', data: $this->registro);
            }
        }

        if (!isset($this->registro['cat_sat_tipo_persona_id'])) {
            $this->registro['cat_sat_tipo_persona_id'] = (new cat_sat_tipo_persona(link: $this->link))->id_predeterminado();
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al obtener id tipo persona predeterminado', data: $this->registro);
            }
        }

        return $this->registro;
    }



    final public function elimina_bd(int $id): array|stdClass
    {
        $filtro['com_cliente_id'] = $id;
        $del = (new ks_cliente(link: $this->link))->elimina_con_filtro_and(filtro: $filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al eliminar ks_cliente',data:  $del);
        }
        $del = parent::elimina_bd(id: $id);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al eliminar cliente',data:  $del);
        }

        return $del;


    }

    private function ks_cliente_ins(int $cat_sat_actividad_economica_id, int $com_cliente_id, float $iva): array
    {
        $ks_cliente_ins['com_cliente_id'] = $com_cliente_id;
        $ks_cliente_ins['cat_sat_actividad_economica_id'] = $cat_sat_actividad_economica_id;
        $ks_cliente_ins['cat_sat_periodicidad_id'] =  (new cat_sat_periodicidad($this->link))->id_predeterminado();
        $ks_cliente_ins['iva'] = $iva;
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener registro predeterminado cat_sat_periodicidad',data: $ks_cliente_ins);
        }

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
                    com_cliente_id: $id,iva: $registro_original['iva']);

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
                $ks_cliente_upd['iva'] = $registro_original['iva'];
                $ks_cliente_update = (new ks_cliente(link: $this->link))->modifica_bd(registro: $ks_cliente_upd,id: $ks_cliente_id);
                if(errores::$error){
                    return $this->error->error(mensaje: 'Error al actualizar ks_cliente',data:  $ks_cliente_update);
                }

            }
        }

        return $r_modifica;

    }


}