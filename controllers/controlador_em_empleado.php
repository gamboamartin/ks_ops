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

    public function init_selects_inputs(): array
    {
        $keys_selects = parent::init_selects_inputs();
        $keys_selects['em_registro_patronal_id']->cols = 12;
        $keys_selects['cat_sat_regimen_fiscal_id']->cols = 12;

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

        return $keys_selects;
    }

    public function cuenta_bancaria(bool $header = true, bool $ws = false, array $not_actions = array()): array|string
    {
        $seccion = "em_cuenta_bancaria";

        $data_view = new stdClass();
        $data_view->names = array('Id', 'Banco Sucursal', 'Descripción', 'Acciones');
        $data_view->keys_data = array($seccion . "_id", 'bn_sucursal_descripcion', $seccion . '_descripcion');
        $data_view->key_actions = 'acciones';
        $data_view->namespace_model = 'gamboamartin\\empleado\\models';
        $data_view->name_model_children = $seccion;

        $contenido_table = $this->contenido_children(data_view: $data_view, next_accion: __FUNCTION__,
            not_actions: $not_actions);
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al obtener tbody', data: $contenido_table, header: $header, ws: $ws);
        }

        return $contenido_table;
    }

}
