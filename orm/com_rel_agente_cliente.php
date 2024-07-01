<?php
namespace gamboamartin\ks_ops\models;
use base\orm\_modelo_parent;
use PDO;

class com_rel_agente_cliente extends _modelo_parent{
    public function __construct(PDO $link, array $childrens = array()){
        $tabla = 'com_rel_agente_cliente';
        $columnas = array($tabla=>false, 'com_agente'=>$tabla, 'com_cliente'=>$tabla);
        $campos_obligatorios = array('com_agente_id','com_cliente_id');

        $columnas_extra = array();

        $atributos_criticos =  array('com_cliente_id','com_agente_id');

        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, columnas_extra: $columnas_extra, childrens: $childrens,
            atributos_criticos: $atributos_criticos);

        $this->NAMESPACE = __NAMESPACE__;

        $this->etiqueta = 'Relacion Agente Cliente';
    }
}