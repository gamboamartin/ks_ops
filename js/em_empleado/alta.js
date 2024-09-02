let sl_dp_pais = $("#dp_pais_id");
let sl_dp_estado = $("#dp_estado_id");
let sl_dp_municipio = $("#dp_municipio_id");
let sl_dp_cp = $("#dp_cp_id");
let sl_dp_colonia = $("#dp_colonia_postal_id");
let sl_dp_calle_pertenece = $("#dp_calle_pertenece_id");

let animaciones = (inputs, efecto = 0, margin = 0) => {
    inputs.forEach(function (valor, indice, array) {
        $(`#direccion_pendiente_${valor}`).parent().parent().css('margin-bottom', margin);
        if (margin == 0) {
            $(`#direccion_pendiente_${valor}`).parent().hide(efecto);
            $(`#direccion_pendiente_${valor}`).parent().siblings().hide(efecto);
        } else {
            $(`#direccion_pendiente_${valor}`).parent().show(efecto);
            $(`#direccion_pendiente_${valor}`).parent().siblings().show(efecto);
        }
    });
};

function dp_asigna_estados(dp_pais_id = '', dp_estado_id = '') {

    let url = get_url("dp_estado", "get_estado", {dp_pais_id: dp_pais_id});

    get_data(url, function (data) {
        sl_dp_estado.empty();
        integra_new_option(sl_dp_estado, 'Seleccione un estado', '-1');

        $.each(data.registros, function (index, dp_estado) {
            integra_new_option(sl_dp_estado, dp_estado.dp_estado_descripcion, dp_estado.dp_estado_id,
                "data-dp_estado_predeterminado", dp_estado.dp_estado_predeterminado);
        });
        sl_dp_estado.val(dp_estado_id);
        sl_dp_estado.selectpicker('refresh');
    });
}

function dp_asigna_municipios(dp_estado_id = '', dp_municipio_id = '') {

    let url = get_url("dp_municipio", "get_municipio", {dp_estado_id: dp_estado_id});

    get_data(url, function (data) {
        sl_dp_municipio.empty();

        integra_new_option(sl_dp_municipio, 'Seleccione un municipio', '-1');

        $.each(data.registros, function (index, dp_municipio) {
            integra_new_option(sl_dp_municipio, dp_municipio.dp_municipio_descripcion, dp_municipio.dp_municipio_id,
                "data-dp_municipio_predeterminado", dp_municipio.dp_municipio_predeterminado);
        });
        sl_dp_municipio.val(dp_municipio_id);
        sl_dp_municipio.selectpicker('refresh');
    });
}

function dp_asigna_cps(dp_municipio_id = '', dp_cp_id = '') {

    let url = get_url("dp_cp", "get_cp", {dp_municipio_id: dp_municipio_id});

    get_data(url, function (data) {
        sl_dp_cp.empty();

        integra_new_option(sl_dp_cp, 'Seleccione un cp', '-1');

        $.each(data.registros, function (index, dp_cp) {
            integra_new_option(sl_dp_cp, dp_cp.dp_cp_descripcion, dp_cp.dp_cp_id, "data-dp_cp_predeterminado",
                dp_cp.dp_cp_predeterminado);
        });
        sl_dp_cp.val(dp_cp_id);
        sl_dp_cp.selectpicker('refresh');
    });
}

function dp_asigna_colonias_postales(dp_cp_id = '', dp_colonia_postal_id = '') {

    let url = get_url("dp_colonia_postal", "get_colonia_postal", {dp_cp_id: dp_cp_id});

    get_data(url, function (data) {
        sl_dp_colonia.empty();

        integra_new_option(sl_dp_colonia, 'Seleccione una colonia', '-1');

        $.each(data.registros, function (index, dp_colonia_postal) {
            integra_new_option(sl_dp_colonia, dp_colonia_postal.dp_colonia_postal_descripcion, dp_colonia_postal.dp_colonia_postal_id,
                "data-dp_colonia_postal_predeterminado", dp_colonia_postal.dp_colonia_postal_predeterminado);
        });
        sl_dp_colonia.val(dp_colonia_postal_id);
        sl_dp_colonia.selectpicker('refresh');
    });
}

function dp_asigna_calles_pertenece(dp_colonia_postal_id = '', dp_calle_pertenece_id = '') {

    let url = get_url("dp_calle_pertenece", "get_calle_pertenece", {dp_colonia_postal_id: dp_colonia_postal_id});

    get_data(url, function (data) {
        sl_dp_calle_pertenece.empty();

        integra_new_option(sl_dp_calle_pertenece, 'Seleccione una calle', '-1');

        $.each(data.registros, function (index, dp_calle_pertenece) {
            integra_new_option(sl_dp_calle_pertenece, dp_calle_pertenece.dp_calle_pertenece_descripcion, dp_calle_pertenece.dp_calle_pertenece_id,
                "data-dp_calle_pertenece_predeterminado", dp_calle_pertenece.dp_calle_pertenece_predeterminado);
        });
        sl_dp_calle_pertenece.val(dp_calle_pertenece_id);
        sl_dp_calle_pertenece.selectpicker('refresh');
    });
}

