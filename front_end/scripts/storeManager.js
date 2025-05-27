const apiUrl = "http://localhost/COS221-Assignment5/api/api.php";
const apiKey = sessionStorage.getItem('apikey');
//Pop up functionality
function openPopup() {
    document.getElementById("popup").style.display = "flex";
}

function closePopup() {
    document.getElementById("popup").style.display = "none";
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
    };
    console.log({
        apikey,
        store_name,
        store_url,
        store_type,
        registrationNo
    });

    return sendRequest(body);
}

//filters products based on the stores products
function getStoresProducts(store_id) {
    const body = {
        type: 'GetFilteredProducts',
        store_id: store_id
    }
    console.log("getStoresProducts body:", body);
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
async function fetchFollowedStores() {
    if (!apiKey) return [];

    try {
        const response = await fetch(apiUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ type: 'GetFollowing', apikey: apiKey })
        });

        if (response.ok) {
            const result = await response.json();
            if (result.status === 'success') {
                
                return result.data.map(store => store.store_id);
            } else {
                console.error("Error fetching followed stores:", result.message);
                return [];
            }
        } else {
            console.error("Failed to fetch followed stores");
            return [];
        }
    } catch (error) {
        console.error("Fetch error:", error);
        return [];
    }
}

let followedStoreIds = [];
function attachFollowListeners() {
    document.querySelectorAll('.btn-follow').forEach(button => {
        button.addEventListener('click', async function () {
            const storeId = this.getAttribute('data-store-id');
            const isFollowing = followedStoreIds.includes(parseInt(storeId));


            if (!apiKey) {
                alert('Log in to be able to follow');
                return;
            }

            try {
                const response = await fetch(apiUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        type: isFollowing ? 'Unfollow' : 'Follow',
                        apikey: apiKey,
                        store_id: storeId
                    })
                });

                if (response.ok) {

                    if (isFollowing) {
                        this.textContent = 'Follow';
                        this.style.backgroundColor = '';
                        followedStoreIds = followedStoreIds.filter(id => id != storeId);
                        console.log('Unfollowed store:', storeId);
                    } else {
                        this.textContent = 'Unfollow';
                        this.style.backgroundColor = '#e0e0e0';
                        followedStoreIds.push(parseInt(storeId));
                        console.log('Followed store:', storeId);
                    }
                } else {
                    console.log(`${isFollowing ? 'Unfollow' : 'Follow'} request error`);
                }
            } catch (error) {
                console.log(error);
            }
        });
    });
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

function displayProducts(products) {
    let productContainer = document.querySelector('.product-list');
    if (!productContainer) {
        productContainer = document.createElement('div');
        productContainer.className = 'product-list';
        document.body.appendChild(productContainer); 
    }
    productContainer.innerHTML = '';
    if (products.length === 0) {
        productContainer.innerHTML = '<p>No products available for this store.</p>';
        return;
    }
    products.forEach(product => {
        const productCard = document.createElement('div');
        productCard.classList.add('product-card');
        productCard.innerHTML = `
            <h3>${product.title}</h3>
            <p>${product.description}</p>
            <p>Price: ${product.price}</p>
            <a href="${product.product_link}" target="_blank">View Product</a>
        `;
        productContainer.appendChild(productCard);
    });
}

// After displaying the store, fetch and display its products
function displayStoreAndProducts(store) {
    displayStores([store]);
    console.log("displayStoreAndProducts store object:", store);
    getStoresProducts(store.store_id)
        .then(result => {
            if (result.status === "success" && Array.isArray(result.data)) {
                displayProducts(result.data);
            } else {
                displayProducts([]);
            }
        })
        .catch(error => {
            console.error("Error fetching products:", error);
            displayProducts([]);
        });
}

Promise.all([getStoreUserOwns(apiKey), fetchFollowedStores()])
    .then(([storeResult, followedIds]) => {
        if (storeResult.status === "success") {
            followedStoreIds = followedIds || [];
            if (Array.isArray(storeResult.data) && storeResult.data.length > 0) {
                displayStoreAndProducts(storeResult.data[0]);
            } else if (storeResult.data !== null) {
                displayStoreAndProducts(storeResult.data);
            } else {
                displayStores([]);
                displayProducts([]);
            }
        } else {
            console.error("Unexpected error:", storeResult.message);
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

    if (!store_name || !store_url || !store_type || !registrationNo) {
        alert("Please fill in all fields.");
        return;
    }

    registerStoreOwner(apiKey, store_name, store_url, store_type, registrationNo)
  .then(result => {
    console.log("Store registration result:", result);

    if (result.status === "success") {
      // Clear form inputs
      document.getElementById("store_name").value = "";
      document.getElementById("store_url").value = "";
      document.getElementById("filter-dropdown").value = "";
      document.getElementById("store_reg").value = "";

      closePopup();

      // Fetch the store the user owns
      return getStoreUserOwns(apiKey);
    } else {
      // Error-specific handling
      if (result.message?.toLowerCase().includes("already owns a store")) {
        alert("You already have a store registered.");
      } else {
        alert("Failed to register store: " + result.message);
      }

      // Prevent next .then from running
      return null;
    }
  })
  .then(result => {
    if (!result) return; // skip if previous step failed

    if (result.status === "success") {
      const stores = Array.isArray(result.data)
        ? result.data
        : result.data !== null
          ? [result.data]
          : [];

      displayStores(stores);
    } else {
      alert("Failed to fetch your store data after registration.");
      console.error("Store fetch error:", result);
    }
  })
  .catch(error => {
    console.error("Unexpected error during store registration or fetch:", error);
    alert("An unexpected error occurred. Please try again later.");
  });
});

