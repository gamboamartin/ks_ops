<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\ks_ops\controllers;

use stdClass;

final class controlador_com_agente extends \gamboamartin\comercial\controllers\controlador_com_agente {

    public function init_datatable(): stdClass
    {
        $datatables = new stdClass();
        $datatables->columns = array();
        $datatables->columns['com_agente_id']['titulo'] = 'Id';
        $datatables->columns['com_agente_descripcion']['titulo'] = 'Agente';
        $datatables->columns['com_tipo_agente_descripcion']['titulo'] = 'Tipo';
        $datatables->columns['adm_usuario_user']['titulo'] = 'Usuario';

        $datatables->filtro = array();
        $datatables->filtro[] = 'com_agente.id';
        $datatables->filtro[] = 'com_agente.descripcion';
        $datatables->filtro[] = 'adm_usuario.user';
        $datatables->filtro[] = 'com_tipo_agente.descripcion';

        return $datatables;
    }
}
