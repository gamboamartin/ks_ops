$('#ks_comision_general').DataTable();

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