<?php /** @var \gamboamartin\ks_ops\controllers\controlador_org_puesto $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->descripcion; ?>
<?php echo $controlador->inputs->org_tipo_puesto_id; ?>
<?php echo $controlador->inputs->org_departamento_id; ?>
<?php include (new views())->ruta_templates.'botons/submit/modifica_bd.php';?>


