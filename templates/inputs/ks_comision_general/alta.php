<?php /** @var \gamboamartin\ks_ops\controllers\controlador_ks_comision_general $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->com_cliente_id; ?>
<?php echo $controlador->inputs->cat_sat_actividad_economica_id; ?>
<?php echo $controlador->inputs->codigo; ?>
<?php echo $controlador->inputs->razon_social; ?>
<?php echo $controlador->inputs->com_cliente_rfc; ?>
<?php echo $controlador->inputs->telefono; ?>

<?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>