

function getAllProducts() {
  const body = {
    type: 'GetAllProducts'
  }

  return sendRequest(body);
}

//get products according to a filter. just use undefined for the items not being filtered.
function getFilteredProducts(prod_id, brand, category, min_price, max_price, search, store_id) {
    const body = {
        type: 'GetFilteredProducts'
    }

    if (prod_id != undefined) {
        body.prod_id = prod_id;
    }
    if (brand != undefined) {
        body.brand = brand;
    }
    if (category != undefined) {
        body.category = category;
    }
    if (min_price != undefined) {
        body.min_price = min_price;
    }
    if (max_price != undefined) {
        body.max_price = max_price;
    }
    if (search != undefined) {
        body.search = search;
    }
    if (store_id != undefined) {
        body.store_id = store_id;
    }

    return sendRequest(body);
}

function getBrands() {
    const body = {
        type: 'GetBrands'
    }
    return sendRequest(body);
}

//this function is a special function which uses multiple api requests to get all the products from all the stores that the user follows
//if there is an error the function send the error to the caller
function filterProductsOnStoresUserFollows(apikey) {
    const body = {
        type: 'GetFollowing',
        apikey: apikey
    }
    const stores = sendRequest(body);
    if (stores.status != 'success') {
        return stores;
    }

    const storesData = stores.data;
    const returnData = {
        status: 'success',
        data: []
    }
    storesData.forEach(store => {
        const requestData = getFilteredProducts(undefined, undefined, undefined, undefined, undefined, undefined, store.store_id);
        if (requestData.status != 'success') {
            return data;
        }
        const data = requestData.data;
        data.forEach(product => {
            returnData.data.push(product);
        });
    });

    return returnData;
}