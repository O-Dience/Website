let api = {

  infos: [],

  loadHeaders: function () {
    myHeaders = new Headers();
    myHeaders.append("Content-Type", "application/json");
    return myHeaders;
  },

  getOne: function (entity, id) {
    let fetchOptions = {
      method: 'GET',
      mode: 'cors',
      cache: 'no-cache',
      headers: api.loadHeaders()
    };
    let url = window.location.origin;
    let fetchUrl = '';
    fetchUrl = url + '/api/v1/' + entity + '/' + id, fetchOptions

    return fetchUrl
  },


  deleteFav: function (id) {

    let fetchOptions = {
      method: 'DELETE',
      mode: 'cors',
      cache: 'no-cache',
      headers: api.loadHeaders()
    };

    fetch('http://localhost:8000/api/v1/announcement/favs/' + id, fetchOptions)
  },


  deleteSocial: function (id) {

    let fetchOptions = {
      method: 'DELETE',
      mode: 'cors',
      cache: 'no-cache',
      headers: api.loadHeaders()
    };

    fetch('http://localhost:8000/api/v1/user/social/' + id, fetchOptions)
  }

}