<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\ks_ops\controllers;

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

}
