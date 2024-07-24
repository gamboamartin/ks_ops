<?php
namespace gamboamartin\ks_ops\models;
use gamboamartin\cat_sat\models\cat_sat_tipo_persona;
use gamboamartin\direccion_postal\models\dp_municipio;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class em_empleado extends \gamboamartin\empleado\models\em_empleado {

    public function transacciona_em_rel_empleado_sucursal(array $data, int $em_empleado_id): array|stdClass {
        return array();
    }

}