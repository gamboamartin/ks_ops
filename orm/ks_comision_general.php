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

    public function alta_bd(array $keys_integra_ds = array('codigo', 'descripcion')): array|stdClass
    {
        $this->registro = $this->inicializa_campos($this->registro);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al inicializar campo base', data: $this->registro);
        }

        $r_alta_bd = parent::alta_bd(keys_integra_ds: $keys_integra_ds);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al dar de alta correo', data: $r_alta_bd);
        }

        return $r_alta_bd;
    }

    protected function inicializa_campos(array $registros): array
    {
        $registros['codigo'] = $this->get_codigo_aleatorio();
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error generar codigo', data: $registros);
        }

        $registros['descripcion'] = $registros['codigo'] . '-' . $registros['com_cliente_id'];

        if (!array_key_exists('fecha_inicio', $registros)) {
            $registros['fecha_inicio'] = '1900-01-01';
        }

        if (!array_key_exists('fecha_inicio', $registros)) {
            $registros['fecha_inicio'] = '2200-01-0';
        }

        return $registros;
    }

}