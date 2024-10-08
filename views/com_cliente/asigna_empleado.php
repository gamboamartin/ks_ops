<?php /** @var  \gamboamartin\ks_ops\controllers\controlador_com_cliente $controlador controlador en ejecucion */ ?>
<?php use config\views; ?>

<main class="main section-color-primary">
    <div class="container">

        <div class="row">

            <div class="col-lg-12">

                <div class="widget  widget-box box-container form-main widget-form-cart" id="form" >

                    <form method="post" action="<?php echo $controlador->link_asigna_empleado_bd; ?>"
                          class="form-additional" enctype="multipart/form-data">
                        <?php include (new views())->ruta_templates . "head/title.php"; ?>
                        <?php include (new views())->ruta_templates . "head/subtitulo.php"; ?>
                        <?php include (new views())->ruta_templates . "mensajes.php"; ?>
                        <?php echo $controlador->inputs->documento; ?>
                        <?php echo $controlador->inputs->nombre; ?>
                        <?php echo $controlador->inputs->ap; ?>
                        <?php echo $controlador->inputs->am; ?>
                        <?php echo $controlador->inputs->telefono; ?>
                        <?php echo $controlador->inputs->correo; ?>
                        <?php echo $controlador->inputs->registro_patronal; ?>
                        <?php echo $controlador->inputs->dp_pais_id; ?>
                        <?php echo $controlador->inputs->dp_estado_id; ?>
                        <?php echo $controlador->inputs->dp_municipio_id; ?>
                        <?php echo $controlador->inputs->cp; ?>
                        <?php echo $controlador->inputs->colonia; ?>
                        <?php echo $controlador->inputs->calle; ?>
                        <?php echo $controlador->inputs->numero_exterior; ?>
                        <?php echo $controlador->inputs->numero_interior; ?>
                        <?php echo $controlador->inputs->cat_sat_regimen_fiscal_id; ?>
                        <?php echo $controlador->inputs->em_empleado_rfc; ?>
                        <?php echo $controlador->inputs->em_empleado_nss; ?>
                        <?php echo $controlador->inputs->em_empleado_curp; ?>
                        <div class="control-group btn-alta">
                            <div class="controls">
                                <button class="btn btn-success" role="submit">Asignar</button><br>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12 buttons-form">
            <?php echo $controlador->button_com_cliente_modifica; ?>
        </div>
    </div>
</main>

<main class="main section-color-primary">
    <div class="container">
        <div class="row">
            <div id="preloader" style="display: none;">
                <div class="spinner"></div>
            </div>

            <div class="col-md-12">
                <table id="table-em_empleado" class="table mb-0 table-striped table-sm "></table>
            </div>
        </div>
    </div>
</main>


















