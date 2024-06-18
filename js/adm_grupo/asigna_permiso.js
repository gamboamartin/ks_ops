let sl_adm_menu = $("#adm_menu_id");
let sl_adm_seccion = $("#adm_seccion_id");
let sl_adm_accion = $("#adm_accion_id");
let adm_menu_id = -1;
let adm_seccion_id = -1;
let adm_accion_id = -1;


function asigna_acciones(){

    let url = get_url("adm_accion","get_adm_accion", {adm_seccion_id: adm_seccion_id});

    get_data(url, function (data) {
        console.log(data);
        sl_adm_accion.empty();
        integra_new_option(sl_adm_accion,'Seleccione una seccion','-1');

        $.each(data.registros, function( index, adm_accion ) {
            console.log(adm_accion);
            integra_new_option(sl_adm_accion,adm_accion.adm_accion_descripcion,adm_accion.adm_accion_id,
                "data-adm_accion_id",adm_accion.adm_accion_id);
        });
        sl_adm_accion.val(adm_accion_id);
        sl_adm_accion.selectpicker('refresh');
    });
}
function asigna_secciones(){

    let url = get_url("adm_seccion","get_adm_seccion", {adm_menu_id: adm_menu_id});

    get_data(url, function (data) {
        //console.log(data.registros);
        sl_adm_seccion.empty();
        integra_new_option(sl_adm_seccion,'Seleccione una seccion','-1');

        $.each(data.registros, function( index, adm_seccion ) {
            console.log(adm_seccion);
            integra_new_option(sl_adm_seccion,adm_seccion.adm_seccion_descripcion,adm_seccion.adm_seccion_id,
                "data-adm_seccion_id",adm_seccion.adm_seccion_id);
        });
        sl_adm_seccion.val(adm_seccion_id);
        sl_adm_seccion.selectpicker('refresh');
    });
}

sl_adm_menu.change(function () {
    adm_menu_id = $(this).val();

    asigna_secciones();

    //dp_asigna_estados(selected.val());

});

sl_adm_seccion.change(function () {

    adm_seccion_id = $(this).val();
    asigna_acciones();

    //dp_asigna_estados(selected.val());

});















