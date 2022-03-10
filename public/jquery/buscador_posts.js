$(document).ready(function () {
    
    $('#form_search').keyup(function (e) { 
        if ($('#form_search').val()) {
            //$("#form_search").focus();
            var Ruta_buscador = Routing.generate('search_posts');
            var texto = $('#form_search').val();

            $.ajax({
                type: "POST",
                url: Ruta_buscador,
                data: ({data:texto}),
                dataType: 'json',
                success: function (response) {
                    //var titulos = JSON.parse(response);
                    console.log(response);
                    /*if (response.length===1) {
                        console.log('Tiene 1 registro');
                        $('#navigation').hide();
                    }else{
                        $('#navigation').show();
                    }*/                               
                    var elem = document.createElement('a');
                    $.each(response, function (index, value) {                    
                        //var foto = window.location.assign = value.foto;
                        //var fecha=  new Date(value.fecha_publicacion);    
                        elem.innerHTML ="<li>"+value.titulo+"</li>"
                        ;    
                    });
        
                    $('#titulo').html(elem); 
                    $('#titulo').show();

                }
            }); 
        }
        else{
            $('#titulo').hide();
        }
        
    });
    
});
