// Dropdown Semantic-UI
$('.dropdown').dropdown();

// Checkbox Semantic-UI
$('.ui.checkbox').checkbox();

// Form Validation Semantic-UI
$('.ui.form')
  .form({
    fields: {
      prenom: {
        identifier: 'first_name',
        rules: [
          {
            type   : 'empty',
            prompt : 'Veuillez indiquer votre prénom'
          },
          {
            type   : 'regExp[[A-Za-z\\u00c0-\\u00ff\\- \']{1,45}]',
            prompt : 'Votre prénom doit contenir entre 2 et 45 caractères'
          }
        ]
      },
      nom: {
        identifier: 'last_name',
        rules: [
          {
            type   : 'empty',
            prompt : 'Veuillez indiquer votre nom'
          },
          {
            type   : 'regExp[[A-Za-z\\u00c0-\\u00ff\- \']{1,45}]',
            prompt : 'Votre nom doit contenir entre 2 et 45 caractères'
          }
        ]
      },
      email: {
        identifier: 'login',
        rules: [
          {
            type   : 'email',
            prompt : 'Veuillez indiquer une adresse email valide'
          }
        ]
      },
      adresse: {
        identifier: 'address',
        rules: [
          {
            type   : 'empty',
            prompt : 'Veuillez selectionner une adresse valide'
          }
        ]
      },
      password: {
        identifier: 'password',
        rules: [
          {
            type   : 'empty',
            prompt : 'Veuillez indiquer un mot de passe'
          },
          {
            type   : 'regExp[/^(?=.*[A-Za-z])(?=.*\\d)[A-Za-z\\d]{8,20}$/]',
            prompt : 'Votre mot de passe doit contenir entre 8 et 20 caractères, au moins une lettre et un nombre'
          }
        ]
      },
      passwordCheck: {
        identifier: 'password_check',
        rules: [
          {
            type   : 'match[password]',
            prompt : 'Les mots de passe ne correspondent pas'
          }
        ]
      },
      cgu: {
        identifier: 'cguCheck',
        rules: [
          {
            type   : 'checked',
            prompt : 'Vous devez accepter les conditions d\'utilisation'
          }
        ]
      }
    }
  })
;