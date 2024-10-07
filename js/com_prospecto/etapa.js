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

document.getElementById('hora_inicio').addEventListener('change', validar_horas);
document.getElementById('hora_fin').addEventListener('change', validar_horas);

function validar_horas() {
    var horaInicio = document.getElementById('hora_inicio').value;
    var horaFin = document.getElementById('hora_fin').value;

    if (horaInicio && horaFin) {
        if (horaInicio > horaFin) {
            alert('La hora de inicio no puede ser posterior a la hora de fin.');
            document.getElementById('hora_inicio').value = '';
        } else if (horaFin < horaInicio) {
            alert('La hora de fin no puede ser anterior a la hora de inicio.');
            document.getElementById('hora_fin').value = '';
        }
    }
}

