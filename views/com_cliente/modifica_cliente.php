<?php /** @var  \gamboamartin\ks_ops\controllers\controlador_com_cliente $controlador controlador en ejecucion */ ?>
<?php use config\views; ?>

<main class="main section-color-primary">
    <div class="container">

        <div class="row">

            <div class="col-lg-12">

                <div class="widget  widget-box box-container form-main widget-form-cart" id="form" >

                    <form method="post" action="<?php echo $controlador->link_modifica_bd; ?>" class="form-additional">
                        <?php include (new views())->ruta_templates . "head/title.php"; ?>
                        <?php include (new views())->ruta_templates . "head/subtitulo.php"; ?>
                        <?php include (new views())->ruta_templates . "mensajes.php"; ?>
                        <?php echo $controlador->inputs->com_tipo_cliente_id; ?>
                        <?php echo $controlador->inputs->codigo; ?>
                        <?php echo $controlador->inputs->razon_social; ?>
                        <?php echo $controlador->inputs->rfc; ?>
                        <?php echo $controlador->inputs->telefono; ?>
                        <?php echo $controlador->inputs->cat_sat_tipo_persona_id; ?>
                        <?php echo $controlador->inputs->cat_sat_regimen_fiscal_id; ?>
                        <?php echo $controlador->inputs->dp_pais_id; ?>
                        <?php echo $controlador->inputs->dp_estado_id; ?>
                        <?php echo $controlador->inputs->dp_municipio_id; ?>
                        <?php echo $controlador->inputs->cp; ?>
                        <?php echo $controlador->inputs->colonia; ?>
                        <?php echo $controlador->inputs->calle; ?>
                        <?php echo $controlador->inputs->numero_exterior; ?>
                        <?php echo $controlador->inputs->numero_interior; ?>

                        <div class="control-group btn-alta">
                            <div class="controls">
                                <button class="btn btn-success" role="submit">Modifica</button><br>
                            </div>
                        </div>
                    </form>
                </div>

            </div>

        </div>
    </div>

</main>



















