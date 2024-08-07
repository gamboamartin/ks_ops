$('#em_cuenta_bancaria').DataTable();

let sl_bn_sucursal = $("#bn_sucursal_id");
let txt_clabe = document.getElementById('clabe');

sl_bn_sucursal.change(function () {
    let option = $(this).find("option:selected");
    let codigo = option.data("bn_sucursal_codigo");

    let formato = codigo.toString().split('').map(char => char === '0' ? `\\${char}` : char).join('');
    let mascara = `${formato}-000000000000000000`;

    if (txt_clabe.inputmask) {
        txt_clabe.inputmask.remove();
    }

    let mask = IMask(txt_clabe, {
        mask:[{ mask: '' }, { mask: mascara }]
    });

    mask.updateValue();
});

