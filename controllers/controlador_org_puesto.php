<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\ks_ops\controllers;
use gamboamartin\ks_ops\models\org_puesto;
use gamboamartin\template_1\html;
use PDO;
use stdClass;

final class controlador_org_puesto extends \gamboamartin\organigrama\controllers\controlador_org_puesto {

    public function __construct(PDO      $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass())
    {
        parent::__construct(link: $link, html: $html, paths_conf: $paths_conf);
        $this->modelo = new org_puesto(link: $this->link);

        $this->childrens_data = array();
    }


}
