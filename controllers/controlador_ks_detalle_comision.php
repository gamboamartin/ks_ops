<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\ks_ops\controllers;

use base\controller\controler;
use gamboamartin\errores\errores;
use gamboamartin\ks_ops\html\ks_cliente_html;
use gamboamartin\ks_ops\html\ks_comision_general_html;
use gamboamartin\ks_ops\html\ks_detalle_comision_html;
use gamboamartin\ks_ops\models\ks_cliente;
use gamboamartin\ks_ops\models\ks_comision_general;
use gamboamartin\ks_ops\models\ks_detalle_comision;
use gamboamartin\system\_ctl_base;
use gamboamartin\system\links_menu;
use gamboamartin\template\html;
use PDO;
use stdClass;

class controlador_ks_detalle_comision extends _ctl_base {

    public array|stdClass $keys_selects = array();

    public function __construct(PDO      $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass())
    {
        $modelo = new ks_detalle_comision(link: $link);
        $html = new ks_detalle_comision_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id: $this->registro_id);

        $datatables = $this->init_datatable();
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al inicializar datatable', data: $datatables);
            print_r($error);
            die('Error');
        }

        parent::__construct(html: $html, link: $link, modelo: $modelo, obj_link: $obj_link, datatables: $datatables,
            paths_conf: $paths_conf);

        $init_controladores = $this->init_controladores(paths_conf: $paths_conf);
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al inicializar controladores',data:  $init_controladores);
            print_r($error);
            die('Error');
        }

        $configuraciones = $this->init_configuraciones();
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al inicializar configuraciones', data: $configuraciones);
            print_r($error);
            die('Error');
        }

    }

    public function alta(bool $header, bool $ws = false): array|string
    {
        $r_alta = $this->init_alta();
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al inicializar alta', data: $r_alta, header: $header, ws: $ws);
        }

        $keys_selects = $this->init_selects_inputs();
        if (errores::$error) {return $this->errores->error(mensaje: 'Error al inicializar selects', data: $keys_selects);
        }

        $this->row_upd->fecha_inicio = date('Y-m-d');
        $this->row_upd->fecha_fin = date('Y-m-d');

        $inputs = $this->inputs(keys_selects: $keys_selects);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al obtener inputs', data: $inputs);
        }

        return $r_alta;
    }

    protected function campos_view(): array
    {
        $keys = new stdClass();
        $keys->inputs = array('porcentaje');
        $keys->fechas = array('fecha_inicio', 'fecha_fin');
        $keys->selects = array();

        $init_data = array();
        $init_data['ks_comision_general'] = "gamboamartin\\ks_ops";
        $init_data['com_agente'] = "gamboamartin\\comercial";
        $campos_view = $this->campos_view_base(init_data: $init_data, keys: $keys);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al inicializar campo view', data: $campos_view);
        }

        return $campos_view;
    }

    private function init_configuraciones(): controler
    {
        $this->titulo_lista = 'Registro de Detalles de Comisiones';

        return $this;
    }

    private function init_controladores(stdClass $paths_conf): controler
    {
        return $this;
    }

    private function init_selects(array $keys_selects, string $key, string $label, int|null $id_selected = -1, int $cols = 6,
                                  bool  $con_registros = true, array $filtro = array(), array $columns_ds =  array()): array
    {
        $keys_selects = $this->key_select(cols: $cols, con_registros: $con_registros, filtro: $filtro, key: $key,
            keys_selects: $keys_selects, id_selected: $id_selected, label: $label, columns_ds: $columns_ds);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        return $keys_selects;
    }

    public function init_selects_inputs(): array{

        $keys_selects = $this->init_selects(keys_selects: array(), key: "ks_comision_general_id", label: "Comisión General",
            cols: 12,columns_ds: array('com_cliente_razon_social', 'ks_comision_general_porcentaje'));
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al integrar selector',data:  $keys_selects);
        }

        $keys_selects = $this->init_selects(keys_selects: $keys_selects, key: "com_agente_id", label: "Agente",
            cols: 8,columns_ds: array('com_agente_descripcion'));
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al integrar selector',data:  $keys_selects);
        }

        return $keys_selects;
    }

    final public function init_datatable(): stdClass
    {
        $datatables = new stdClass();
        $datatables->columns = array();
        $datatables->columns['ks_detalle_comision_id']['titulo'] = 'Id';
        $datatables->columns['com_cliente_razon_social']['titulo'] = 'Cliente';
        $datatables->columns['com_agente_descripcion']['titulo'] = 'Agente';
        $datatables->columns['ks_detalle_comision_porcentaje']['titulo'] = 'Porcentaje';
        $datatables->columns['ks_detalle_comision_fecha_inicio']['titulo'] = 'Fecha Inicio';
        $datatables->columns['ks_detalle_comision_fecha_fin']['titulo'] = 'Fecha Fin';

        $datatables->filtro = array();
        $datatables->filtro[] = 'ks_detalle_comision.id';
        $datatables->filtro[] = 'com_agente.descripcion';

        return $datatables;
    }

    public function get_ultimo_registro(bool $header, bool $ws = true): array|stdClass
    {
        if (!isset($_GET['ks_comision_general_id'])) {
            return $this->retorno_error(mensaje: 'Error el campo ks_comision_general_id no existe', data: $_GET, header: $header, ws: $ws);
        }

        $ultimo_registro = (new ks_detalle_comision($this->link))->ultimo_registro_x_comision(ks_comision_general_id: $_GET['ks_comision_general_id']);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener datos', data: $ultimo_registro, header: $header, ws: $ws);
        }

        $salida['data'] = $ultimo_registro;

        header('Content-Type: application/json');
        echo json_encode($salida);
        exit;
    }

    protected function key_selects_txt(array $keys_selects): array
    {
        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 4, key: 'porcentaje',
            keys_selects: $keys_selects, place_holder: 'Porcentaje');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6, key: 'fecha_inicio',
            keys_selects: $keys_selects, place_holder: 'Fecha Inicio');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6, key: 'fecha_fin',
            keys_selects: $keys_selects, place_holder: 'Fecha Fin');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        return $keys_selects;
    }

    public function modifica(bool $header, bool $ws = false): array|stdClass
    {
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

        $keys_selects['ks_comision_general_id']->id_selected = $this->registro['ks_comision_general_id'];
        $keys_selects['com_agente_id']->id_selected = $this->registro['com_agente_id'];

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(), params_ajustados: array());
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar base', data: $base, header: $header, ws: $ws);
        }

        return $r_modifica;
    }
}
