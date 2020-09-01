let userSocial = {

  // find corresponding social network object
  onSelectChange: function (evt) {

    let socialNetworkId = evt.target.selectedOptions[0].value;
    let social = api.getOne('social_network', socialNetworkId);

    fetch(social)
      .then(
        function (response) {
          return response.json();
        }
      ).then(
        function (json) {
          return userSocial.handleSocialDisplay(json);
        }
      );
  },

  handleSocialDisplay: function(json){
    // TODO: Afficher l'interface selon le réseau social
  }

}

document.getElementById('user_social_social').addEventListener('change', userSocial.onSelectChange);