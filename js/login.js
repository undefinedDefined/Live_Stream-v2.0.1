// Form validation Semantic-UI
$(document)
.ready(function() {
  $('.ui.form')
    .form({
      fields: {
        login: {
          identifier  : 'login',
          rules: [
            {
              type   : 'email',
              prompt : 'Merci d\'entrer une adresse email valide'
            }
          ]
        },
        password: {
          identifier  : 'password',
          rules: [
            {
              type   : 'empty',
              prompt : 'Merci d\'entrer votre mot de passe'
            }
          ]
        }
      }
    })
  ;
})
;