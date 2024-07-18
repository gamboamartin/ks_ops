<?php
namespace gamboamartin\ks_ops\models;
use base\orm\_modelo_parent;
use base\orm\modelo;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class ks_comision_general extends _modelo_parent {
    public function __construct(PDO $link, array $childrens = array()){
        $tabla = 'ks_comision_general';
        $columnas = array($tabla=>false, 'com_cliente'=> $tabla);

        $campos_obligatorios = array('com_cliente_id');

        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, childrens: $childrens);

        $this->NAMESPACE = __NAMESPACE__;

        $this->etiqueta = 'Comisión General';
    }
}