const registro_id = getParameterByName('registro_id');

var loaderOverlay = $('<div class="loader-overlay"><div class="loader"></div></div>');
$('.widget').append(loaderOverlay);

let getData = (url) => {
    return fetch(url)
        .then(response => response.json())
        .catch(err => {
            alert('Error al ejecutar');
            console.error("ERROR: ", err.message);
        });
};

let url = get_url("adm_calendario", "acciones_permitidas", {}, 0);
let permisos = [];

let url_usuario = get_url("adm_usuario", "get_usuario", {}, 0);

getData(url_usuario).then(user => {
    const filtro_adm_calendario = [
        {
            "key": "adm_usuario.id",
            "valor": user
        }
    ];

    getData(url).then(data => {
        permisos = data.data;
        const table_adm_calendario = table('adm_calendario', columns_adm_calendario, filtro_adm_calendario, [], callback_adm_calendario, false);
    }).finally(() => {
        loaderOverlay.remove();
    });
})

const columns_adm_calendario = [
    {
        title: "Id",
        data: "adm_calendario_id"
    },{
        title: "Sección",
        data: "adm_seccion_descripcion"
    },
    {
        title: "Titulo",
        data: "adm_calendario_titulo"
    },
    {
        title: "Zona horaria",
        data: "adm_calendario_zona_horaria"
    },
    {
        title: "Estado",
        data: "adm_calendario_status"
    },
    {
        title: "Acciones",
        data: null
    }
];





const callback_adm_calendario = (seccion, columns) => {
    return [
        {
            targets: -2,
            render: function (data, type, row, meta) {
                let etapa = row[`adm_calendario_status`];
                let badge = 'delete';

                if (etapa.toLowerCase() === 'activo') {
                    badge = 'success';
                }

                return `<span class="badge badge-pill badge-${badge}">${etapa.toLowerCase()}</span>`;
            }
        },
        {
            targets: -1,
            render: function (data, type, row, meta) {
                let links = '';
                for (permiso of permisos) {
                    let url_permiso = new URL($(location).attr('href'));

                    let sec = getParameterByName('seccion');
                    let acc = getParameterByName('accion');
                    let registro_id = getParameterByName('registro_id');

                    url_permiso.searchParams.set('accion', permiso.adm_accion_descripcion);
                    url_permiso.searchParams.set('seccion', permiso.adm_seccion_descripcion);
                    url_permiso.searchParams.set('registro_id', row['adm_calendario_id']);

                    links += `<a href="${url_permiso}">${permiso.adm_accion_titulo}</a>`;
                }

                let dropdown_menu = `
                    <div class="dropdown">
                        <span class="dropbtn">&#9776;</span>
                        <div class="dropdown-content">
                            ${links}
                        </div>
                    </div>
                `;
                return dropdown_menu;
            }
        }
    ]
}
