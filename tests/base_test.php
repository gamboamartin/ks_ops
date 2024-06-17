<?php
namespace gamboamartin\facturacion\tests;
use base\orm\modelo_base;

use config\generales;
use gamboamartin\cat_sat\models\cat_sat_factor;
use gamboamartin\cat_sat\models\cat_sat_forma_pago;
use gamboamartin\cat_sat\models\cat_sat_metodo_pago;
use gamboamartin\cat_sat\models\cat_sat_moneda;
use gamboamartin\cat_sat\models\cat_sat_obj_imp;
use gamboamartin\cat_sat\models\cat_sat_tipo_de_comprobante;
use gamboamartin\cat_sat\models\cat_sat_tipo_factor;
use gamboamartin\cat_sat\models\cat_sat_tipo_impuesto;
use gamboamartin\cat_sat\models\cat_sat_tipo_relacion;
use gamboamartin\cat_sat\models\cat_sat_uso_cfdi;
use gamboamartin\comercial\models\com_producto;
use gamboamartin\comercial\models\com_sucursal;
use gamboamartin\comercial\models\com_tipo_cambio;
use gamboamartin\documento\models\doc_documento;
use gamboamartin\documento\models\doc_version;
use gamboamartin\errores\errores;
use gamboamartin\facturacion\models\fc_complemento_pago;
use gamboamartin\facturacion\models\fc_conf_retenido;
use gamboamartin\facturacion\models\fc_conf_traslado;
use gamboamartin\facturacion\models\fc_csd;
use gamboamartin\facturacion\models\fc_docto_relacionado;
use gamboamartin\facturacion\models\fc_factura;
use gamboamartin\facturacion\models\fc_factura_documento;
use gamboamartin\facturacion\models\fc_factura_relacionada;
use gamboamartin\facturacion\models\fc_impuesto_dr;
use gamboamartin\facturacion\models\fc_impuesto_p;
use gamboamartin\facturacion\models\fc_nc_rel;
use gamboamartin\facturacion\models\fc_nota_credito;
use gamboamartin\facturacion\models\fc_pago;
use gamboamartin\facturacion\models\fc_pago_pago;
use gamboamartin\facturacion\models\fc_partida;


use gamboamartin\facturacion\models\fc_partida_cp;
use gamboamartin\facturacion\models\fc_partida_nc;
use gamboamartin\facturacion\models\fc_relacion;
use gamboamartin\facturacion\models\fc_relacion_nc;
use gamboamartin\facturacion\models\fc_traslado_dr;
use gamboamartin\facturacion\models\fc_traslado_dr_part;
use gamboamartin\facturacion\models\fc_traslado_p;
use gamboamartin\facturacion\models\fc_traslado_p_part;
use gamboamartin\notificaciones\models\not_adjunto;
use gamboamartin\organigrama\models\org_sucursal;
use PDO;
use stdClass;


class base_test{




}
