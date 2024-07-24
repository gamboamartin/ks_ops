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
}
