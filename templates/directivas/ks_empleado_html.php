<?php
namespace gamboamartin\ks_ops\html;

use gamboamartin\errores\errores;
use gamboamartin\ks_ops\models\ks_empleado;
use gamboamartin\system\html_controler;
use PDO;

class ks_empleado_html extends html_controler {


    public function select_ks_empleado_id(int $cols, bool $con_registros, int $id_selected, PDO $link): array|string
    {
        $modelo = new ks_empleado(link: $link);

        $select = $this->select_catalogo(cols:$cols,con_registros:$con_registros,id_selected:$id_selected,
            modelo: $modelo,label: 'Empleado');
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }

}