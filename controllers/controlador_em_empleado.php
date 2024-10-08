<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\ks_ops\controllers;

use gamboamartin\direccion_postal\models\dp_municipio;
use gamboamartin\errores\errores;
use gamboamartin\ks_ops\html\ks_empleado_html;
use gamboamartin\ks_ops\models\em_empleado;
use gamboamartin\template_1\html;
use PDO;
use stdClass;

final class controlador_em_empleado extends \gamboamartin\empleado\controllers\controlador_em_empleado  {

    public function __construct(PDO      $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass())
    {
        parent::__construct(link: $link, html: $html, paths_conf: $paths_conf);
        $this->modelo = new em_empleado(link: $this->link);

        $this->childrens_data = array();
    }

    protected function campos_view(): array
    {
        $keys = new stdClass();
        $keys->inputs = array('codigo', 'descripcion', 'nombre', 'ap', 'am',  'rfc', 'curp', 'nss', 'salario_diario',
            'salario_diario_integrado','com_sucursal','org_sucursal', 'salario_total', 'numero_exterior', 'numero_interior',
            'registro_patronal', 'cp', 'colonia', 'calle', 'asunto', 'mensaje', 'receptor', 'cc', 'cco');
        $keys->telefonos = array('telefono');
        $keys->fechas = array('fecha_inicio_rel_laboral', 'fecha_inicio', 'fecha_final');
        $keys->emails = array('correo');
        $keys->selects = array();

        $init_data = array();
        $init_data['dp_pais'] = "gamboamartin\\direccion_postal";
        $init_data['dp_estado'] = "gamboamartin\\direccion_postal";
        $init_data['dp_municipio'] = "gamboamartin\\direccion_postal";
        $init_data['dp_cp'] = "gamboamartin\\direccion_postal";
        $init_data['dp_colonia_postal'] = "gamboamartin\\direccion_postal";
        $init_data['dp_calle_pertenece'] = "gamboamartin\\direccion_postal";
        $init_data['cat_sat_regimen_fiscal'] = "gamboamartin\\cat_sat";
        $init_data['cat_sat_tipo_regimen_nom'] = "gamboamartin\\cat_sat";
        $init_data['cat_sat_tipo_jornada_nom'] = "gamboamartin\\cat_sat";
        $init_data['org_puesto'] = "gamboamartin\\organigrama";
        $init_data['em_centro_costo'] = "gamboamartin\\empleado";
        $init_data['em_empleado'] = "gamboamartin\\empleado";
        $init_data['em_registro_patronal'] = "gamboamartin\\empleado";
        $init_data['com_sucursal'] = "gamboamartin\\comercial";
        $init_data['com_cliente'] = "gamboamartin\\comercial";

        $campos_view = $this->campos_view_base(init_data: $init_data, keys: $keys);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al inicializar campo view', data: $campos_view);
        }

