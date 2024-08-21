$('#em_cuenta_bancaria').DataTable();

let sl_bn_sucursal = $("#bn_sucursal_id");
let mask;

sl_bn_sucursal.change(function () {
    let option = $(this).find("option:selected");
    let codigo = option.data("bn_sucursal_codigo").toString();

    let formato = codigo.split('').map(char => char === '0' ? `\\${char}` : char).join('');
    let mascara = `${formato}000000000000000`;

    let txt_clabe = document.getElementById('clabe');
    let valor_actual = txt_clabe.value;

    if (valor_actual.length > 3) {
        valor_actual = codigo + valor_actual.slice(3);
    } else {
        valor_actual = codigo;
    }

    if (mask) {
        mask.destroy();
    }

    mask = IMask(txt_clabe, {
        mask: mascara
    });

    txt_clabe.value = valor_actual;
    mask.updateValue();
});

