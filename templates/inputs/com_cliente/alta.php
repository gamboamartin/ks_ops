<?php /** @var gamboamartin\comercial\controllers\controlador_com_cliente $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->com_tipo_cliente_id; ?>
<?php echo $controlador->inputs->codigo; ?>
<?php echo $controlador->inputs->razon_social; ?>
<?php echo $controlador->inputs->com_cliente_rfc; ?>
<?php echo $controlador->inputs->telefono; ?>

<?php include (new views())->ruta_templates.'botons/submit/alta_bd.php';?>






