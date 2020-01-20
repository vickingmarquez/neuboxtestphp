// Funciones en JQuery
$(document).ready(function() {
    //Archivo seleccionado
    $('input[type="file"]').change(function(e){
        var nameFile = e.target.files[0].name;
        $('.nameFile').html(nameFile);
    });
    //Marcador
    $('#formMarcador').submit(function(e){
        e.preventDefault();
        var form_data = new FormData();
        form_data.append('file', $('#fileTxt')[0].files[0]);
        $.ajax({
            url: '/marcadorCheck',
            type: "POST",
            contentType: false,
            cache: false,
            processData: false,
            data: form_data,
            beforeSend: function(xhr){
                xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                $('#msgSuccess').hide();
                $('#msgError').hide();
            },
            success: function(data){
                console.log(data);	        	
                if(data.status){
                    $('#msgSuccess').html(data.respuesta);
                    $('#msgSuccess').fadeIn(200);
                } else {
                    $('#msgError').html(data.respuesta);
                    $('#msgError').fadeIn(200).delay(5000).fadeOut(1000);
                }	        	
            },
            error: function(jqXHR) {
                $('#msgError').html('Verifica que la estructura del contenido del archivo sea correcta');
                $('#msgError').fadeIn(200).delay(5000).fadeOut(1000);
            }
        });
    });
    //Encriptador
    $('#formEncriptador').submit(function(e){
        e.preventDefault();
        var form_data = new FormData();
        form_data.append('file', $('#fileTxt')[0].files[0]);
        $.ajax({
            url: '/encriptadorCheck',
            type: "POST",
            contentType: false,
            cache: false,
            processData: false,
            data: form_data,
            beforeSend: function(xhr){
                xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                $('#msgSuccess').hide();
                $('#msgError').hide();
            },
            success: function(data){		        	
                console.log(data);
                if(data.status){
                    $('#msgSuccess').html(data.respuesta);
                    $('#msgSuccess').fadeIn(200);
                } else {
                    $('#msgError').html(data.respuesta);
                    $('#msgError').fadeIn(200).delay(5000).fadeOut(1000);
                }	        	
            },
            error: function(jqXHR) {
                $('#msgError').html('Verifica que la estructura del contenido del archivo sea correcta');
                $('#msgError').fadeIn(200).delay(5000).fadeOut(1000);
            }
        });
    });
});