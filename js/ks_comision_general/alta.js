document.addEventListener('DOMContentLoaded', () => {
    var fecha_inicio = document.getElementById('fecha_inicio').value;
    var fecha_fin = document.getElementById('fecha_fin').value;
    document.getElementById('fecha_fin').setAttribute('min', fecha_inicio);
    document.getElementById('fecha_inicio').setAttribute('max', fecha_fin);
});

document.getElementById('fecha_inicio').addEventListener('change', function () {
    var fecha_inicio = this.value;
    document.getElementById('fecha_fin').setAttribute('min', fecha_inicio);
});

document.getElementById('fecha_fin').addEventListener('change', function () {
    var fecha_fin = this.value;
    document.getElementById('fecha_inicio').setAttribute('max', fecha_fin);
});

let sl_com_cliente = $("#com_cliente_id");
var date_fecha_inicio = document.getElementById('fecha_inicio');
var date_fecha_fin = document.getElementById('fecha_fin');

function asigna_datos(com_cliente_id) {

    let url = get_url("ks_comision_general", "get_ultimo_registro", {com_cliente_id: com_cliente_id});

    get_data(url, function (data) {
        if (data.data.length === 0) {
            return;
        }

        console.log(data);
        let fecha_inicio = new Date(data.data.ks_comision_general_fecha_fin);
        let fecha_fin = new Date(data.data.ks_comision_general_fecha_inicio);

        fecha_inicio.setDate(fecha_fin.getDate() + 1);
        fecha_fin.setFullYear(fecha_fin.getFullYear() + 1);

        let fecha_inicio_formateada = fecha_inicio.toISOString().split('T')[0];
        let fecha_fin_formateada = fecha_fin.toISOString().split('T')[0];

        date_fecha_fin.value = fecha_fin_formateada;
        date_fecha_inicio.value = fecha_inicio_formateada;

        document.getElementById('fecha_fin').setAttribute('min', date_fecha_inicio.value);
        document.getElementById('fecha_inicio').setAttribute('max', date_fecha_fin.value);
    });
}

sl_com_cliente.change(function () {
    com_cliente_id = $(this).val();

    if (com_cliente_id != '') {
        asigna_datos(com_cliente_id);
    }
});