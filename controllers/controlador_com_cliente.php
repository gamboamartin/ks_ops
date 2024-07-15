<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */

namespace gamboamartin\ks_ops\controllers;

use gamboamartin\direccion_postal\controllers\_init_dps;
use gamboamartin\errores\errores;
use base\controller\init;
use gamboamartin\ks_ops\models\com_cliente;
use gamboamartin\template_1\html;
use html\cat_sat_actividad_economica_html;
use html\com_cliente_html;
use PDO;
use stdClass;

final class controlador_com_cliente extends \gamboamartin\comercial\controllers\controlador_com_cliente
{

    public function __construct(PDO      $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass())
    {
        parent::__construct(link: $link, html: $html, paths_conf: $paths_conf);
        $this->modelo = new com_cliente(link: $this->link);

        $this->childrens_data = array();
    }

    public function alta(bool $header, bool $ws = false): array|string
    {
        $urls_js = (new _init_dps())->init_js(controler: $this);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al generar url js', data: $urls_js, header: $header, ws: $ws);
        }

        $r_alta = $this->init_alta();
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al inicializar alta', data: $r_alta, header: $header, ws: $ws);
        }

        $inputs = $this->data_form();
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener inputs', data: $inputs, header: $header, ws: $ws);
        }

        return $r_alta;
    }

    protected function init_datatable(): stdClass
    {
        $columns["com_cliente_id"]["titulo"] = "Id";
        $columns["com_cliente_codigo"]["titulo"] = "Código";
        $columns["com_cliente_razon_social"]["titulo"] = "Razón Social";
        $columns["com_cliente_rfc"]["titulo"] = "RFC";
        $columns["com_tipo_cliente_descripcion"]["titulo"] = "Tipo";
        $columns["cat_sat_actividad_economica_descripcion"]["titulo"] = "Giro/Act";
        $columns["com_cliente_telefono"]["titulo"] = "Teléfono ";

        $filtro = array("com_cliente.id", "com_cliente.codigo", "com_cliente.razon_social", "com_cliente.rfc",
            "com_cliente.telefono",'com_tipo_cliente.descripcion','cat_sat_actividad_economica.descripcion');

        $datatables = new stdClass();
        $datatables->columns = $columns;
        $datatables->filtro = $filtro;

        return $datatables;
    }

    protected function data_form(): array|stdClass
    {
        $keys_selects = $this->init_selects_inputs();
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al inicializar selects', data: $keys_selects);
        }


        $keys_selects['dp_pais_id']->required = false;
        $keys_selects['dp_estado_id']->required = false;
        $keys_selects['dp_municipio_id']->required = false;
        $keys_selects['cat_sat_uso_cfdi_id']->required = false;
        $keys_selects['cat_sat_metodo_pago_id']->required = false;
        $keys_selects['cat_sat_forma_pago_id']->required = false;
        $keys_selects['cat_sat_tipo_de_comprobante_id']->required = false;
        $keys_selects['cat_sat_moneda_id']->required = false;

        $data_extra_cat_sat_metodo_pago[] = 'cat_sat_metodo_pago_codigo';
        $keys_selects['cat_sat_metodo_pago_id']->extra_params_keys = $data_extra_cat_sat_metodo_pago;

        $data_extra_cat_sat_forma_pago[] = 'cat_sat_forma_pago_codigo';
        $keys_selects['cat_sat_forma_pago_id']->extra_params_keys = $data_extra_cat_sat_forma_pago;

        $com_cliente_rfc = (new com_cliente_html(html: $this->html_base))->input_rfc(cols: 6, row_upd: $this->row_upd,
            value_vacio: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar input', data: $com_cliente_rfc);
        }

        $this->inputs->com_cliente_rfc = $com_cliente_rfc;

        $inputs = $this->inputs(keys_selects: $keys_selects);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al obtener inputs', data: $inputs);
        }

        $cat_sat_actividad_economica_id = (
            new cat_sat_actividad_economica_html(html: $this->html_base))->select_cat_sat_actividad_economica_id(
                cols: 12, con_registros: true,id_selected: -1,link: $this->link,label: 'Giro/Actividad');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al obtener cat_sat_actividad_economica_id',
                data: $cat_sat_actividad_economica_id);
        }

        $inputs->cat_sat_actividad_economica_id = $cat_sat_actividad_economica_id;


        return $inputs;
    }

    public function modifica_cliente(bool $header, bool $ws = false, array $not_actions = array()): array|stdClass
    {
        $urls_js = (new _init_dps())->init_js(controler: $this);

        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al generar url js', data: $urls_js, header: $header, ws: $ws);
        }

        $r_modifica = $this->init_modifica();
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template', data: $r_modifica, header: $header, ws: $ws);
        }

        $keys_selects = $this->key_select(cols: 12, con_registros: true, filtro: array(), key: "com_tipo_cliente_id",
            keys_selects: array(), id_selected: $this->registro['com_tipo_cliente_id'], label: "Tipo de Cliente");
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al obtener keys_selects', data: $keys_selects);
        }

        $keys_selects = $this->key_select(cols: 12, con_registros: true, filtro: array(), key: "cat_sat_tipo_persona_id",
            keys_selects: $keys_selects, id_selected: $this->registro['cat_sat_tipo_persona_id'], label: "Tipo de Persona");
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al obtener keys_selects', data: $keys_selects);
        }

        $keys_selects = $this->key_select(cols: 12, con_registros: true, filtro: array(), key: "cat_sat_regimen_fiscal_id",
            keys_selects: $keys_selects, id_selected: $this->registro['cat_sat_regimen_fiscal_id'], label: "Régimen Fiscal");
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al obtener keys_selects', data: $keys_selects);
        }

        $keys_selects = $this->key_select(cols: 6, con_registros: true, filtro: array(), key: "dp_pais_id",
            keys_selects: $keys_selects, id_selected: $this->registro['dp_pais_id'], label: "País");
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al obtener keys_selects', data: $keys_selects);
        }
        $keys_selects['dp_pais_id']->key_descripcion_select = 'dp_pais_descripcion';

        $keys_selects = $this->key_select(cols: 6, con_registros: true, filtro: array('dp_pais.id' => $this->registro['dp_pais_id']),
            key: "dp_estado_id", keys_selects: $keys_selects, id_selected: $this->registro['dp_estado_id'], label: "Estado");
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al obtener keys_selects', data: $keys_selects);
        }
        $keys_selects['dp_estado_id']->key_descripcion_select = 'dp_estado_descripcion';

        $keys_selects = $this->key_select(cols: 6, con_registros: true, filtro: array('dp_estado.id' => $this->registro['dp_estado_id']),
            key: "dp_municipio_id", keys_selects: $keys_selects, id_selected: $this->registro['dp_municipio_id'], label: "Municipio");
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al obtener keys_selects', data: $keys_selects);
        }
        $keys_selects['dp_municipio_id']->key_descripcion_select = 'dp_municipio_descripcion';

        $keys_selects['com_tipo_cliente_id']->disabled = true;

        $keys_selects = (new init())->key_select_txt(cols: 4, key: 'codigo',
            keys_selects: $keys_selects, place_holder: 'Cod');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }
        $keys_selects['codigo']->disabled = true;

        $keys_selects = (new init())->key_select_txt(cols: 8, key: 'razon_social',
            keys_selects: $keys_selects, place_holder: 'Razón Social');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }
        $keys_selects['razon_social']->disabled = true;

        $keys_selects = (new init())->key_select_txt(cols: 6, key: 'rfc',
            keys_selects: $keys_selects, place_holder: 'RFC');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }
        $keys_selects['rfc']->disabled = true;


        $base = $this->base_upd(keys_selects: $keys_selects, params: array(), params_ajustados: array());
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar base', data: $base, header: $header, ws: $ws);
        }

        return $r_modifica;
    }


}
