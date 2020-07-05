let fav = {

  onClickBtnFav:function(evt){
        evt.preventDefault()
        let fav = document.querySelector('.favAnnouncement')
            favId = fav.dataset.id
            api.deleteFav(favId)
            fav.remove()  
    }
}

document.querySelectorAll('a.js-fav').forEach(function(link){
    link.addEventListener('click', fav.onClickBtnFav)
});

