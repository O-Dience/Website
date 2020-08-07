let category = {

    onClickBtnSup:function(evt){
  
          evt.preventDefault()
  
          let categoryId = this.parentNode.dataset.id 
          let deleteDiv = document.querySelector('.category__div');

          
          if (confirm("Êtes-vous sûr ?")) {
              api.deleteCategory(categoryId)
              deleteDiv.remove()
          }
      }
         
  }
  
  document.querySelectorAll('button.delete_category').forEach(function(link){
      link.addEventListener('click', category.onClickBtnSup)
  });
  