<?php /** @var gamboamartin\ks_ops\controllers\controlador_adm_usuario $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>

<?php echo $controlador->inputs->user; ?>

<?php echo $controlador->inputs->password; ?>
<?php echo $controlador->inputs->email; ?>
<?php echo $controlador->inputs->telefono; ?>
<?php echo $controlador->inputs->nombre; ?>
<?php echo $controlador->inputs->ap; ?>
<?php echo $controlador->inputs->am; ?>


<?php echo $controlador->inputs->adm_grupo_id; ?>



<?php include (new views())->ruta_templates.'botons/submit/alta_bd_otro.php';?>
