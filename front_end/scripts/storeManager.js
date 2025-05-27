
const apiKey = sessionStorage.getItem('apikey');
//Pop up functionality
 function openPopup() {
            document.getElementById("popup").style.display = "flex";
        }

function closePopup() {
            document.getElementById("popup").style.display = "none";
        }
//Send req to API
function sendRequest(body) {
    return fetch('http://localhost/COS221-Assignment5/api/api.php', {          
        method: 'POST',
        headers: {
                'Content-Type': 'application/json'  
            },
        body: JSON.stringify(body)
  })
  .then(response => response.json());
}
//For initial creation of store
function registerStoreOwner(apikey, store_name, store_url, store_type, registrationNo) {
    const body = {
        type: 'RegisterStoreOwner',
        apikey: apikey,
        store_name: store_name,
        store_url: store_url,
        store_type: store_type,
        registrationNo: registrationNo
    }
    return sendRequest(body);
}

//filters products based on the stores products
function getStoresProducts(store_id) {
    const body = {
        type: 'GetFilteredProducts',
        store_id: store_id
    }
    return sendRequest(body);
}

function addProductToStore(title, price, product_link, description, thumbnail, category, brand_name, store_id, apikey) {
    const body = {
        type: 'AddProduct',
        title: title,
        price: price,
        product_link: product_link,
        description: description,
        thumbnail: thumbnail,
        category: category,
        brand_name: brand_name,
        store_id: store_id,
        apikey: apikey
    }
    return sendRequest(body);
}

function deleteProductFromStore(prod_id, store_id, apikey) {
    const body = {
        type: 'DeleteProduct',
        prod_id: prod_id,
        store_id: store_id,
        apikey: apikey
    }
    return sendRequest(body);
}

function editProductInStore(prod_id, title, price, product_link, description, thumbnail, category, brand_name, store_id, apikey) {
    const body = {
        type: 'EditProduct',
        prod_id: prod_id,
        store_id: store_id,
        apikey: apikey
    }
    if (title != undefined) body.title = title;
    if (price != undefined) body.price = price;
    if (product_link != undefined) body.product_link = product_link;
    if (description != undefined) body.description = description;
    if (thumbnail != undefined) body.thumbnail = thumbnail;
    if (category != undefined) body.category = category;
    if (brand_name != undefined) body.brand_name = brand_name;

    return sendRequest(body);
}

//gets the stores that the user owns.
function getStoreUserOwns(apiKey) {
    const body = {
        type: 'GetUsersStore',
        apikey: apiKey
    }
    return sendRequest(body);
}
function displayStores(stores) {

    const container = document.querySelector('.store-list');
    container.innerHTML = '';
    console.log(stores);
    stores.forEach(store => {
        const storeCard = document.createElement('div');
        storeCard.classList.add('store-card');
        const isFollowing = followedStoreIds.includes(store.store_id);
        storeCard.innerHTML = `
            <div class="store-info">
                <h2 class="store-name">${store.name}</h2>
                <span class="store-type">${store.type}</span>
                <p>Explore a wide range of Stores at ${store.name}.</p>
                <div class="store-actions">
                    <a href="${store.url}" target="_blank" class="btn btn-visit">Visit Store</a>
                    <button class="btn btn-follow" data-store-id="${store.store_id}" style="background-color: ${isFollowing ? '#e0e0e0' : ''}">
                        ${isFollowing ? 'Unfollow' : 'Follow'}
                    </button>
                </div>
            </div>
        `;
        container.appendChild(storeCard);
    });

    attachFollowListeners();
}


getStoreUserOwns(apiKey)
    .then(result => {
        if (result.status === "success") {
            console.log("User owns a store:", result.data);
            displayStores(result.data);
        } else if (
            result.status === "error" &&
            result.message === "This user is not a store owner"
        ) {
            console.log("User does not own a store.");
           
        } else {
            console.error("Unexpected error:", result.message);
        }
    })
    .catch(error => {
        console.error("Request failed or invalid JSON:", error);
    });

document.getElementById('submit-btn').addEventListener('click', function (e) {
    e.preventDefault();

    
    const store_name = document.getElementById("store_name").value;
    const store_url = document.getElementById("store_url").value;
    const store_type = document.getElementById("filter-dropdown").value;
    const registrationNo = document.getElementById("store_reg").value;

    registerStoreOwner(apiKey, store_name, store_url, store_type, registrationNo)
        .then(result => {
            console.log(result);
            getStoreUserOwns(apiKey);
        })
        .catch(error => {
            console.error("Error registering store owner:", error);
        });
});

