<?php /** @var gamboamartin\acl\controllers\controlador_adm_evento $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>

<?php echo $controlador->inputs->descripcion; ?>

<?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>
