
$(document).ready(function(){

    $('#form_search').keyup(function (e) { 
        var Ruta_buscador = Routing.generate('search_posts');
        var texto = $('#form_search').val();
        var input_form = document.getElementById('#form_search');
        if(e.currentTarget.value.trim() === ""){
            
         }else{
            //Si llegas aquí entonces hay contenido.
            $.ajax({
                type: "POST",
                url: Ruta_buscador,
                data: ({data:texto}),
                dataType: 'json',
                success: function (response) {
                    //var titulos = JSON.parse(response);
                    console.log(response);               
                    var mostrar = '';
                    
                    $.each(response, function (index, value) {                    
                        var foto = window.location.assign = value.foto;
                        var fecha=  new Date(value.fecha_publicacion);
                        mostrar +=
                        "<div class='card mb-4' style='width: 44rem;'>"+
                        "<img class='card-img-top img-fluid' id='img_id' src='./uploads/photos/"+foto+"' alt='Card image cap'></img>"+
                            "<div class='card-body'>"+
                            "<h3 class='card-title'>"+value.titulo+"</h3>"+
                            "<p class='card-text'>Lorem ipsum dolor sit amet consectetur adipisicing elit. Reiciendis corrupti iste molestias explicabo minus aspernatur, maiores tenetur, quia ad fuga quod sit mollitia libero at rem deserunt similique saepe quas?"+
                            "Assumenda cupiditate id sapiente. Nulla eum a alias quas harum distinctio fugit quia, labore neque modi unde illo repellat eius ipsam adipisci nisi natus voluptatibus inventore sunt praesentium laudantium pariatur."+
                            "Cupiditate nam neque recusandae odio culpa aut inventore porro dolorem nobis laboriosam. Quibusdam iure ex quidem eligendi autem, nesciunt omnis tempore illum esse. Quas, magnam ad! Quis ipsam accusantium doloremque?</p>"+
                            "<a href='#' class='btn btn-primary'>Ver Más →</a>"+
                            "</div>"+
                            "<div class='card-footer text-muted'>"+
                                "Posted on "+ fecha +" by <a href='#'>"+value.nombre+"</a>"+
                            "</div>"+
                        "</div>";
                        
                    });
        
                    $('#titulo').html(mostrar);              
                }
            }); 
         }
            //e.preventDefault();
        
        
          
    });
});    


