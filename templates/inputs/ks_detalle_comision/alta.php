<?php /** @var \gamboamartin\ks_ops\controllers\controlador_ks_detalle_comision $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->ks_comision_general_id; ?>
<?php echo $controlador->inputs->com_agente_id; ?>
<?php echo $controlador->inputs->porcentaje; ?>
<?php echo $controlador->inputs->fecha_inicio; ?>
<?php echo $controlador->inputs->fecha_fin; ?>

<?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>