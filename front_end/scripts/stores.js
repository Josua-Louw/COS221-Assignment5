
        document.querySelectorAll('.btn-follow').forEach(button => {
            button.addEventListener('click', function() {
                const storeId = this.getAttribute('data-store-id');
                // This will be replaced with actual API call to follow endpoint
                console.log('Following store:', storeId);
                this.textContent = 'Following';
                this.style.backgroundColor = '#e0e0e0';
            });
        });

        document.querySelector('.search-btn').addEventListener('click', function() {
            const searchTerm = document.querySelector('.search-input').value;
            const storeType = document.querySelector('select').value;
            console.log('Searching for:', searchTerm, 'Type:', storeType);
        });

function getAllStores() {
  const body = {
    type: 'GetStores'
  }

  return sendRequest(body);
}

function followStore(apikey, storeID) {
  const body = {
    type: 'Follow',
    apikey: apikey,
    store_id: storeID
  }

  return sendRequest(body);
}

function getFollowingStores(apikey) {
    const body = {
        type: 'GetFollowing',
        apikey: apikey
    }

    return sendRequest(body);
}

function unfollowStore(apikey) {
    const body = {
        type: 'GetFollowing',
        apikey: apikey
    }

    return sendRequest(body);
}

//searches for stores
function getFilteredStores(store_id, name) {
    const body = {
      type: 'getFilteredStores'
    }
    if (store_id != undefined) {
      body.store_id = store_id;
    }
    if (name != undefined) {
      body.name = name;
    }
    return sendRequest(body);
}

//calls the api adn returns a json object of whatever the api returns
function sendRequest(body) {
  const request = new XMLHttpRequest;

  request.onload = function () {
    if (this.readyState == 4) {
      if (this.status == 200) {
        return JSON.parse(this.responseText);
      } else {
        try {
          const repsonse = JSON.parse(this.responseText)
          return repsonse;
        } catch {
          //only returns text if the error cannot be made into a JSON object
          return this.responseText;
        }
      }
    }
  }

  request.open("POST", process.env.API_Location, true);
  request.setRequestHeader("Content-Type","application/json");
  request.send(JSON.stringify(body));
}