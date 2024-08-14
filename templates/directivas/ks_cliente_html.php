<?php
namespace gamboamartin\ks_ops\html;

use gamboamartin\errores\errores;
use gamboamartin\ks_ops\models\ks_cliente;
use gamboamartin\system\html_controler;
use PDO;
use stdClass;


class ks_cliente_html extends html_controler {


    public function select_ks_cliente_id(int $cols, bool $con_registros, int $id_selected, PDO $link): array|string
    {
        $modelo = new ks_cliente(link: $link);

        $select = $this->select_catalogo(cols:$cols,con_registros:$con_registros,id_selected:$id_selected,
            modelo: $modelo,label: 'Cliente');
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }

    public function input_iva(int $cols, stdClass $row_upd, bool $value_vacio, bool $disabled = false): array|string
    {
        $valida = $this->directivas->valida_cols(cols: $cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar columnas', data: $valida);
        }

        $html =$this->directivas->input_text_required(disabled: $disabled,name: 'iva',place_holder: 'IVA',
            row_upd: $row_upd, value_vacio: $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input', data: $html);
        }

        $div = $this->directivas->html->div_group(cols: $cols,html:  $html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;
    }

}