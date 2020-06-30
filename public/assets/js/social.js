let social = {

    onClickBtnSup:function(evt){
  
          evt.preventDefault()
  
          let socialId = this.parentNode.dataset.id 
          let deleteDiv = document.querySelector('.social__div');

          
          if (confirm("Êtes-vous sûr ?")) {
              api.deleteSocial(socialId)
              deleteDiv.remove()
          }
      }
         
  }
  
  document.querySelectorAll('button.delete_social').forEach(function(link){
      link.addEventListener('click', social.onClickBtnSup)
  });
  