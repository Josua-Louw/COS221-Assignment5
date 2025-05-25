

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
function getStoreUserOwns(apikey) {
    const body = {
        type: 'GetUsersStores',
        apikey: apikey
    }
    return sendRequest(body);
}