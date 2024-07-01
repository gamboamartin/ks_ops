<?php

namespace gamboamartin\ks_ops\models;

use base\orm\_modelo_parent;
use base\orm\_modelo_parent_sin_codigo;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class com_rel_agente_cliente extends _modelo_parent_sin_codigo
{
    public function __construct(PDO $link, array $childrens = array())
    {
        $tabla = 'com_rel_agente_cliente';
        $columnas = array($tabla => false, 'com_agente' => $tabla, 'com_cliente' => $tabla);
        $campos_obligatorios = array('com_agente_id', 'com_cliente_id');

        $columnas_extra = array();

        $atributos_criticos = array('com_cliente_id', 'com_agente_id');

        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, columnas_extra: $columnas_extra, childrens: $childrens,
            atributos_criticos: $atributos_criticos);

        $this->NAMESPACE = __NAMESPACE__;

        $this->etiqueta = 'Relacion Agente Cliente';
    }

    public function alta_bd(array $keys_integra_ds = array('codigo', 'descripcion')): array|stdClass
    {
        $this->registro = $this->inicializa_campos($this->registro);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al inicializar campo base', data: $this->registro);
        }

        $r_alta_bd = parent::alta_bd($keys_integra_ds);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al insertar autorizante', data: $r_alta_bd);
        }
        return $r_alta_bd;
    }


    protected function inicializa_campos(array $registros): array
    {
        $registros['codigo'] = $this->get_codigo_aleatorio(longitud: 12);
        $registros['descripcion'] = $registros['codigo'];

        return $registros;
    }
}