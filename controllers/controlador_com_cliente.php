<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */

namespace gamboamartin\ks_ops\controllers;

use DateTime;
use gamboamartin\banco\models\bn_sucursal;
use gamboamartin\direccion_postal\controllers\_init_dps;
use gamboamartin\empleado\models\em_cuenta_bancaria;
use gamboamartin\errores\errores;
use base\controller\init;
use gamboamartin\ks_ops\html\selec_html;
use gamboamartin\ks_ops\models\com_cliente;
use gamboamartin\ks_ops\models\em_empleado;
use gamboamartin\ks_ops\models\ks_cliente_empleado;
use gamboamartin\ks_ops\models\ks_comision_general;
use gamboamartin\plugins\exportador;
use gamboamartin\system\actions;
use gamboamartin\template_1\html;
use html\cat_sat_actividad_economica_html;
use html\com_cliente_html;
use html\em_empleado_html;
use PDO;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use stdClass;

final class controlador_com_cliente extends \gamboamartin\comercial\controllers\controlador_com_cliente
{
    public string $link_comisiones_generales_bd = '';
    public string $link_asigna_empleado_bd = '';

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
        $keys->inputs = array('codigo', 'razon_social', 'rfc', 'numero_exterior', 'numero_interior',
            'cp', 'colonia', 'calle', 'nombre', 'ap', 'am', 'porcentaje', 'nss', 'curp', 'registro_patronal');
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
        $init_data['cat_sat_periodicidad'] = "gamboamartin\\cat_sat";
        $init_data['cat_sat_moneda'] = "gamboamartin\\cat_sat";
        $init_data['cat_sat_forma_pago'] = "gamboamartin\\cat_sat";
        $init_data['cat_sat_metodo_pago'] = "gamboamartin\\cat_sat";
        $init_data['cat_sat_uso_cfdi'] = "gamboamartin\\cat_sat";
        $init_data['cat_sat_tipo_de_comprobante'] = "gamboamartin\\cat_sat";
        $init_data['com_tipo_cliente'] = "gamboamartin\\comercial";
        $init_data['cat_sat_tipo_persona'] = "gamboamartin\\cat_sat";
        $init_data['com_agente'] = "gamboamartin\\comercial";
        $init_data['com_tipo_contacto'] = "gamboamartin\\comercial";
        $init_data['org_puesto'] = "gamboamartin\\organigrama";
        $init_data['em_registro_patronal'] = "gamboamartin\\empleado";
        $campos_view = $this->campos_view_base(init_data: $init_data, keys: $keys);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al inicializar campo view', data: $campos_view);
        }

        return $campos_view;
    }

    public function asigna_empleado(bool $header, bool $ws = false, array $not_actions = array()): array|string
    {
        $this->accion_titulo = 'Asignar Empleado';

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

        $keys_selects = (new init())->key_select_txt(cols: 4, key: 'nombre', keys_selects: $keys_selects,
            place_holder: 'Nombre');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 4, key: 'ap', keys_selects: $keys_selects,
            place_holder: 'Apellido Paterno');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 4, key: 'am', keys_selects: $keys_selects,
            place_holder: 'Apellido Materno', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 12, key: 'registro_patronal', keys_selects: $keys_selects,
            place_holder: 'Registro Patronal');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new init())->key_select_txt(cols: 6, key: 'telefono', keys_selects: $keys_selects,
            place_holder: 'Teléfono');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $this->row_upd->rfc = '';

        $em_empleado_rfc = (new em_empleado_html(html: $this->html_base))->input_rfc(cols: 6, row_upd: $this->row_upd,
            value_vacio: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar input', data: $em_empleado_rfc);
        }

        $em_empleado_curp = (new em_empleado_html(html: $this->html_base))->input_curp(cols: 12, row_upd: $this->row_upd,
            value_vacio: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar input', data: $em_empleado_curp);
        }

        $em_empleado_nss = (new em_empleado_html(html: $this->html_base))->input_nss(cols: 6, row_upd: $this->row_upd,
            value_vacio: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar input', data: $em_empleado_nss);
        }

        $this->inputs->em_empleado_rfc = $em_empleado_rfc;
        $this->inputs->em_empleado_curp = $em_empleado_curp;
        $this->inputs->em_empleado_nss = $em_empleado_nss;

        $this->row_upd->cp = "";
        $this->row_upd->colonia = "";
        $this->row_upd->calle = "";
        $this->row_upd->numero_exterior = "";
        $this->row_upd->numero_interior = "";
        $this->row_upd->telefono = '';

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(), params_ajustados: array());
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar base', data: $base, header: $header, ws: $ws);
        }

        $button = $this->html->button_href(accion: 'modifica', etiqueta: 'Ir a Cliente',
            registro_id: $this->registro_id, seccion: $this->tabla, style: 'warning', params: array());
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al generar link', data: $button);
        }

        $this->button_com_cliente_modifica = $button;

        $data_view = new stdClass();
        $data_view->names = array('Id', 'Empleado', 'RFC', 'NSS', 'Acciones');
        $data_view->keys_data = array('ks_cliente_empleado_id', 'em_empleado_nombre_completo', 'em_empleado_rfc', 'em_empleado_nss');
        $data_view->key_actions = 'acciones';
        $data_view->namespace_model = 'gamboamartin\\ks_ops\\models';
        $data_view->name_model_children = 'ks_cliente_empleado';

        $contenido_table = $this->contenido_children(data_view: $data_view, next_accion: __FUNCTION__,
            not_actions: $not_actions);
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al obtener tbody', data: $contenido_table, header: $header, ws: $ws);
        }

        return $contenido_table;
    }

    public function asigna_empleado_bd(bool $header, bool $ws = false): array|stdClass
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

        $em_empleado = new em_empleado($this->link);
        $em_empleado->registro = $_POST;
        $em_empleado->registro['com_cliente_id'] = $this->registro_id;
        $proceso = $em_empleado->alta_bd();
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al dar de alta empleado', data: $proceso, header: $header,
                ws: $ws);
        }

        $this->link->commit();

        if ($header) {
            $this->retorno_base(registro_id: $this->registro_id, result: $proceso,
                siguiente_view: "asigna_empleado", ws: $ws);
        }
        if ($ws) {
            header('Content-Type: application/json');
            echo json_encode($proceso, JSON_THROW_ON_ERROR);
            exit;
        }
        $proceso->siguiente_view = "asigna_empleado";

        return $proceso;
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
            "com_cliente.telefono", 'com_tipo_cliente.descripcion', 'cat_sat_actividad_economica.descripcion');

        $datatables = new stdClass();
        $datatables->columns = $columns;
        $datatables->filtro = $filtro;

        return $datatables;
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
            $error = $this->errores->error(mensaje: 'Error al recuperar link asigna_agente_bd', data: $link);
            print_r($error);
            exit;
        }
        $this->link_com_rel_agente_cliente_bd = $link;

        $link = $this->obj_link->get_link(seccion: "com_cliente", accion: "asigna_contacto_bd");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al recuperar link asigna_contacto_bd', data: $link);
            print_r($error);
            exit;
        }
        $this->link_asigna_contacto_bd = $link;

        $link = $this->obj_link->get_link(seccion: "com_cliente", accion: "comisiones_generales_bd");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al recuperar link comisiones_generales_bd', data: $link);
            print_r($error);
            exit;
        }
        $this->link_comisiones_generales_bd = $link;

        $link = $this->obj_link->get_link(seccion: "com_cliente", accion: "asigna_empleado_bd");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al recuperar link asigna_empleado_bd', data: $link);
            print_r($error);
            exit;
        }
        $this->link_asigna_empleado_bd = $link;

        return $link;
    }

    public function init_selects_inputs(): array
    {

        $keys_selects = $this->init_selects(keys_selects: array(), key: "com_tipo_cliente_id", label: "Tipo de Cliente",
            cols: 12);

        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al integrar selector', data: $keys_selects);
        }

        $keys_selects = $this->init_selects(keys_selects: $keys_selects, key: "dp_cp_id", label: "CP",
            cols: 6, con_registros: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al integrar selector', data: $keys_selects);
        }

        $keys_selects = $this->init_selects(keys_selects: $keys_selects, key: "dp_colonia_postal_id", label: "Colonia",
            cols: 6, con_registros: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al integrar selector', data: $keys_selects);
        }

        $keys_selects = $this->init_selects(keys_selects: $keys_selects, key: "dp_calle_pertenece_id", label: "Calle",
            cols: 6, con_registros: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al integrar selector', data: $keys_selects);
        }

        $keys_selects = $this->init_selects(keys_selects: $keys_selects, key: "org_puesto_id", label: "Puesto",
            cols: 6);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al integrar selector', data: $keys_selects);
        }

        $keys_selects = $this->init_selects(keys_selects: $keys_selects, key: "em_registro_patronal_id", label: "Registro Patronal",
            cols: 6);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al integrar selector', data: $keys_selects);
        }

        $keys_selects = $this->init_selects(keys_selects: $keys_selects, key: "com_agente_id", label: "Agente",
            cols: 12);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al integrar selector', data: $keys_selects);
        }

        $keys_selects = $this->init_selects(keys_selects: $keys_selects, key: "com_tipo_contacto_id", label: "Tipo de Contacto",
            cols: 12);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al integrar selector', data: $keys_selects);
        }

        $keys_selects = $this->init_selects(keys_selects: $keys_selects, key: "cat_sat_regimen_fiscal_id",
            label: "Régimen Fiscal", cols: 12);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al integrar selector', data: $keys_selects);
        }

        $keys_selects = $this->init_selects(keys_selects: $keys_selects, key: "cat_sat_periodicidad_id",
            label: "Periodicidad", cols: 12);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al integrar selector', data: $keys_selects);
        }

        $keys_selects = $this->init_selects(keys_selects: $keys_selects, key: "cat_sat_tipo_persona_id",
            label: "Tipo Persona", cols: 12);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al integrar selector', data: $keys_selects);
        }

        $keys_selects['cat_sat_regimen_fiscal_id']->columns_descripcion_select = array(
            'cat_sat_regimen_fiscal_codigo', 'cat_sat_regimen_fiscal_descripcion');


        $keys_selects = $this->init_selects(keys_selects: $keys_selects, key: "dp_pais_id", label: "País");
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al integrar selector', data: $keys_selects);
        }

        $keys_selects['dp_pais_id']->key_descripcion_select = 'dp_pais_descripcion';


        $keys_selects = $this->init_selects(keys_selects: $keys_selects, key: "dp_estado_id", label: "Estado",
            con_registros: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al integrar selector', data: $keys_selects);
        }

        $keys_selects['dp_estado_id']->key_descripcion_select = 'dp_estado_descripcion';


        $keys_selects = $this->init_selects(keys_selects: $keys_selects, key: "dp_municipio_id", label: "Municipio",
            con_registros: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al integrar selector', data: $keys_selects);
        }

        $keys_selects['dp_municipio_id']->key_descripcion_select = 'dp_municipio_descripcion';

        $keys_selects = $this->init_selects(keys_selects: $keys_selects, key: "cat_sat_uso_cfdi_id", label: "Uso CFDI",
            cols: 12);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al integrar selector', data: $keys_selects);
        }

        $keys_selects['cat_sat_uso_cfdi_id']->columns_ds = array(
            'cat_sat_uso_cfdi_codigo', 'cat_sat_uso_cfdi_descripcion');

        $keys_selects = $this->init_selects(keys_selects: $keys_selects, key: "cat_sat_metodo_pago_id",
            label: "Método de Pago");
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al integrar selector', data: $keys_selects);
        }

        $keys_selects['cat_sat_metodo_pago_id']->columns_ds = array(
            'cat_sat_metodo_pago_codigo', 'cat_sat_metodo_pago_descripcion');

        $keys_selects = $this->init_selects(keys_selects: $keys_selects, key: "cat_sat_forma_pago_id",
            label: "Forma Pago");
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al integrar selector', data: $keys_selects);
        }

        $keys_selects['cat_sat_forma_pago_id']->columns_ds = array(
            'cat_sat_forma_pago_codigo', 'cat_sat_forma_pago_descripcion');

        $keys_selects = $this->init_selects(keys_selects: $keys_selects, key: "cat_sat_tipo_de_comprobante_id",
            label: "Tipo de Comprobante");
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al integrar selector', data: $keys_selects);
        }

        $keys_selects['cat_sat_tipo_de_comprobante_id']->columns_ds = array(
            'cat_sat_tipo_de_comprobante_codigo', 'cat_sat_tipo_de_comprobante_descripcion');


        $keys_selects = $this->init_selects(keys_selects: $keys_selects, key: "cat_sat_moneda_id",
            label: "Moneda");
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al integrar selector', data: $keys_selects);
        }

        $keys_selects['cat_sat_moneda_id']->columns_ds = array(
            'cat_sat_moneda_codigo', 'cat_sat_moneda_descripcion');

        return $keys_selects;
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
                cols: 12, con_registros: true, id_selected: -1, link: $this->link, label: 'Giro/Actividad');
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

        $ultimo_registro = (new ks_comision_general($this->link))->ultimo_registro_x_cliente(com_cliente_id: $this->registro_id);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener datos', data: $ultimo_registro, header: $header, ws: $ws);
        }

        $this->row_upd->fecha_inicio = date('Y-m-d');
        $this->row_upd->fecha_fin = date('Y-m-d');

        if (!empty($ultimo_registro)) {
            $fecha_fin = new DateTime($ultimo_registro['ks_comision_general_fecha_fin']);
            $fecha_inicio = new DateTime($ultimo_registro['ks_comision_general_fecha_inicio']);

            $fecha_fin->modify('+1 day');
            $fecha_inicio->modify('+1 year');

            $this->row_upd->fecha_inicio = $fecha_fin->format('Y-m-d');
            $this->row_upd->fecha_fin = $fecha_inicio->format('Y-m-d');
        }


        $base = $this->base_upd(keys_selects: $keys_selects, params: array(), params_ajustados: array());
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar base', data: $base, header: $header, ws: $ws);
        }

        $button = $this->html->button_href(accion: 'modifica', etiqueta: 'Ir a Cliente',
            registro_id: $this->registro_id, seccion: $this->tabla, style: 'warning', params: array());
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al generar link', data: $button);
        }

        $this->button_com_cliente_modifica = $button;

        $data_view = new stdClass();
        $data_view->names = array('Id', 'Porcentaje', 'Fecha Inicio', 'Fecha Fin', 'Acciones');
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

    public function comisiones_generales_bd(bool $header, bool $ws = false): array|stdClass
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

        $registro['com_cliente_id'] = $this->registro_id;
        $registro['porcentaje'] = $_POST['porcentaje'];
        $registro['fecha_inicio'] = $_POST['fecha_inicio'];
        $registro['fecha_fin'] = $_POST['fecha_fin'];

        $com_contacto = new ks_comision_general($this->link);
        $com_contacto->registro = $registro;
        $proceso = $com_contacto->alta_bd();
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al dar de alta comision general', data: $proceso, header: $header,
                ws: $ws);
        }

        $this->link->commit();

        if ($header) {
            $this->retorno_base(registro_id: $this->registro_id, result: $proceso,
                siguiente_view: "comisiones_generales", ws: $ws);
        }
        if ($ws) {
            header('Content-Type: application/json');
            echo json_encode($proceso, JSON_THROW_ON_ERROR);
            exit;
        }
        $proceso->siguiente_view = "comisiones_generales";

        return $proceso;
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
                cols: 12, con_registros: true, id_selected: $com_cliente->cat_sat_actividad_economica_id,
                link: $this->link, label: 'Giro/Actividad');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al obtener cat_sat_actividad_economica_id',
                data: $cat_sat_actividad_economica_id);
        }

        $cat_sat_periodicidad_id = (
        new selec_html(html: $this->html_base))->select_cat_sat_periodicidad_id(
                cols: 12, con_registros: true, id_selected: $com_cliente->cat_sat_periodicidad_id,
                link: $this->link);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al obtener cat_sat_periodicidad_id',
                data: $cat_sat_actividad_economica_id);
        }

        $this->inputs->cat_sat_actividad_economica_id = $cat_sat_actividad_economica_id;
        $this->inputs->cat_sat_periodicidad_id = $cat_sat_periodicidad_id;

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

    public function reporte_empleados(bool $header, bool $ws = false): array|stdClass
    {
        $com_cliente = (new com_cliente($this->link))->registro(registro_id: $this->registro_id);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al obtener cliente', data: $com_cliente);
        }

        $filtro['com_cliente_id'] = $this->registro_id;
        $ks_cliente_empleado = (new ks_cliente_empleado($this->link))->filtro_and(filtro: $filtro);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al obtener empleado por cliente', data: $ks_cliente_empleado);
            print_r($error);
            die('Error');
        }

        $data = array();

        foreach ($ks_cliente_empleado->registros as $empleado) {
            $filtro = array('em_empleado_id' => $empleado['em_empleado_id']);
            $order = array('em_cuenta_bancaria_id' => 'DESC');
            $em_cuenta_bancaria = (new em_cuenta_bancaria($this->link))->filtro_and(filtro: $filtro, order: $order);
            if (errores::$error) {
                $error = $this->errores->error(mensaje: 'Error al obtener cuenta bancaria', data: $em_cuenta_bancaria);
                print_r($error);
                die('Error');
            }

            $clabe = "-";
            $sucursal = "-";

            if ($em_cuenta_bancaria->n_registros > 0) {
                $clabe = $em_cuenta_bancaria->registros[0]['em_cuenta_bancaria_clabe'];
                $sucursal_id = $em_cuenta_bancaria->registros[0]['em_cuenta_bancaria_bn_sucursal_id'];

                $bn_sucursal = (new bn_sucursal($this->link))->registro(registro_id: $sucursal_id);
                if (errores::$error) {
                    $error = $this->errores->error(mensaje: 'Error al obtener sucursal', data: $bn_sucursal);
                    print_r($error);
                    die('Error');
                }

                $sucursal = $bn_sucursal['bn_sucursal_descripcion'];
            }

            $data[] = [
                $empleado['em_empleado_codigo'],
                $empleado['em_empleado_nss'],
                $empleado['em_empleado_rfc'],
                $empleado['em_empleado_curp'],
                $empleado['em_empleado_nombre_completo'],
                "",
                $sucursal,
                "-",
                $clabe,
                $empleado['em_empleado_correo']
            ];
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('ADMON. PENSION');

        $sheet->setCellValue('B2', 'CLIENTE');
        $sheet->setCellValue('B3', 'PERIODO');
        $sheet->setCellValue('B4', 'COMISION');
        $sheet->setCellValue('B5', 'IVA');
        $sheet->setCellValue('B6', 'ESQUEMA');

        $sheet->setCellValue('C2', $com_cliente['com_cliente_razon_social']);
        $sheet->setCellValue('C3', '-');
        $sheet->setCellValue('C4', '0.11');
        $sheet->setCellValue('C5', '0.16');
        $sheet->setCellValue('C6', 'ADMON. FONDO');

        $sheet->mergeCells('C2:E2');
        $sheet->mergeCells('C3:E3');
        $sheet->mergeCells('C4:E4');
        $sheet->mergeCells('C5:E5');
        $sheet->mergeCells('C6:E6');

        $sheet->getStyle('B2:E6')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '800000'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        $sheet->getStyle('C4:C5')
            ->getNumberFormat()->setFormatCode('0.00%');

        $sheet->getStyle('C2:E6')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '000000'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F2DCDB'],
            ]
        ]);

        $cabecera = [
            'CLAVE EMPLEADO', 'NSS', 'RFC', 'CURP', 'NOMBRE COMPLETO', 'NETO A DEPOSITAR', 'BANCO', 'CUENTA',
            'CLABE INTERBANCARIA', 'EMAIL'
        ];

        $columna_inicio = 'A';
        $fila_inicio = 8;
        $columna = $columna_inicio;

        foreach ($cabecera as $encabezado) {
            $sheet->setCellValue($columna . $fila_inicio, $encabezado);
            $sheet->getColumnDimension($columna)->setAutoSize(true);
            $columna++;
        }

        $fila = $fila_inicio + 1;
        $columna = $columna_inicio;

        foreach ($data as $valor) {
            foreach ($cabecera as $indice => $encabezado) {
                $sheet->setCellValue($columna . $fila, $valor[$indice]);
                $columna++;
            }
            $fila++;
            $columna = $columna_inicio;
        }



        $sheet->getStyle("A8:J8")->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '800000'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        $ultimaFila = $fila - 1;

        $sheet->setCellValue('F' . ($ultimaFila + 1), '=SUM(F' . ($fila_inicio + 1) . ':F' . $ultimaFila . ')');

        $sheet->setCellValue('E' . ($ultimaFila + 3), 'NÓMINA');
        $sheet->setCellValue('E' . ($ultimaFila + 4), 'COMISIÓN');
        $sheet->setCellValue('E' . ($ultimaFila + 5), 'SUBTOTAL');
        $sheet->setCellValue('E' . ($ultimaFila + 6), 'IVA 16%');
        $sheet->setCellValue('E' . ($ultimaFila + 7), 'TOTAL');

        $sheet->setCellValue('F' . ($ultimaFila + 3), '=F' . ($ultimaFila + 1));
        $sheet->setCellValue('F' . ($ultimaFila + 4), '=F' . ($ultimaFila + 3) . '* C4');
        $sheet->setCellValue('F' . ($ultimaFila + 5), '=F' . ($ultimaFila + 3) . '+F' . ($ultimaFila + 4));
        $sheet->setCellValue('F' . ($ultimaFila + 6), '=F' . ($ultimaFila + 5) . '* C5');
        $sheet->setCellValue('F' . ($ultimaFila + 7), '=F' . ($ultimaFila + 5) . '+F' . ($ultimaFila + 6));

        $sheet->getStyle('F' . ($ultimaFila + 1) . ':F' . ($ultimaFila + 1))->applyFromArray([
            'font' => [
                'bold' => true,
            ],
        ]);

        $sheet->getStyle('E' . ($ultimaFila + 2) . ':F' . ($ultimaFila + 7))->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->getStyle('F' . ($ultimaFila + 3) . ':F' . ($ultimaFila + 6))->applyFromArray([
            'font' => [
                'bold' => false,
            ],
        ]);

        $sheet->getStyle('F' . ($fila_inicio + 1) . ':F' . ($ultimaFila + 7))
            ->getNumberFormat()->setFormatCode('_-$* #,##0.00_ ;_-$* #,##0.00_ ;_-$* "-"??_ ;_(@_)');

        $sheet->getStyle('A' . ($fila_inicio + 1) . ':J' . $ultimaFila)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        $sheet->getStyle('E' . ($ultimaFila + 3) . ':F' . ($ultimaFila + 7))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        $name = "PAGO ADMON. FONDO_" . $com_cliente['com_cliente_razon_social'];

        $out = (new exportador\output())->genera_salida_xls(header: $header, libro: $spreadsheet, name: $name,
            path_base: $this->path_base);
        if (isset($out['error'])) {
            $error = $this->errores->error('Error al aplicar generar salida', $out);
            if (!$header) {
                return $error;
            }
            print_r($error);
            die('Error');
        }

        if (!$header) {
            return $out;
        }
        exit;
    }
}
