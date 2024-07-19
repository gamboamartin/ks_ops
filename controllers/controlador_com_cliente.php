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

    protected function campos_view(): array
    {
        $keys = new stdClass();
        $keys->inputs = array('codigo', 'razon_social', 'rfc','numero_exterior', 'numero_interior',
            'cp', 'colonia', 'calle', 'nombre', 'ap', 'am', 'porcentaje');
        $keys->telefonos = array('telefono');
        $keys->emails = array('correo');
        $keys->fechas = array('fecha_inicio', 'fecha_fin');
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
        $init_data['com_tipo_contacto'] = "gamboamartin\\comercial";
        $campos_view = $this->campos_view_base(init_data: $init_data, keys: $keys);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al inicializar campo view', data: $campos_view);
        }

        return $campos_view;
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

    public function comisiones_generales(bool $header, bool $ws = false, array $not_actions = array()): array|string
    {
        $this->accion_titulo = 'Comisiones Generales';

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

        $keys_selects = (new init())->key_select_txt(cols: 4, key: 'porcentaje', keys_selects: $keys_selects,
            place_holder: 'Porcentaje');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 4, key: 'fecha_inicio', keys_selects: $keys_selects,
            place_holder: 'Fecha Inicio');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 4, key: 'fecha_fin', keys_selects: $keys_selects,
            place_holder: 'Fecha Fin');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $this->row_upd->fecha_inicio = date('Y-m-d');
        $this->row_upd->fecha_fin = date('Y-m-d');

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(), params_ajustados: array());
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar base', data: $base, header: $header, ws: $ws);
        }

        $data_view = new stdClass();
        $data_view->names = array('Id', 'Porcentaje', 'Fecha Inicio', 'Fecha Fin','Acciones');
        $data_view->keys_data = array('ks_comision_general_id', 'ks_comision_general_porcentaje', 'ks_comision_general_fecha_inicio',
            'ks_comision_general_fecha_fin');
        $data_view->key_actions = 'acciones';
        $data_view->namespace_model = 'gamboamartin\\ks_ops\\models';
        $data_view->name_model_children = 'ks_comision_general';

        $contenido_table = $this->contenido_children(data_view: $data_view, next_accion: __FUNCTION__,
            not_actions: $not_actions);
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al obtener tbody', data: $contenido_table, header: $header, ws: $ws);
        }

        return $contenido_table;
    }



    final public function modifica(bool $header, bool $ws = false): array|stdClass
    {
        $r_modifica_bd = parent::modifica(header: $header, ws: $ws);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al obtener template', data: $r_modifica_bd);
        }

        $com_cliente = (new com_cliente(link: $this->link))->registro(registro_id: $this->registro_id, retorno_obj: true);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al obtener com_cliente',
                data: $com_cliente);
        }


        $cat_sat_actividad_economica_id = (
        new cat_sat_actividad_economica_html(html: $this->html_base))->select_cat_sat_actividad_economica_id(
                cols: 12, con_registros: true,id_selected: $com_cliente->cat_sat_actividad_economica_id,
                link: $this->link,label: 'Giro/Actividad');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al obtener cat_sat_actividad_economica_id',
                data: $cat_sat_actividad_economica_id);
        }

        $this->inputs->cat_sat_actividad_economica_id = $cat_sat_actividad_economica_id;

        return $r_modifica_bd;

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