        return $campos_view;
    }

    protected function init_datatable(): stdClass
    {
        $columns["em_empleado_id"]["titulo"] = "Id";
        $columns["em_empleado_nombre_completo"]["titulo"] = "Empleado";
        $columns["em_empleado_rfc"]["titulo"] = "Rfc";
        $columns["em_empleado_nss"]["titulo"] = "NSS";
        $columns["em_empleado_n_cuentas_bancarias"]["titulo"] = "Cuentas Bancarias";

        $filtro = array("em_empleado.id","em_empleado.nombre","em_empleado.ap","em_empleado.am","em_empleado.rfc",
            "em_empleado_nombre_completo","em_empleado_nombre_completo_inv", "em_empleado.nss");

        $datatables = new stdClass();
        $datatables->columns = $columns;
        $datatables->filtro = $filtro;

        return $datatables;
    }

    public function init_selects_inputs(): array
    {
        $keys_selects = parent::init_selects_inputs();
        $keys_selects['em_registro_patronal_id']->cols = 12;
        $keys_selects['cat_sat_regimen_fiscal_id']->cols = 12;

        $keys_selects = $this->key_select(cols: 12, con_registros: true, filtro: array(), key: "com_cliente_id",
            keys_selects: $keys_selects, id_selected: -1, label: "Cliente");
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        return $keys_selects;
    }

    protected function key_selects_txt(array $keys_selects): array
    {
        $keys_selects = parent::key_selects_txt($keys_selects);
        $keys_selects['nombre']->cols = 4;
        $keys_selects['ap']->cols = 4;
        $keys_selects['am']->cols = 4;
        $keys_selects['rfc']->cols = 4;
        $keys_selects['curp']->cols = 4;
        $keys_selects['nss']->cols = 4;

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 12, key: 'registro_patronal',
            keys_selects: $keys_selects, place_holder: 'Registro Patronal');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 12, key: 'receptor',
            keys_selects: $keys_selects, place_holder: 'Receptor');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 12, key: 'asunto',
            keys_selects: $keys_selects, place_holder: 'Asunto');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 12, key: 'asunto',
            keys_selects: $keys_selects, place_holder: 'Asunto');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        return $keys_selects;
    }

    public function cuenta_bancaria(bool $header = true, bool $ws = false, array $not_actions = array()): array|string
    {
        $moficia = $this->modifica(header: $header, ws: $ws);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al obtener modifica', data: $moficia);
        }
        $seccion = "em_cuenta_bancaria";

        $data_view = new stdClass();
        $data_view->names = array('Id', 'Banco', 'Clabe', 'Acciones');
        $data_view->keys_data = array($seccion . "_id", 'bn_sucursal_descripcion', 'em_cuenta_bancaria_clabe');
        $data_view->key_actions = 'acciones';
        $data_view->namespace_model = 'gamboamartin\\empleado\\models';
        $data_view->name_model_children = $seccion;

        $contenido_table = $this->contenido_children(data_view: $data_view, next_accion: __FUNCTION__,
            not_actions: $not_actions);
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al obtener tbody', data: $contenido_table, header: $header, ws: $ws);
        }

        $em_cuenta_bancaria_clabe = (new ks_empleado_html(html: $this->html_base))->input_clabe(cols: 12, row_upd: $this->row_upd,
            value_vacio: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar input', data: $em_cuenta_bancaria_clabe);
        }

        $this->inputs->em_cuenta_bancaria_clabe = $em_cuenta_bancaria_clabe;

        return $contenido_table;
    }

    final public function modifica(bool $header, bool $ws = false): array|stdClass
    {
        $r_modifica_bd = parent::modifica(header: $header, ws: $ws);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al obtener template', data: $r_modifica_bd);
        }

        $em_empleado = (new em_empleado(link: $this->link))->registro(registro_id: $this->registro_id, retorno_obj: true);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al obtener com_cliente',
                data: $em_empleado);
        }

        $this->row_upd->registro_patronal = $em_empleado->ks_empleado_registro_patronal;

        $keys_selects = $this->init_selects_inputs();
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al inicializar selects', data: $keys_selects, header: $header,
                ws: $ws);
        }

        $dp_municipio = (new dp_municipio($this->link))->get_municipio($this->registro['em_empleado_dp_municipio_id']);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al obtener dp_municipio', data: $dp_municipio);
        }

        $keys_selects['dp_pais_id']->id_selected = $dp_municipio['dp_pais_id'];

        $keys_selects['dp_estado_id']->con_registros = true;
        $keys_selects['dp_estado_id']->filtro = array("dp_pais.id" => $dp_municipio['dp_pais_id']);
        $keys_selects['dp_estado_id']->id_selected = $dp_municipio['dp_estado_id'];

        $keys_selects['dp_municipio_id']->con_registros = true;
        $keys_selects['dp_municipio_id']->filtro = array("dp_estado.id" => $dp_municipio['dp_estado_id']);
        $keys_selects['dp_municipio_id']->id_selected = $dp_municipio['dp_municipio_id'];

        $keys_selects['cat_sat_regimen_fiscal_id']->id_selected = $this->registro['cat_sat_regimen_fiscal_id'];
        $keys_selects['com_cliente_id']->id_selected = $em_empleado->ks_cliente_empleado_com_cliente_id;

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(), params_ajustados: array());
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar base', data: $base, header: $header, ws: $ws);
        }

        return $r_modifica_bd;

    }

}
