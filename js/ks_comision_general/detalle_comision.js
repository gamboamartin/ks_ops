$('#ks_detalle_comision').DataTable();

let sl_com_agente = $("#com_agente_id");
var date_fecha_inicio = document.getElementById('fecha_inicio');
var date_fecha_fin = document.getElementById('fecha_fin');

let registro_id = getParameterByName('registro_id');

document.addEventListener('DOMContentLoaded', () => {
    var fecha_inicio = document.getElementById('fecha_inicio').value;
    var fecha_fin = document.getElementById('fecha_fin').value;

    document.getElementById('fecha_inicio').setAttribute('min', fecha_inicio);
    document.getElementById('fecha_inicio').setAttribute('max', fecha_fin);
    document.getElementById('fecha_fin').setAttribute('min', fecha_inicio);
    document.getElementById('fecha_fin').setAttribute('max', fecha_fin);
});

function asigna_datos(com_agente_id) {

    let url = get_url("ks_detalle_comision", "get_ultimo_registro", {ks_comision_general_id: registro_id, com_agente_id: com_agente_id});

    get_data(url, function (data) {
        if (data.data.length === 0) {
            return;
        }

        let fecha_inicio = new Date(data.data.ks_detalle_comision_fecha_fin);
        let fecha_fin = new Date(data.data.ks_comision_general_fecha_fin);

        if (fecha_inicio.getDate() < fecha_fin.getDate()) {
            fecha_inicio.setDate(fecha_fin.getDate() + 1);
        }
        console.log({data})
        console.log(fecha_inicio);
        console.log(fecha_fin);

        let fecha_inicio_formateada = fecha_inicio.toISOString().split('T')[0];
         date_fecha_inicio.value = fecha_inicio_formateada;

        document.getElementById('fecha_inicio').setAttribute('min', date_fecha_inicio.value);
        document.getElementById('fecha_fin').setAttribute('min', date_fecha_inicio.value);
    });
}

sl_com_agente.change(function () {
    sl_com_agente_id = $(this).val();

    if (sl_com_agente_id != '') {
        asigna_datos(sl_com_agente_id);
    }
});