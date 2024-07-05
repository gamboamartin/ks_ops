<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */

namespace gamboamartin\ks_ops\controllers;

use gamboamartin\direccion_postal\controllers\_init_dps;
use gamboamartin\errores\errores;
use stdClass;

final class controlador_com_cliente extends \gamboamartin\comercial\controllers\controlador_com_cliente
{
    public function alta(bool $header, bool $ws = false): array|string
    {
        $urls_js = (new _init_dps())->init_js(controler: $this);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al generar url js', data: $urls_js, header: $header, ws: $ws);
        }

        $r_alta = $this->init_alta();
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al inicializar alta', data: $r_alta, header: $header, ws: $ws);
        }

        $inputs = $this->data_form();
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener inputs', data: $inputs, header: $header, ws: $ws);
        }

        return $r_alta;
    }

    protected function data_form(): array|stdClass
    {
        $keys_selects = $this->init_selects_inputs();
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al inicializar selects', data: $keys_selects);
        }
        $keys_selects['com_tipo_cliente_id']->required = false;
        $keys_selects['dp_pais_id']->required = false;
        $keys_selects['dp_estado_id']->required = false;
        $keys_selects['dp_municipio_id']->required = false;
        $keys_selects['cat_sat_uso_cfdi_id']->required = false;
        $keys_selects['cat_sat_metodo_pago_id']->required = false;
        $keys_selects['cat_sat_forma_pago_id']->required = false;
        $keys_selects['cat_sat_tipo_de_comprobante_id']->required = false;
        $keys_selects['cat_sat_moneda_id']->required = false;

        $data_extra_cat_sat_metodo_pago[] = 'cat_sat_metodo_pago_codigo';
        $keys_selects['cat_sat_metodo_pago_id']->extra_params_keys = $data_extra_cat_sat_metodo_pago;

        $data_extra_cat_sat_forma_pago[] = 'cat_sat_forma_pago_codigo';
        $keys_selects['cat_sat_forma_pago_id']->extra_params_keys = $data_extra_cat_sat_forma_pago;

        $inputs = $this->inputs(keys_selects: $keys_selects);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al obtener inputs', data: $inputs);
        }

        return $inputs;
    }


}
