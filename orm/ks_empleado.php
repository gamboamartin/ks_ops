<?php
namespace gamboamartin\ks_ops\models;
use base\orm\modelo;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class ks_empleado extends modelo {
    public function __construct(PDO $link, array $childrens = array()){
        $tabla = 'ks_empleado';
        $columnas = array($tabla=>false, 'em_empleado'=>$tabla);

        $campos_obligatorios = array('em_empleado_id');

        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, childrens: $childrens);

        $this->NAMESPACE = __NAMESPACE__;

        $this->etiqueta = 'Extension de Empleado';
    }

    final public function alta_bd(array $keys_integra_ds = array('codigo', 'descripcion')): array|stdClass
    {
        $filtro['em_empleado.id'] = $this->registro['em_empleado_id'];
        $existe_empleado = (new ks_empleado(link: $this->link))->existe(filtro: $filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al verificar si existe empleado',data:  $existe_empleado);
        }

        if($existe_empleado){
            return $this->error->error(mensaje: 'Error el empleado ya existe en ks',data:  $filtro);
        }

        $empleado = (new em_empleado(link: $this->link))->registro(registro_id: $this->registro['em_empleado_id'],
            columnas_en_bruto: true,retorno_obj: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener empleado',data:  $empleado);
        }

        $this->registro['codigo'] = $empleado->codigo;
        $this->registro['descripcion'] = $empleado->descripcion;

        $r_alta_bd = parent::alta_bd();
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al insertar empleado',data:  $r_alta_bd);
        }
        return $r_alta_bd;

    }

    final public function id_by_empleado(int $em_empleado_id)
    {
        $filtro['em_empleado.id'] = $em_empleado_id;
        $ks_empleado = $this->filtro_and(filtro: $filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener ks_empleado',data:  $ks_empleado);
        }

        return (int)$ks_empleado->registros[0]['ks_empleado_id'];
    }

    final public function modifica_bd(array $registro, int $id, bool $reactiva = false): array|stdClass
    {
        $ks_empleado = $this->registro(registro_id: $id,columnas_en_bruto: true,retorno_obj: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener ks_empleado',data:  $ks_empleado);
        }

        $em_empleado_id = $ks_empleado->em_empleado_id;

        if(isset($registro['em_empleado_id'])){
            $registro['em_empleado_id'] = $em_empleado_id;
        }

        $r_modifica_bd = parent::modifica_bd(registro: $registro, id: $id);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al modificar empleado',data:  $r_modifica_bd);
        }

        return $r_modifica_bd;
    }


}