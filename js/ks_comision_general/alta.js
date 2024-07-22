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

let sl_com_cliente = $("#com_cliente_id");

function asigna_datos(com_cliente_id){

    let url = get_url("ks_comision_general","get_ultimo_registro", {com_cliente_id: com_cliente_id});

    get_data(url, function (data) {
        console.log(data);
    });
}

sl_com_cliente.change(function () {
    com_cliente_id = $(this).val();
    asigna_acciones(com_cliente_id);
});