$('#ks_detalle_comision').DataTable();

let sl_com_agente = $("#com_agente_id");
var date_fecha_inicio = document.getElementById('fecha_inicio');
var date_fecha_fin = document.getElementById('fecha_fin');

document.addEventListener('DOMContentLoaded', () => {
    var fecha_inicio = document.getElementById('fecha_inicio').value;
    var fecha_fin = document.getElementById('fecha_fin').value;

    document.getElementById('fecha_inicio').setAttribute('min', fecha_inicio);
    document.getElementById('fecha_inicio').setAttribute('max', fecha_fin);
    document.getElementById('fecha_fin').setAttribute('min', fecha_inicio);
    document.getElementById('fecha_fin').setAttribute('max', fecha_fin);
});

function asigna_datos(com_agente_id) {

    let url = get_url("ks_detalle_comision", "get_ultimo_registro", {nombre_campo: 'com_agente_id', valor: com_agente_id});

    get_data(url, function (data) {
        if (data.data.length === 0) {
            return;
        }

        let fecha_inicio_general = new Date(data.data.ks_comision_general_fecha_inicio);
        let fecha_fin_general = new Date(data.data.ks_comision_general_fecha_fin);
        let fecha_inicio = new Date(data.data.ks_detalle_comision_fecha_fin);
        let fecha_fin = new Date(data.data.ks_detalle_comision_fecha_inicio);

        fecha_inicio.setDate(fecha_fin.getDate() + 1);

        let fecha_inicio_formateada = fecha_inicio.toISOString().split('T')[0];
        let fecha_fin_formateada = fecha_fin.toISOString().split('T')[0];
        let fecha_inicio_general_formateada = fecha_inicio_general.toISOString().split('T')[0];
        let fecha_fin_general_formateada = fecha_fin_general.toISOString().split('T')[0];

        date_fecha_fin.value = fecha_fin_formateada;
        date_fecha_inicio.value = fecha_inicio_formateada;
        date_fecha_inicio_general.value = fecha_inicio_general_formateada;
        date_fecha_inicio_general.value = fecha_fin_general_formateada;

        //document.getElementById('fecha_inicio').setAttribute('max', date_fecha_fin.value);
        document.getElementById('fecha_inicio').setAttribute('min', date_fecha_inicio_general.value);
        document.getElementById('fecha_fin').setAttribute('min', date_fecha_inicio.value);
        document.getElementById('fecha_fin').setAttribute('max', date_fecha_inicio_general.value);
    });
}

sl_com_agente.change(function () {
    sl_com_agente_id = $(this).val();

    if (sl_com_agente_id != '') {
        asigna_datos(sl_com_agente_id);
    }
});