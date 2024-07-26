<?php

namespace gamboamartin\ks_ops\models;

use base\orm\_modelo_parent;
use base\orm\modelo;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class ks_detalle_comision extends _modelo_parent
{
    public function __construct(PDO $link, array $childrens = array())
    {
        $tabla = 'ks_detalle_comision';
        $columnas = array($tabla => false, 'com_agente' => $tabla, 'ks_comision_general' => $tabla, 'com_cliente' => 'ks_comision_general');

        $campos_obligatorios = array('com_agente_id', 'ks_comision_general_id');

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

        $this->registro = $this->validaciones($this->registro);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error en validaciones', data: $this->registro);
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

    public function modifica_bd(array $registro, int $id, bool $reactiva = false,
                                array $keys_integra_ds = array('descripcion')): array|stdClass
    {
        $validacion = $this->validaciones(registros: $registro,id: $id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error en validaciones', data: $validacion);
        }

        $modifica = parent::modifica_bd($registro, $id, $reactiva, $keys_integra_ds);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error de validacion', data: $validacion);

        }

        return $modifica;
    }

    protected function validaciones(array $registros, int $id = -1): array
    {
        if (isset($registros['status'])) {
            return $registros;
        }

        $registros = $this->validar_fechas(registros: $registros);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error en validaciones de fechas', data: $registros);
        }

        $ks_comision_general = (new ks_comision_general($this->link))->registro(registro_id: $registros['ks_comision_general_id']);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener comisión general', data: $registros);
        }

        $registros = $this->validar_fechas_inicio_fin($registros, $ks_comision_general);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error en validaciones de fechas inicio fin', data: $registros);
        }

        $registros = $this->validar_porcentaje($registros, $ks_comision_general);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error en validaciones de porcentaje', data: $registros);
        }

        $registros = $this->validar_sumatoria_porcentajes($registros, $ks_comision_general, $id);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error en validaciones de sumatoria de porcentajes', data: $registros);
        }

        return $registros;
    }

    private function validar_fechas(array $registros) : array
    {
        if (isset($registros['fecha_inicio']) && isset($registros['fecha_fin'])) {
            $fecha_inicio = strtotime($registros['fecha_inicio']);
            $fecha_fin = strtotime($registros['fecha_fin']);

            if ($fecha_inicio > $fecha_fin) {
                return $this->error->error(mensaje: 'La fecha de inicio debe ser anterior a la fecha de fin.',
                    data: $registros);
            }
        }

        return $registros;
    }

    private function validar_sumatoria_porcentajes(array $registros, array $ks_comision_general, int $id = -1) : array
    {
        $resto = 0;
        if ($id > 0) {
            $registro = $this->registro(registro_id: $id,columnas: array('ks_detalle_comision_porcentaje'));
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al obtener último registro', data: $registro);
            }

            $resto = $registro['ks_detalle_comision_porcentaje'];
        }

        $sumatoria = $this->sumatoria_porcentajes($registros['ks_comision_general_id']);
        $sumatoria += $registros['porcentaje'] - $resto;
        if ($sumatoria > $ks_comision_general['ks_comision_general_porcentaje']) {
            $mensaje = "La sumatoria de porcentajes $sumatoria% no puede ser mayor a {$ks_comision_general['ks_comision_general_porcentaje']}%";
            return $this->error->error(mensaje: $mensaje, data: $registros);
        }

        return $registros;
    }

    private function validar_fechas_inicio_fin(array $registros, array $ks_comision_general) : array
    {
        if (isset($registros['fecha_inicio'])) {
            $fecha_inicio_general = strtotime($ks_comision_general['ks_comision_general_fecha_inicio']);
            $fecha_inicio_detalle = strtotime($registros['fecha_inicio']);

            if ($fecha_inicio_detalle < $fecha_inicio_general) {
                $fecha_inicio_general = date('Y-m-d', $fecha_inicio_general);
                $fecha_inicio_detalle = date('Y-m-d', $fecha_inicio_detalle);
                $mensaje = "La fecha de inicio $fecha_inicio_detalle del detalle debe ser mayor a la fecha de inicio $fecha_inicio_general de la comisión general";
                return $this->error->error(mensaje: $mensaje, data: $registros);
            }
        }

        if (isset($registros['fecha_fin'])) {
            $fecha_fin_general = strtotime($ks_comision_general['ks_comision_general_fecha_fin']);
            $fecha_fin_detalle = strtotime($registros['fecha_fin']);

            if ($fecha_fin_detalle > $fecha_fin_general) {
                $fecha_fin_general = date('Y-m-d', $fecha_fin_general);
                $fecha_fin_detalle = date('Y-m-d', $fecha_fin_detalle);
                $mensaje = "La fecha de fin $fecha_fin_detalle del detalle debe ser menor a la fecha de fin $fecha_fin_general de la comisión general";
                return $this->error->error(mensaje: $mensaje, data: $registros);
            }
        }

        return $registros;
    }

    private function validar_porcentaje(array $registros, array $ks_comision_general) : array
    {
        if (isset($registros['porcentaje']) && $registros['porcentaje'] <= 0) {
            return $this->error->error(mensaje: 'El porcentaje debe ser mayor a 0', data: $registros);
        }

        if (isset($registros['porcentaje'])) {
            $porcentaje_general = $ks_comision_general['ks_comision_general_porcentaje'];
            $porcentaje_detalle = $registros['porcentaje'];
            if ($porcentaje_detalle > $porcentaje_general) {
                $mensaje = "El porcentaje $porcentaje_detalle% no puede ser mayor a $porcentaje_general%";
                return $this->error->error(mensaje: $mensaje, data: $registros);
            }
        }

        return $registros;
    }

    public function sumatoria_porcentajes(int $ks_comision_general_id): float|array
    {
        $suma = $this->suma(campos: array("suma" => "ks_detalle_comision.porcentaje"),
            filtro: array("ks_comision_general_id" => $ks_comision_general_id));
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener sumatoria de porcentajes', data: $suma);
        }

        return $suma['suma'];
    }

    public function ultimo_registro_x_comision(string $campo, int $valor) : array|stdClass
    {
        $filtro = array($campo => $valor);
        $order = array('ks_detalle_comision_fecha_fin' => 'DESC');
        $registro = $this->filtro_and(filtro: $filtro, order: $order);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener el último registro', data: $registro);
        }

        if ($registro->n_registros == 0) {
            return array();
        }

        return $registro->registros[0];
    }
}