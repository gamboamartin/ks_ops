<?php /** @var gamboamartin\ks_ops\controllers\controlador_adm_grupo $controlador controlador en ejecucion */ ?>
<?php use config\views; ?>
<main class="main section-color-primary">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <?php include (new views())->ruta_templates . "head/title.php"; ?>
                <?php include (new views())->ruta_templates . "mensajes.php"; ?>

                <div class="widget  widget-box box-container form-main widget-form-cart" id="form">
                    <?php include (new views())->ruta_templates . "head/subtitulo.php"; ?>

                    <div class="row">
                        <div class="col-lg-12">
                            <table id="table-adm_calendario" class="table mb-0 table-striped table-sm "></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
