<?php

namespace gamboamartin\ks_ops\models;

use base\orm\_modelo_parent;
use base\orm\modelo;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class ks_cliente_empleado extends _modelo_parent
{
    public function __construct(PDO $link, array $childrens = array())
    {
        $tabla = 'ks_cliente_empleado';
        $columnas = array($tabla => false, 'em_empleado' => $tabla, 'com_cliente' => $tabla);

        $campos_obligatorios = array('em_empleado_id', 'com_cliente_id');

        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, childrens: $childrens);

        $this->NAMESPACE = __NAMESPACE__;

        $this->etiqueta = 'Relación de empleado con cliente';
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

        $registros['descripcion'] = $registros['codigo'] . '-' . $registros['em_empleado_id'] . '-' . $registros['com_cliente'];

        return $registros;
    }

    public function modifica_bd(array $registro, int $id, bool $reactiva = false,
                                array $keys_integra_ds = array('descripcion')): array|stdClass
    {
        $modifica = parent::modifica_bd($registro, $id, $reactiva, $keys_integra_ds);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error de validacion', data: $modifica);

        }

        return $modifica;
    }

}