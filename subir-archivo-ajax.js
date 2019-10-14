
//Esta parte es la que sube el archivo
$("#uploadFile").submit(function() {
	var files = $('#uploadedFile')[0].files[0];
	formData.append('uploadedFile',files);
	$.ajax({
    //Aquí se pone la dirección donde será subido
		url: '/subirArchivo.php',
		type: 'post',
		data: formData,
		contentType: false,
		processData: false,
		success: function(response) {
			console.log(response)
			if (response != 'Archivo se ha subido correctamente.') {
				console.log('No se subio desde el ajax');
			}
		},
		error: function(jqXHR,estado, error){
			console.log('Error al subir el archivo');
		}
	});
	return false;
});
