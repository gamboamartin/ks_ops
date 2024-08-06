<?php
namespace gamboamartin\ks_ops\tests\orm;

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


class com_clienteTest extends test {
    public errores $errores;
    private stdClass $paths_conf;
    public function __construct(?string $name = null)
    {
        parent::__construct($name);
        $this->errores = new errores();
        $this->paths_conf = new stdClass();
        $this->paths_conf->generales = '/var/www/html/organigrama/config/generales.php';
        $this->paths_conf->database = '/var/www/html/organigrama/config/database.php';
        $this->paths_conf->views = '/var/www/html/organigrama/config/views.php';
    }

    public function test_registro(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'adm_accion';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 2;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';
        $_GET['registro_id'] = '1';
        $obj = new \gamboamartin\ks_ops\models\com_cliente(link: $this->link);

        $registro_id = 10;
        $resultado = $obj->registro(registro_id: $registro_id,retorno_obj: true);


        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('activo',$resultado->ks_cliente_status);
        $this->assertEquals('activo',$resultado->cat_sat_actividad_economica_status);
        $this->assertArrayHasKey('cat_sat_periodicidad_id',(array)$resultado);
        errores::$error = false;
    }




}

