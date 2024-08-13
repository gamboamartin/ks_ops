<?php
namespace gamboamartin\ks_ops\models;
use gamboamartin\cat_sat\models\cat_sat_tipo_persona;
use gamboamartin\direccion_postal\models\dp_municipio;
use gamboamartin\empleado\models\em_registro_patronal;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class em_empleado extends \gamboamartin\empleado\models\em_empleado {

    public function __construct(PDO $link)
    {
        parent::__construct(link: $link);
        $this->extension_estructura['ks_empleado']['key'] = 'em_empleado_id';
        $this->extension_estructura['ks_empleado']['enlace'] = 'em_empleado';
        $this->extension_estructura['ks_empleado']['key_enlace'] = 'id';

        $this->extension_estructura['ks_cliente_empleado']['key'] = 'em_empleado_id';
        $this->extension_estructura['ks_cliente_empleado']['enlace'] = 'em_empleado';
        $this->extension_estructura['ks_cliente_empleado']['key_enlace'] = 'id';
    }

    final public function alta_bd(array $keys_integra_ds = array('codigo', 'descripcion')): array|stdClass
    {
        $predefinidos = $this->valores_predeterminados();
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener valores predeterminados',data:  $predefinidos);
        }

        $registro_original = $this->registro;
        $alta_bd = parent::alta_bd(keys_integra_ds: $keys_integra_ds);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al insertar cliente',data:  $alta_bd);
        }

        if(isset($registro_original['registro_patronal'])){
            $ks_empleado_ins = $this->ks_empleado_ins(em_empleado_id: $alta_bd->registro_id, registros: $registro_original);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al inicializar valores',data:  $ks_empleado_ins);
            }

            $ks_empleado_alta = (new ks_empleado(link: $this->link))->alta_registro(registro: $ks_empleado_ins);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al insertar ks_cliente',data:  $ks_empleado_alta);
            }
        }

        $ks_cliente_empleado_alta = $this->alta_cliente_empleado(registros: $registro_original,
            em_empleado_id: $alta_bd->registro_id);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al insertar ks_cliente_empleado',data:  $ks_cliente_empleado_alta);
        }

        return $alta_bd;
    }

    public function alta_cliente_empleado(array $registros, int $em_empleado_id)
    {
        $ks_cliente_empleado = (new ks_cliente_empleado(link: $this->link));
        $ks_cliente_empleado->registro['em_empleado_id'] = $em_empleado_id;
        $ks_cliente_empleado->registro['com_cliente_id'] = $registros['com_cliente_id'];

        $operacion = $ks_cliente_empleado->alta_bd();
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al insertar cliente empleado',data:  $operacion);
        }

        return $operacion;
    }

    public function valores_predeterminados() : array
    {
        $this->registro['em_registro_patronal_id'] = (new em_registro_patronal(link: $this->link))->id_predeterminado();
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener id registro patronal predeterminado', data: $this->registro);
        }

        return $this->registro;
    }

    private function ks_empleado_ins(int $em_empleado_id, array $registros): array
    {
        $empleado = (new em_empleado(link: $this->link))->registro(registro_id: $em_empleado_id);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener registro empleado',data: $empleado);
        }

        $ks_empleado_ins['em_empleado_id'] = $em_empleado_id;
        $ks_empleado_ins['codigo'] = $empleado['em_empleado_codigo'];
        $ks_empleado_ins['descripcion'] = $empleado['em_empleado_descripcion'];
        $ks_empleado_ins['descripcion_select'] = $empleado['em_empleado_descripcion_select'];
        $ks_empleado_ins['alias'] = $empleado['em_empleado_alias'];
        $ks_empleado_ins['codigo_bis'] = $empleado['em_empleado_codigo_bis'];
        $ks_empleado_ins['registro_patronal'] = $registros['registro_patronal'];

        return $ks_empleado_ins;
    }

    final public function modifica_bd(array $registro, int $id, bool $reactiva = false,
                                      array $keys_integra_ds = array('codigo', 'descripcion')): array|stdClass
    {
        $registro_original = $registro;
        $r_modifica = parent::modifica_bd(registro: $registro, id: $id, reactiva: $reactiva,
            keys_integra_ds: $keys_integra_ds);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al modificar cliente',data:  $r_modifica);
        }

        if (isset($registro['status'])){
            return $r_modifica;
        }

        $modifica_ks_empleado = $this->modifica_ks_empelado(em_empleado_id: $id, registros: $registro_original);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al modificar ks_empleado',data:  $modifica_ks_empleado);
        }

        $ks_cliente_empleado_alta = $this->modifica_cliente_empleado(registros: $registro_original,
            em_empleado_id: $id);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al insertar ks_cliente_empleado',data:  $ks_cliente_empleado_alta);
        }

        return $r_modifica;
    }

    public function modifica_cliente_empleado(array $registros, int $em_empleado_id)
    {
        $ks_cliente_empleado = (new ks_cliente_empleado(link: $this->link));
        $registro = $ks_cliente_empleado->filtro_and(filtro: array('em_empleado_id' => $em_empleado_id));
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener registro ks_cliente_empleado',data: $registro);
        }

        if ($registro->n_registros == 0) {
            return $this->error->error(mensaje: 'No se encontró registro ks_cliente_empleado',data: $registro);
        }

        $registro = $registro->registros[0];

        $m_ks_cliente_empleado['em_empleado_id'] = $em_empleado_id;
        $m_ks_cliente_empleado['com_cliente_id'] = $registros['com_cliente_id'];

        $operacion = $ks_cliente_empleado->modifica_bd(registro: $m_ks_cliente_empleado, id: $registro['ks_cliente_empleado_id']);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al modificar cliente empleado',data:  $operacion);
        }

        return $operacion;
    }

    public function modifica_ks_empelado(int $em_empleado_id, array $registros)
    {
        $ks_empleado = (new ks_empleado(link: $this->link))->filtro_and(filtro: array('em_empleado_id' => $em_empleado_id));
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener registro ks_empleado',data: $ks_empleado);
        }

        $ks_empleado = $ks_empleado->registros[0];

        $modifica['registro_patronal'] = $registros['registro_patronal'];
        $ks_empleado_modifica = (new ks_empleado(link: $this->link))->modifica_bd(registro: $modifica, id: $ks_empleado['ks_empleado_id']);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al insertar ks_cliente',data:  $ks_empleado_modifica);
        }

        return $ks_empleado_modifica;
    }


    public function transacciona_em_rel_empleado_sucursal(array $data, int $em_empleado_id): array|stdClass {
        return array();
    }

}