animaciones(["pais", "estado", "municipio", "cp", "colonia", "calle_pertenece"]);

sl_dp_pais.change(function () {
    let selected = $(this).find('option:selected');
    let predeterminado = selected.data(`dp_pais_predeterminado`);

    animaciones(["pais", "estado", "municipio", "cp", "colonia", "calle_pertenece"], "slow");

    dp_asigna_estados(selected.val());

    sl_dp_estado.prop("disabled", false);
    sl_dp_municipio.prop("disabled", false);
    sl_dp_cp.prop("disabled", false);
    sl_dp_colonia.prop("disabled", false);
    sl_dp_calle_pertenece.prop("disabled", false);

    if (predeterminado === 'activo') {
        animaciones(["pais", "estado", "municipio", "cp", "colonia", "calle_pertenece"], "slow", 20);

        sl_dp_estado.prop("disabled", true);
        sl_dp_municipio.prop("disabled", true);
        sl_dp_cp.prop("disabled", true);
        sl_dp_colonia.prop("disabled", true);
        sl_dp_calle_pertenece.prop("disabled", true);
    }
});

sl_dp_estado.change(function () {
    let selected = $(this).find('option:selected');
    let predeterminado = selected.data(`dp_estado_predeterminado`);

    animaciones(["estado", "municipio", "cp", "colonia", "calle_pertenece"], "slow");

    dp_asigna_municipios(selected.val());

    sl_dp_municipio.prop("disabled", false);
    sl_dp_cp.prop("disabled", false);
    sl_dp_colonia.prop("disabled", false);
    sl_dp_calle_pertenece.prop("disabled", false);

    if (predeterminado === 'activo') {
        animaciones(["estado", "municipio", "cp", "colonia", "calle_pertenece"], "slow", 20);
        sl_dp_municipio.prop("disabled", true);
        sl_dp_cp.prop("disabled", true);
        sl_dp_colonia.prop("disabled", true);
        sl_dp_calle_pertenece.prop("disabled", true);

    }
});

sl_dp_municipio.change(function () {
    let selected = $(this).find('option:selected');
    let predeterminado = selected.data(`dp_municipio_predeterminado`);

    animaciones(["municipio", "cp", "colonia", "calle_pertenece"], "slow");

    dp_asigna_cps(selected.val());

    sl_dp_cp.prop("disabled", false);
    sl_dp_colonia.prop("disabled", false);
    sl_dp_calle_pertenece.prop("disabled", false);

    if (predeterminado === 'activo') {
        animaciones(["municipio", "cp", "colonia", "calle_pertenece"], "slow", 20);
        sl_dp_cp.prop("disabled", true);
        sl_dp_colonia.prop("disabled", true);
        sl_dp_calle_pertenece.prop("disabled", true);

    }
});

sl_dp_cp.change(function () {
    let selected = $(this).find('option:selected');
    let predeterminado = selected.data(`dp_cp_predeterminado`);

    animaciones(["cp", "colonia", "calle_pertenece"], "slow");

    dp_asigna_colonias_postales(selected.val());

    sl_dp_colonia.prop("disabled", false);
    sl_dp_calle_pertenece.prop("disabled", false);

    if (predeterminado === 'activo') {
        animaciones(["cp", "colonia", "calle_pertenece"], "slow", 20);
        sl_dp_colonia.prop("disabled", true);
        sl_dp_calle_pertenece.prop("disabled", true);

    }
});

sl_dp_colonia.change(function () {
    let selected = $(this).find('option:selected');
    let predeterminado = selected.data(`dp_colonia_postal_predeterminado`);

    animaciones(["colonia", "calle_pertenece"], "slow");

    dp_asigna_calles_pertenece(selected.val());

    sl_dp_calle_pertenece.prop("disabled", false);

    if (predeterminado === 'activo') {
        animaciones(["colonia", "calle_pertenece"], "slow", 20);
        sl_dp_calle_pertenece.prop("disabled", true);

    }
});

sl_dp_calle_pertenece.change(function () {
    let selected = $(this).find('option:selected');
    let predeterminado = selected.data(`dp_calle_pertenece_predeterminado`);

    animaciones(["calle_pertenece"], "slow");

    if (predeterminado === 'activo') {
        animaciones(["calle_pertenece"], "slow", 20);
    }
});

