<?php
namespace gamboamartin\ks_ops\models;
use gamboamartin\cat_sat\models\cat_sat_tipo_persona;
use gamboamartin\direccion_postal\models\dp_municipio;
use gamboamartin\errores\errores;
use gamboamartin\organigrama\models\org_departamento;
use PDO;
use stdClass;

class org_puesto extends \gamboamartin\organigrama\models\org_puesto {

    final public function alta_bd(array $keys_integra_ds = array('codigo', 'descripcion')): array|stdClass
    {
        $predefinidos = $this->valores_predeterminados();
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener valores predeterminados',data:  $predefinidos);
        }

        $alta_bd = parent::alta_bd(keys_integra_ds: $keys_integra_ds);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al insertar cliente',data:  $alta_bd);
        }

        return $alta_bd;
    }

    public function valores_predeterminados() : array
    {
        $this->registro['org_departamento_id'] = (new org_departamento(link: $this->link))->id_predeterminado();
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener el departamento predeterminado', data: $this->registro);
        }

        return $this->registro;
    }
}