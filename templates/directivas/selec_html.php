<?php
namespace gamboamartin\ks_ops\html;

use gamboamartin\cat_sat\models\cat_sat_periodicidad;
use gamboamartin\errores\errores;
use gamboamartin\ks_ops\models\ks_cliente;
use gamboamartin\system\html_controler;
use PDO;


class selec_html extends html_controler {


    public function select_cat_sat_periodicidad_id(int $cols, bool $con_registros, int $id_selected, PDO $link): array|string
    {
        $modelo = new cat_sat_periodicidad(link: $link);

        $select = $this->select_catalogo(cols:$cols,con_registros:$con_registros,id_selected:$id_selected,
            modelo: $modelo,label: 'Periodicidad');
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }

}