let txt_nombre = $("#nombre");
let txt_ap = $("#ap");
let txt_am = $("#am");
let txt_correo = $("#correo");
let txt_cp = $("#cp");
let txt_colonia = $("#colonia");
let txt_calle = $("#calle");
let txt_numero_exterior = $("#numero_exterior");
let txt_numero_interior = $("#numero_interior");
const sl_cat_sat_regimen_fiscal = document.getElementById('cat_sat_regimen_fiscal_id');
let txt_rfc = $("#rfc");
let txt_curp = $("#curp");
document.getElementById('documento').addEventListener('change', async function (event) {
    var file = event.target.files[0];

    if (!file) {
        alert('No se seleccionó ningún archivo.');
        event.target.value = '';
        return;
    }

    if (file.type !== 'application/pdf') {
        alert('El archivo seleccionado no es un PDF.');
        event.target.value = '';
        return;
    }

    var loaderOverlay = $('<div class="loader-overlay"><div class="loader"></div></div>');
    $('body').append(loaderOverlay);

    var formData = new FormData();
    formData.append('documento', this.files[0]);

    let url = get_url("em_empleado", "leer_qr", {registro_id: -1});

    try {
        let response = await fetch(url, {
            method: 'POST',
            body: formData
        });

        let result = await response.json();
        let data = result.data;

        txt_nombre.val(data.datos_identificacion.nombre);
        txt_ap.val(data.datos_identificacion.apellido_paterno);
        txt_am.val(data.datos_identificacion.apellido_materno);
        txt_correo.val(data.datos_ubicacion.correo_electronico);
        txt_cp.val(data.datos_ubicacion.cp);
        txt_colonia.val(data.datos_ubicacion.colonia);
        txt_calle.val(data.datos_ubicacion.nombre_de_la_vialidad);
        txt_numero_exterior.val(data.datos_ubicacion.numero_exterior);
        txt_numero_interior.val(data.datos_ubicacion.numero_interior);
        txt_rfc.val(data.rfc);
        txt_curp.val(data.datos_identificacion.curp);

        let municipioUrl = get_url("dp_municipio", "get_municipio", {dp_municipio_descripcion: data.datos_ubicacion.municipio_o_delegacion});
        let municipioResponse = await fetch(municipioUrl);
        let municipioData = await municipioResponse.json();
        let municipio = municipioData.registros[0];

        sl_dp_pais.val(municipio.dp_pais_id);
        sl_dp_pais.selectpicker('refresh');

        await promise_asigna_estados(municipio.dp_pais_id, municipio.dp_estado_id);
        await promise_asigna_municipios(municipio.dp_estado_id, municipio.dp_municipio_id);

        sl_dp_estado.val(municipio.dp_estado_id);
        sl_dp_estado.selectpicker('refresh');

        sl_dp_municipio.val(municipio.dp_municipio_id);
        sl_dp_municipio.selectpicker('refresh');

        let option = Array.from(sl_cat_sat_regimen_fiscal.options).find(opt =>
            opt.text.includes(data.datos_fiscales.regimen)
        );

        if (option) {
            sl_cat_sat_regimen_fiscal.value = option.value;
            $('#cat_sat_regimen_fiscal_id').selectpicker('refresh');
        }

    } catch (error) {
        alert('Error al leer el documento.');
        console.error('Error al subir el archivo:', error);
    } finally {
        loaderOverlay.remove();
    }
});

function promise_asigna_estados(dp_pais_id = '', dp_estado_id = '') {
    return new Promise((resolve, reject) => {
        let url = get_url("dp_estado", "get_estado", {dp_pais_id: dp_pais_id});

        get_data(url, function (data) {
            sl_dp_estado.empty();
            integra_new_option(sl_dp_estado, 'Seleccione un estado', '-1');

            $.each(data.registros, function (index, dp_estado) {
                integra_new_option(sl_dp_estado, dp_estado.dp_estado_descripcion, dp_estado.dp_estado_id,
                    "data-dp_estado_predeterminado", dp_estado.dp_estado_predeterminado);
            });
            sl_dp_estado.val(dp_estado_id);
            sl_dp_estado.selectpicker('refresh');
            resolve(); // Se resuelve la promesa cuando se completa la asignación
        });
    });
}

function promise_asigna_municipios(dp_estado_id = '', dp_municipio_id = '') {
    return new Promise((resolve, reject) => {
        let url = get_url("dp_municipio", "get_municipio", {dp_estado_id: dp_estado_id});

        get_data(url, function (data) {
            sl_dp_municipio.empty();
            integra_new_option(sl_dp_municipio, 'Seleccione un municipio', '-1');

            $.each(data.registros, function (index, dp_municipio) {
                integra_new_option(sl_dp_municipio, dp_municipio.dp_municipio_descripcion, dp_municipio.dp_municipio_id,
                    "data-dp_municipio_predeterminado", dp_municipio.dp_municipio_predeterminado);
            });
            sl_dp_municipio.val(dp_municipio_id);
            sl_dp_municipio.selectpicker('refresh');
            resolve(); // Se resuelve la promesa cuando se completa la asignación
        });
    });
}













