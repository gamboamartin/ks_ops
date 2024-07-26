<?php /** @var  \gamboamartin\ks_ops\controllers\controlador_ks_comision_general $controlador controlador en ejecucion */ ?>
<?php use config\views; ?>

<main class="main section-color-primary">
    <div class="container">

        <div class="row">

            <div class="col-lg-12">

                <div class="widget  widget-box box-container form-main widget-form-cart" id="form" >

                    <form method="post" action="<?php echo $controlador->link_registra_detalle_comision_bd; ?>" class="form-additional">
                        <?php include (new views())->ruta_templates . "head/title.php"; ?>
                        <?php include (new views())->ruta_templates . "head/subtitulo.php"; ?>
                        <?php include (new views())->ruta_templates . "mensajes.php"; ?>
                        <?php echo $controlador->inputs->ks_comision_general_id; ?>
                        <?php echo $controlador->inputs->fecha_inicio_disabled; ?>
                        <?php echo $controlador->inputs->fecha_fin_disabled; ?>
                        <?php echo $controlador->inputs->com_agente_id; ?>
                        <?php echo $controlador->inputs->porcentaje; ?>
                        <?php echo $controlador->inputs->fecha_inicio; ?>
                        <?php echo $controlador->inputs->fecha_fin; ?>
                        <div class="control-group btn-alta">
                            <div class="controls">
                                <button class="btn btn-success" role="submit">Registrar</button><br>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12 buttons-form">
            <?php echo $controlador->button_ks_comision_general; ?>
        </div>
    </div>
</main>

<main class="main section-color-primary">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="widget widget-box box-container widget-mylistings">
                    <?php echo $controlador->contenido_table; ?>
                </div> <!-- /. widget-table-->
            </div><!-- /.center-content -->
        </div>
    </div>
</main>


















