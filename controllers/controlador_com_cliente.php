<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */

namespace gamboamartin\ks_ops\controllers;

use gamboamartin\errores\errores;
use gamboamartin\ks_ops\models\com_rel_agente_cliente;
use gamboamartin\system\actions;
use stdClass;

final class controlador_com_cliente extends \gamboamartin\comercial\controllers\controlador_com_cliente
{
    public string $link_com_rel_agente_cliente_bd = '';

    protected function campos_view(): array
    {
        $keys = new stdClass();
        $keys->inputs = array('codigo', 'razon_social', 'rfc', 'telefono', 'numero_exterior', 'numero_interior',
            'cp', 'colonia', 'calle');
        $keys->selects = array();

        $init_data = array();
        $init_data['dp_pais'] = "gamboamartin\\direccion_postal";
        $init_data['dp_estado'] = "gamboamartin\\direccion_postal";
        $init_data['dp_municipio'] = "gamboamartin\\direccion_postal";
        $init_data['dp_cp'] = "gamboamartin\\direccion_postal";
        $init_data['dp_colonia_postal'] = "gamboamartin\\direccion_postal";
        $init_data['dp_calle_pertenece'] = "gamboamartin\\direccion_postal";
        $init_data['cat_sat_regimen_fiscal'] = "gamboamartin\\cat_sat";
        $init_data['cat_sat_moneda'] = "gamboamartin\\cat_sat";
        $init_data['cat_sat_forma_pago'] = "gamboamartin\\cat_sat";
        $init_data['cat_sat_metodo_pago'] = "gamboamartin\\cat_sat";
        $init_data['cat_sat_uso_cfdi'] = "gamboamartin\\cat_sat";
        $init_data['cat_sat_tipo_de_comprobante'] = "gamboamartin\\cat_sat";
        $init_data['com_tipo_cliente'] = "gamboamartin\\comercial";
        $init_data['cat_sat_tipo_persona'] = "gamboamartin\\cat_sat";
        $init_data['com_agente'] = "gamboamartin\\comercial";
        $campos_view = $this->campos_view_base(init_data: $init_data, keys: $keys);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al inicializar campo view', data: $campos_view);
        }

        return $campos_view;
    }

    protected function init_links(): array|string
    {
        $links = $this->obj_link->genera_links(controler: $this);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al generar links', data: $links);
            print_r($error);
            exit;
        }

        $link = $this->obj_link->get_link(seccion: "com_cliente", accion: "asigna_agente_bd");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al recuperar link autoriza_bd', data: $link);
            print_r($error);
            exit;
        }
        $this->link_com_rel_agente_cliente_bd = $link;

        return $link;
    }


    public function asigna_agente(bool $header, bool $ws = false): array|stdClass
    {
        $init_links = $this->init_links();
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al inicializar links', data: $init_links);
            print_r($error);
            die('Error');
        }

        $this->accion_titulo = 'Asignar agente';

        $r_modifica = $this->init_modifica();
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template', data: $r_modifica, header: $header, ws: $ws);
        }

        $keys_selects = $this->init_selects_inputs();
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al inicializar selects', data: $keys_selects, header: $header,
                ws: $ws);
        }

        $relacion = (new com_rel_agente_cliente(link: $this->link))->filtro_and(filtro: array('com_cliente.id' => $this->registro['com_cliente_id']));
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener relacion', data: $relacion, header: $header, ws: $ws);
        }

        $id_selected = $relacion->n_registros > 0 ? $relacion->registros[0]['com_agente_id'] : -1;

        $keys_selects = $this->key_select(cols: 12, con_registros: true, filtro: array(), key: 'com_agente_id',
            keys_selects: $keys_selects, id_selected: $id_selected, label: 'Agente');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects['com_agente_id']->required = true;

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(), params_ajustados: array());
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar base', data: $base, header: $header, ws: $ws);
        }

        return $r_modifica;
    }

    public function asigna_agente_bd(bool $header, bool $ws = false): array|stdClass
    {
        $this->link->beginTransaction();

        $siguiente_view = (new actions())->init_alta_bd();
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener siguiente view', data: $siguiente_view,
                header: $header, ws: $ws);
        }

        if (isset($_POST['btn_action_next'])) {
            unset($_POST['btn_action_next']);
        }

        $relacion = (new com_rel_agente_cliente(link: $this->link))->filtro_and(filtro: array('com_cliente.id' => $this->registro_id));
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener relacion', data: $relacion, header: $header, ws: $ws);
        }

        $proceso = new stdClass();

        if ($relacion->n_registros == 0) {
            $registro['com_agente_id'] = $_POST['com_agente_id'];
            $registro['com_cliente_id'] = $this->registro_id;

            $proceso = (new com_rel_agente_cliente($this->link, array('com_agente')))->alta_registro(registro: $registro);
            if (errores::$error) {
                $this->link->rollBack();
                return $this->retorno_error(mensaje: 'Error al dar de alta relacion', data: $proceso, header: $header,
                    ws: $ws);
            }

        } else {
            $registro['com_agente_id'] = $_POST['com_agente_id'];
            $registro['com_cliente_id'] = $this->registro_id;
            $id = $relacion->registros[0]['com_rel_agente_cliente_id'];
            $proceso = (new com_rel_agente_cliente($this->link, array('com_agente')))->modifica_bd(registro: $registro,id: $id);
            if (errores::$error) {
                $this->link->rollBack();
                return $this->retorno_error(mensaje: 'Error al modificar relacion', data: $proceso, header: $header,
                    ws: $ws);
            }
        }

        $this->link->commit();

        if ($header) {
            $this->retorno_base(registro_id: $this->registro_id, result: $proceso,
                siguiente_view: "lista", ws: $ws);
        }
        if ($ws) {
            header('Content-Type: application/json');
            echo json_encode($proceso, JSON_THROW_ON_ERROR);
            exit;
        }
        $proceso->siguiente_view = "lista";

        return $proceso;
    }


}
