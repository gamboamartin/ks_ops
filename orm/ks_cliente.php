<?php
namespace gamboamartin\ks_ops\models;
use base\orm\modelo;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class ks_cliente extends modelo {
    public function __construct(PDO $link, array $childrens = array()){
        $tabla = 'ks_cliente';
        $columnas = array($tabla=>false, 'com_cliente'=>$tabla, 'cat_sat_actividad_economica'=>$tabla);

        $campos_obligatorios = array('com_cliente_id','cp','cat_sat_actividad_economica_id');

        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, childrens: $childrens);

        $this->NAMESPACE = __NAMESPACE__;

        $this->etiqueta = 'Extension de Clientes';
    }

    final public function alta_bd(array $keys_integra_ds = array('codigo', 'descripcion')): array|stdClass
    {
        $filtro['com_cliente.id'] = $this->registro['com_cliente_id'];
        $existe_cliente = (new ks_cliente(link: $this->link))->existe(filtro: $filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al verificar si existe cliente',data:  $existe_cliente);
        }

        if($existe_cliente){
            return $this->error->error(mensaje: 'Error el cliente ya existe en ks',data:  $filtro);
        }

        $r_alta_bd = parent::alta_bd();
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al insertar cliente',data:  $r_alta_bd);
        }
        return $r_alta_bd;

    }

    final public function modifica_bd(array $registro, int $id, bool $reactiva = false): array|stdClass
    {

        $ks_cliente = $this->registro(registro_id: $id,columnas_en_bruto: true,retorno_obj: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener ks_cliente',data:  $ks_cliente);
        }

        $com_cliente_id = $ks_cliente->com_cliente_id;

        if(isset($registro['com_cliente_id'])){
            $registro['com_cliente_id'] = $com_cliente_id;
        }

        $r_modifica_bd = parent::modifica_bd(registro: $registro, id: $id);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al modificar cliente',data:  $r_modifica_bd);
        }
        return $r_modifica_bd;
    }


}