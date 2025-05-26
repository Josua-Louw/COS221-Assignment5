const apiUrl = "http://localhost/COS221-Assignment5/api/api.php"; // Replace with actual API URL

var allStores = [];

async function getStores() {
    
    
   try {
   const response = await fetch(apiUrl, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({
        type: 'GetStores'
    })
});

    const contentType = response.headers.get('content-type');

    if (contentType && contentType.includes('application/json')) {
        const result = await response.json();

        if (response.ok && result.status === 'success') {
            allStores = result.data;
            displayStores(result.data);
        } else {
            console.error('API error:', result.message || 'Unknown error');
        }
    } else {
        // Not JSON — probably an HTML error page
        const text = await response.text();
        console.error('Expected JSON, got:', text);
    }
} catch (err) {
    console.error('Fetch error:', err);
}
}

function displayStores(stores) {
    const container = document.querySelector('.store-list');
    container.innerHTML = '';
    console.log(stores);
    stores.forEach(store => {
        const storeCard = document.createElement('div');
        storeCard.classList.add('store-card');

        storeCard.innerHTML = `
            
            <div class="store-info">
                <h2 class="store-name">${store.name}</h2>
                <span class="store-type">${store.type}</span>
                <p>Explore a wide range of Stores at ${store.name}.</p>
                <div class="store-actions">
                    <a href="${store.url}" target="_blank" class="btn btn-visit">Visit Store</a>
                    <button class="btn btn-follow" data-store-id="${store.store_id}">Follow</button>
                </div>
            </div>
        `;
        container.appendChild(storeCard);
    });

    attachFollowListeners();
}

function attachFollowListeners() {
    document.querySelectorAll('.btn-follow').forEach(button => {
        button.addEventListener('click', function () {
            const storeId = this.getAttribute('data-store-id');
           if (this.textContent === 'Follow') {
                console.log('Following store:', storeId);
                this.textContent = 'Unfollow';
                this.style.backgroundColor = '#e0e0e0'; 
            } else {
                console.log('Unfollowing store:', storeId);
                this.textContent = 'Follow';
                this.style.backgroundColor = ''; 
            } 
        });
    });
}
function filterStores() {
  const query = document.getElementById("filter-input").value.toLowerCase();
  const selectedType = document.getElementById("filter-dropdown").value;

  let filteredStores;
  if (selectedType === "All") {
    filteredStores = allStores.filter(store => {
      const name = store.name || "";
      return name.toLowerCase().includes(query);
    });
  } else {
    
    filteredStores = allStores.filter(store => {
      const matchesName = store.name.toLowerCase().includes(query);
      const matchesType = store.type === selectedType;
      return matchesName && matchesType;
    });
  }
  displayStores(filteredStores);
}



window.addEventListener('DOMContentLoaded', getStores);
document.getElementById("filter-dropdown").addEventListener("change", filterStores);
document.getElementById("filter-input").addEventListener("input", filterStores);

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
