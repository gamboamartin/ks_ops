$('#generar_evento').change(function() {
    if (this.checked) {
        $('#elementos-evento')
            .stop(true, true)
            .slideDown({
                duration: 1000,
                easing: 'swing',
                complete: function() {
                    $('#elementos-evento').find('input[name], select[name], textarea[name]').attr('required', true);
                }
            });
    } else {
        $('#elementos-evento')
            .stop(true, true)
            .slideUp({
                duration: 1000,
                easing: 'swing',
                complete: function() {
                    $('#elementos-evento').find('input, select, textarea').removeAttr('required');
                }
            });
    }
});

const txt_fecha_inicio = document.getElementById('fecha_inicio');
const txt_fecha_fin = document.getElementById('fecha_fin');

const hoy = new Date();
const fecha_actual = hoy.toISOString().split('T')[0];

txt_fecha_inicio.value = fecha_actual;
txt_fecha_fin.value = fecha_actual;

