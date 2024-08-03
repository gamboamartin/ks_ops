<?php
namespace gamboamartin\ks_ops\tests\controllers;

use gamboamartin\comercial\controllers\controlador_com_cliente;
use gamboamartin\comercial\controllers\controlador_com_sucursal;
use gamboamartin\comercial\models\com_cliente;
use gamboamartin\comercial\test\base_test;
use gamboamartin\errores\errores;
use gamboamartin\ks_ops\controllers\controlador_com_agente;
use gamboamartin\template_1\html;

use gamboamartin\test\liberator;
use gamboamartin\test\test;

use html\com_sucursal_html;

use stdClass;


class controlador_com_agenteTest extends test {
    public errores $errores;
    private stdClass $paths_conf;
    public function __construct(?string $name = null)
    {
        parent::__construct($name);
        $this->errores = new errores();
        $this->paths_conf = new stdClass();
        $this->paths_conf->generales = '/var/www/html/ks_ops/config/generales.php';
        $this->paths_conf->database = '/var/www/html/ks_ops/config/database.php';
        $this->paths_conf->views = '/var/www/html/ks_ops/config/views.php';
    }

    public function test_init_datatable(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'adm_accion';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 2;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';
        $_GET['registro_id'] = '1';
        $ctl = new controlador_com_agente(link: $this->link, paths_conf: $this->paths_conf);
       // $ctl = new liberator($ctl);

        $resultado = $ctl->init_datatable();
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        errores::$error = false;
    }




}

