let userSocial = {

    onSelectChange:function(evt){

        let socialNetworkId = evt.target.selectedOptions[0].value;
        let social = api.getOne('social_network', socialNetworkId);
        console.log(social);
        
  }

}
  
document.getElementById('user_social_social').addEventListener('change', userSocial.onSelectChange);