document.getElementById('documento').addEventListener('change', function (event) {
    var file = event.target.files[0];

    if (!file) {
        alert('No se seleccionó ningún archivo.');
        event.target.value = '';
        return;
    }

    if (file.type !== 'application/pdf') {
        alert('El archivo seleccionado no es un PDF.');
        event.target.value = '';
        return;
    }

    var formData = new FormData();
    formData.append('documento', this.files[0]);

    let url = get_url("com_cliente","leer_qr", {registro_id: -1});

    fetch(url, {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(result => {
            console.log(result);

        })
        .catch(error => {
            alert('Error al leer el documento.');
            console.error('Error al subir el archivo:', error);
        });
});