$(document).ready(function(){

    // Selectors pour Update 
    const icon_Update = $('.icon.updateUser');
    const formModal_Update = $('#updateUser');
    const formModal_Update_Content = $('#updateUser > .content');
    const confirmModal_Update = $('#confirmUpdateUser');

    /**
     * 
     * @function On crée un évenement onclick sur nos icones de modification
     * 
     * @let userid: récupère la valeur de l'attribut 'data-id' de l'icône sur laquelle on a cliqué
     * Celle-ci correspond à l'id de l'utilisateur pour lequel on veut faire l'update
     * 
     * On fait ensuite une requête Ajax de type POST à customer_info.php
     * dans laquelle on envoi $_POST['id'] = userid
     * 
     * @returns customer_info.php renvoi le code html du formulaire contenant les informations de l'utilisateur
     * On ajoute ensuite le formulaire dans notre modal, puis on affiche ce dernier
     * 
     */

    icon_Update.click(function(){

        let userid = $(this).data('id');
        
        $.ajax({
            url: 'customer_info.php',
            type: 'post',
            data: {id: userid},
            success: function(response){
                // Ajouter la réponse (formulaire) dans le corps de notre modal principal
                formModal_Update_Content.html(response);

                // Afficher le modal principal
                formModal_Update.modal('attach events', '.ui.close.button', 'hide')
                .modal('setting', 'closable', false)
                .modal('show');
            }
        });
    });

    /**
     * La partie qui suit est nécessaire pour afficher le modal de confirmation rattaché au modal principal
     * contenant les informations renvoyées par customer_info.php
     */

    // Autoriser deux modaux l'un sur l'autre
    $('.coupled.modal').modal({
        allowMultiple: true
    });

    // Afficher le modal de confirmation lorsqu'on appuie sur le bouton de modification
    confirmModal_Update.modal('attach events', '#submitUpdateUser');

    /**
     * @function pour envoyer le formulaire via Ajax si on confirme notre choix
     * 
     * @let data: contient un tableau Javascript d'objets correspondant à nos inputs
     * Exemple pour deux input 'prenom' et 'nom" : [{name: prenom, value: monPrenom}, {name: nom, value: monNom}]
     * 
     * @let url : récupère l'url de l'attribut action de notre formulaire
     * 
     * @returns notre url nous renvoi une alerte contenant le message associé au succès ou non de notre update
     * 
     */

    confirmModal_Update.modal({
        onApprove: function() {
            let data = $( "#formUpdate" ).serializeArray();
            let url = $( "#formUpdate" ).attr('action');
            $.ajax({
                url: url,
                type: 'post',
                data: data,
                success: function(response){
                    // Ajouter la réponse (alerte) après notre titre
                    $(response).insertAfter('.row:first');
                    // Javascript pour permettre de fermer les messages d'alerte, et leur ligne associée au bout d'un delai
                    $('.message .close').click(function() {
                        $(this).closest('.message').transition('fade');
                        setTimeout(() => {
                            $(this).closest('.row').remove();
                        }, 1000);
                    });
                    // Fermer le modal principal une fois la modification effectuée
                    formModal_Update.modal('hide');
                }
            })
        }
    });


    // Selectors pour locations
    const icon_Location = $('.icon.viewLoc');
    const formModal_Location = $('#viewLoc');
    const formModal_Location_Content = $('#viewLoc > .content');

    /**
     * 
     * @function On crée un évenement onclick sur nos icones de locations
     * 
     * @let userid: récupère la valeur de l'attribut 'data-id' de l'icône sur laquelle on a cliqué
     * Celle-ci correspond à l'id de l'utilisateur pour lequel on veut faire l'update
     * 
     * On fait ensuite une requête Ajax de type POST à customer_film.php
     * dans laquelle on envoi $_POST['id'] = userid
     * 
     * @returns customer_film.php renvoi le code html d'un tableau contenant les locations de l'utilisateur
     * On ajoute ensuite le tableau dans notre modal, puis on affiche ce dernier
     * 
     */

    icon_Location.click(function(){

        let userid = $(this).data('id');

        // requête Ajax
        $.ajax({
            url: 'customer_film.php',
            type: 'post',
            data: {id: userid},
            success: function(response){ 
                // Ajouter la réponse dans le corps de notre modal
                formModal_Location_Content.html(response);

                // Afficher le modal
                formModal_Location.modal('attach events', '.ui.close.button', 'hide')
                .modal('setting', 'closable', false)
                .modal('show');
            }
        });
    });

    

});