<?php /** @var \gamboamartin\ks_ops\controllers\controlador_org_tipo_puesto $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->descripcion; ?>

<?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>
