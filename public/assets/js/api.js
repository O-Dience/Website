let api = {

  loadHeaders: function(){
    myHeaders = new Headers();
    myHeaders.append("Content-Type", "application/json");
    return myHeaders;
  },

  deleteFav: function (id){

    let fetchOptions = {
      method: 'DELETE',
      mode: 'cors',
      cache: 'no-cache',
      headers: api.loadHeaders()
    };
    
    fetch('http://localhost:8000/api/v1/announcement/favs/'+ id, fetchOptions)
  }

}



