$(document).ready(function(){

    // Evenement onclick sur nos liens .updateUser
    $('.updateUser').click(function(){
      
      let userid = $(this).data('id');
   
      // requête Ajax
      $.ajax({
       url: 'customer_info.php',
       type: 'post',
       data: {id: userid},
       success: function(response){ 
         // Ajouter la réponse dans le corps de notre modal
         $('.ajax.content').html(response);
   
         // Afficher le modal
         $('.ui.modal').modal('show'); 
       }
     });
    });
   });