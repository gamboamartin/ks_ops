let txt_codigo = $("#codigo");
let txt_rfc = $("#rfc");
let txt_razon_social = $("#razon_social");
let txt_tipo_persona = $("#tipo_persona");
let txt_regimen_fiscal = $("#regimen_fiscal");
let txt_estado = $("#estado");
let txt_municipio = $("#municipio");
let txt_cp = $("#cp");
let txt_colonia = $("#colonia");
let txt_calle = $("#calle");
let txt_numero_exterior = $("#numero_exterior");
let txt_numero_interior = $("#numero_interior");

document.getElementById('documento').addEventListener('change', function (event) {
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

    let url = get_url("com_cliente", "leer_qr", {registro_id: -1});

    fetch(url, {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(result => {
            data = result.data;
            persona = data.datos_identificacion;

            let razon_social = "";

            if (data.tipo_persona === 'PERSONA FISICA') {
                razon_social = persona.nombre + ' ' + persona.apellido_paterno + ' ' + persona.apellido_materno;
            } else if (data.tipo_persona === 'PERSONA MORAL') {
                razon_social = persona.denominacion_o_razon_social;
            }

            txt_codigo.val(data.rfc);
            txt_rfc.val(data.rfc);
            txt_razon_social.val(razon_social);
            txt_tipo_persona.val(data.tipo_persona);
            txt_regimen_fiscal.val(data.datos_fiscales.regimen);
            txt_estado.val(data.datos_ubicacion.entidad_federativa);
            txt_municipio.val(data.datos_ubicacion.municipio_o_delegacion);
            txt_cp.val(data.datos_ubicacion.cp);
            txt_colonia.val(data.datos_ubicacion.colonia);
            txt_calle.val(data.datos_ubicacion.nombre_de_la_vialidad);
            txt_numero_exterior.val(data.datos_ubicacion.numero_exterior);
            txt_numero_interior.val(data.datos_ubicacion.numero_interior);

            loaderOverlay.remove();
        })
        .catch(error => {
            alert('Error al leer el documento.');
            console.error('Error al subir el archivo:', error);
            loaderOverlay.remove();
        });
});
