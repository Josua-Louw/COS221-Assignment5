// const apiUrl = "http://localhost/COS221-Assignment5/api/api.php"; // Replace with actual API URL

// var allStores = [];
// const apiKey = sessionStorage.getItem('apiKey');

// async function getStores() {


//     try {
//         const response = await fetch(apiUrl, {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/json'
//             },
//             body: JSON.stringify({
//                 type: 'GetStores'
//             })
//         });

//         const contentType = response.headers.get('content-type');

//         if (contentType && contentType.includes('application/json')) {
//             const result = await response.json();

//             if (response.ok && result.status === 'success') {
//                 allStores = result.data;
//                 displayStores(result.data);
//             } else {
//                 console.error('API error:', result.message || 'Unknown error');
//             }
//         } else {
//             // Not JSON — probably an HTML error page
//             const text = await response.text();
//             console.error('Expected JSON, got:', text);
//         }
//     } catch (err) {
//         console.error('Fetch error:', err);
//     }
// }

// function displayStores(stores) {
//     const container = document.querySelector('.store-list');
//     container.innerHTML = '';
//     // console.log(stores);
//     stores.forEach(store => {
//         const storeCard = document.createElement('div');
//         storeCard.classList.add('store-card');

//         storeCard.innerHTML = `
            
//             <div class="store-info">
//                 <h2 class="store-name">${store.name}</h2>
//                 <span class="store-type">${store.type}</span>
//                 <p>Explore a wide range of Stores at ${store.name}.</p>
//                 <div class="store-actions">
//                     <a href="${store.url}" target="_blank" class="btn btn-visit">Visit Store</a>
//                     <button class="btn btn-follow" data-store-id="${store.store_id}">Follow</button>
//                 </div>
//             </div>
//         `;
//         container.appendChild(storeCard);
//     });

//     attachFollowListeners();
// }

// function attachFollowListeners() {
//     document.querySelectorAll('.btn-follow').forEach(button => {
//         button.addEventListener('click', async function () {
//             const storeId = this.getAttribute('data-store-id');
//             if (this.textContent === 'Follow') {
//                 // console.log('Following store:', storeId);
//                 try {
//                     const response = await fetch(apiUrl, {
//                         method: 'POST',
//                         headers: {
//                             'Content-Type': 'application/json'
//                         },
//                         body: JSON.stringify({
//                             type: 'Follow',
//                             apikey: apiKey,
//                             store_id: storeId
//                         })
//                     });
//                     if(!response.ok){
//                         // console.log("Request error");
//                     } else {
//                         this.textContent = 'Unfollow';
//                         this.style.backgroundColor = '#e0e0e0';
//                     }
//                 } catch (error) {
//                     // console.log(error);
//                 }

//             } else {
//                 // console.log('Unfollowing store:', storeId);
//                 try {
//                     const response = await fetch(apiUrl, {
//                         method: 'POST',
//                         headers: {
//                             'Content-Type': 'application/json'
//                         },
//                         body: JSON.stringify({
//                             type: 'Unfollow',
//                             apikey: apiKey,
//                             store_id: storeId
//                         })
//                     });
//                     if(!response.ok){
//                         // console.log("Request error");
//                     } else {
//                         this.textContent = 'Follow';
//                         this.style.backgroundColor = '';
//                     }
//                 } catch (error) {
//                     // console.log(error);
//                 }
//             }
//         });
//     });
// }
// function filterStores() {
//     const query = document.getElementById("filter-input").value.toLowerCase();
//     const selectedType = document.getElementById("filter-dropdown").value;

//     let filteredStores;
//     if (selectedType === "All") {
//         filteredStores = allStores.filter(store => {
//             const name = store.name || "";
//             return name.toLowerCase().includes(query);
//         });
//     } else {

//         filteredStores = allStores.filter(store => {
//             const matchesName = store.name.toLowerCase().includes(query);
//             const matchesType = store.type === selectedType;
//             return matchesName && matchesType;
//         });
//     }
//     displayStores(filteredStores);
// }

// window.addEventListener('DOMContentLoaded', getStores);
// document.getElementById("filter-dropdown").addEventListener("change", filterStores);
// document.getElementById("filter-input").addEventListener("input", filterStores);


const apiUrl = "http://localhost/COS221-Assignment5/api/api.php"; // Replace with actual API URL

var allStores = [];
const apiKey = sessionStorage.getItem('apikey');

async function getStores() {
    if (apiKey) {
        await fetchFollowedStores();
    }

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
    // console.log(stores);
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
                    <button class="btn btn-follow ${isFollowing ? 'unfollow' : ''}" data-store-id="${store.store_id}">
                        ${isFollowing ? 'Unfollow' : 'Follow'}
                    </button>
                </div>
            </div>
        `;
        container.appendChild(storeCard);
    });

    attachFollowListeners();
}

let followedStoreIds = [];


async function fetchFollowedStores() {
    if (!apiKey) return;

    try {
        const response = await fetch(apiUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                type: 'GetFollowing',
                apikey: apiKey
            })
        });

        if (response.ok) {
            const result = await response.json();
            if (result.status === 'success') {
                followedStoreIds = result.data.map(store => store.store_id);
            } else {
                console.error("Error fetching followed stores:", result.message);
            }
        } else {
            console.error("Failed to fetch followed stores");
        }
    } catch (error) {
        console.error("Fetch error:", error);
    }
}

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
                        this.classList.remove('unfollow');
                        followedStoreIds = followedStoreIds.filter(id => id != storeId);
                        console.log('Unfollowed store:', storeId);
                    } else {
                        this.textContent = 'Unfollow';
                        this.classList.add('unfollow');
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



document.getElementById("Follow_products").addEventListener("change", async function () {
    const selected = this.value;

    let requestType = "";
    
    console.log(selected);
    if (selected != "1") {
        requestType = "GetStores";
    } 
    else {
        requestType = "GetFollowing";
    }

    try {
        const res = await fetch("http://localhost/COS221-Assignment5/api/api.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                type: requestType,
                apikey: apiKey
            })
        });

        const data = await res.json();

        if (data.status === "success") {
            console.log("Products:", data.data);
            displayStores(data.data); 
        } else {
            console.error("Error from API:", data.message);
        }

    } catch (err) {
        console.error("Fetch failed:", err);
    }
});



