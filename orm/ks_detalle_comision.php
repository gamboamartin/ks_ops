<?php
namespace gamboamartin\ks_ops\models;
use base\orm\_modelo_parent;
use base\orm\modelo;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class ks_detalle_comision extends _modelo_parent {
    public function __construct(PDO $link, array $childrens = array()){
        $tabla = 'ks_detalle_comision';
        $columnas = array($tabla=>false, 'com_agente'=> $tabla, 'ks_comision_general'=> $tabla, 'com_cliente'=> 'ks_comision_general');

        $campos_obligatorios = array('com_agente_id','ks_comision_general_id');

        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, childrens: $childrens);

        $this->NAMESPACE = __NAMESPACE__;

        $this->etiqueta = 'Detalle Comisión';
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

        $registros['descripcion'] = $registros['codigo'] . '-' . $registros['ks_comision_general_id'];

        if (!array_key_exists('fecha_inicio', $registros)) {
            $registros['fecha_inicio'] = '1900-01-01';
        }

        if (!array_key_exists('fecha_inicio', $registros)) {
            $registros['fecha_inicio'] = '2200-01-0';
        }

        return $registros;
    }

}