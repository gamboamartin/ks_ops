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
use gamboamartin\ks_ops\html\ks_empleado_html;
use gamboamartin\ks_ops\models\ks_cliente;
use gamboamartin\ks_ops\models\ks_empleado;
use gamboamartin\system\_ctl_base;
use gamboamartin\system\links_menu;
use gamboamartin\template\html;
use PDO;
use stdClass;

class controlador_ks_empleado extends _ctl_base {

    public array|stdClass $keys_selects = array();

    public function __construct(PDO      $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass())
    {
        $modelo = new ks_empleado(link: $link);
        $html = new ks_empleado_html(html: $html);
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

    private function init_configuraciones(): controler
    {
        $this->titulo_lista = 'Registro de Empleados';

        return $this;
    }

    private function init_controladores(stdClass $paths_conf): controler
    {
        return $this;
    }

    final public function init_datatable(): stdClass
    {
        $datatables = new stdClass();
        $datatables->columns = array();
        $datatables->columns['em_empleado_id']['titulo'] = 'Id';
        $datatables->columns["em_empleado_nombre"]["titulo"] = "Nombre";
        $datatables->columns["em_empleado_nombre"]["campos"] = array("em_empleado_ap","em_empleado_am");
        $datatables->columns["em_empleado_rfc"]["titulo"] = "Rfc";
        $datatables->columns["em_empleado_nss"]["titulo"] = "NSS";
        $datatables->columns["ks_empleado_registro_patronal"]["titulo"] = "Registro Patronal";

        $datatables->filtro = array();
        $datatables->filtro[] = 'em_empleado.id';
        $datatables->filtro[] = 'em_empleado.nombre';
        $datatables->filtro[] = 'em_empleado.ap';
        $datatables->filtro[] = 'em_empleado.am';
        $datatables->filtro[] = 'em_empleado.rfc';
        $datatables->filtro[] = 'em_empleado.nss';

        return $datatables;
    }




}
