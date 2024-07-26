document.addEventListener('DOMContentLoaded', () => {
    var fecha_inicio = document.getElementById('fecha_inicio').value;
    var fecha_fin = document.getElementById('fecha_fin').value;
    document.getElementById('fecha_fin').setAttribute('min', fecha_inicio);
    document.getElementById('fecha_inicio').setAttribute('max', fecha_fin);
});

document.getElementById('fecha_inicio').addEventListener('change', function() {
    var fecha_inicio = this.value;
    document.getElementById('fecha_fin').setAttribute('min', fecha_inicio);
});

document.getElementById('fecha_fin').addEventListener('change', function() {
    var fecha_fin = this.value;
    document.getElementById('fecha_inicio').setAttribute('max', fecha_fin);
});


let sl_ks_comision_general = $("#ks_comision_general_id");
var date_fecha_inicio = document.getElementById('fecha_inicio');
var date_fecha_fin = document.getElementById('fecha_fin');

function asigna_datos(ks_comision_general_id) {

    let url = get_url("ks_detalle_comision", "get_ultimo_registro", {nombre_campo: 'ks_comision_general_id', valor: ks_comision_general_id});

    get_data(url, function (data) {
        if (data.data.length === 0) {
            return;
        }

        console.log(data);
        let fecha_inicio = new Date(data.data.ks_detalle_comision_fecha_fin);
        let fecha_fin = new Date(data.data.ks_detalle_comision_fecha_inicio);

        fecha_inicio.setDate(fecha_fin.getDate() + 1);
        //fecha_fin.setFullYear(fecha_fin.getFullYear() + 1);

        let fecha_inicio_formateada = fecha_inicio.toISOString().split('T')[0];
        let fecha_fin_formateada = fecha_fin.toISOString().split('T')[0];

        date_fecha_fin.value = fecha_fin_formateada;
        date_fecha_inicio.value = fecha_inicio_formateada;

        document.getElementById('fecha_fin').setAttribute('min', date_fecha_inicio.value);
        document.getElementById('fecha_inicio').setAttribute('max', date_fecha_fin.value);
    });
}

sl_ks_comision_general.change(function () {
    ks_comision_general_id = $(this).val();

    if (ks_comision_general_id != '') {
        asigna_datos(ks_comision_general_id);
    }
});