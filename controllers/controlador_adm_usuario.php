<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\ks_ops\controllers;


final class controlador_adm_usuario extends \gamboamartin\notificaciones\controllers\controlador_adm_usuario {

    public function get_usuario(bool $header, bool $ws = false): array
    {
        header('Content-Type: application/json');
        echo json_encode($_SESSION['usuario_id']);
        exit;
    }


}
