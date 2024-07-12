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
use gamboamartin\ks_ops\models\ks_cliente;
use gamboamartin\system\_ctl_base;
use gamboamartin\system\links_menu;
use gamboamartin\template\html;
use PDO;
use stdClass;

class controlador_ks_cliente extends _ctl_base {

    public array|stdClass $keys_selects = array();

    public function __construct(PDO      $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass())
    {
        $modelo = new ks_cliente(link: $link);
        $html = new ks_cliente_html(html: $html);
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
        $this->titulo_lista = 'Registro de Clientes';

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
        $datatables->columns['ks_cliente_id']['titulo'] = 'Id';
        $datatables->columns['com_cliente_id']['titulo'] = 'Cliente Id';
        $datatables->columns['com_cliente_codigo']['titulo'] = 'Codigo';
        $datatables->columns['com_cliente_rfc']['titulo'] = 'RFC';
        $datatables->columns['com_cliente_razon_social']['titulo'] = 'Cliente';
        $datatables->columns['cat_sat_actividad_economica_descripcion']['titulo'] = 'Actividad Economica';


        $datatables->filtro = array();
        $datatables->filtro[] = 'ks_cliente.id';
        $datatables->filtro[] = 'cat_sat_actividad_economica.descripcion';
        $datatables->filtro[] = 'com_cliente.codigo';
        $datatables->filtro[] = 'com_cliente.rfc';
        $datatables->filtro[] = 'com_cliente.razon_social';

        return $datatables;
    }




}
