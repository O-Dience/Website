let fav = {

  onClickBtnFav:function(evt){

        evt.preventDefault()

        let favId = this.parentNode.dataset.id 
            api.deleteFav(favId)
            this.parentNode.remove()  
    }

       
}

document.querySelectorAll('button.js-fav').forEach(function(link){
    link.addEventListener('click', fav.onClickBtnFav)
});

