$(document).ready(function(){

  // Evenement onclick sur nos icones .updateUser
  $('.icon.updateUser').click(function(){
    
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
        $('.ui.modal.updateUser')
        .modal('attach events', '.ui.close.button', 'hide')
        .modal('show');
      }
    });
  });

  // Evenement onclick sur nos icones .viewLoc
  $('.icon.viewLoc').click(function(){
    
    let userid = $(this).data('id');

    // requête Ajax
    $.ajax({
      url: 'customer_film.php',
      type: 'post',
      data: {id: userid},
      success: function(response){ 
        // Ajouter la réponse dans le corps de notre modal
        $('.ajax.content').html(response);

        // Afficher le modal
        $('.ui.modal.viewLoc')
        .modal('attach events', '.ui.close.button', 'hide')
        .modal('show');
      }
    });
  });

})