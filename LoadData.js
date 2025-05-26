const response = fetch('electronicsSearch.json').then(response => response.json()).then( data => {
    const results = data.shopping_results;
    console.log(response);
    var i = 2;
    results.forEach(result => {
    const addData = new XMLHttpRequest;

    addData.onload = function () {
        console.log(result.title);
        console.log(this.responseText);
    }

    var addDataBody = {
        "type": "AddProduct",
        "prod_id": i,
        "category": "electronics",
        "title": result.title,
        "price": result.extracted_price,
        "product_link": result.product_link,
        "thumbnail": result.thumbnail,
        "storeName": result.source
    };

    i++;
    
    addData.open("POST", "api/api.php");
    addData.setRequestHeader("Content-Type","application/json");
    addData.send(JSON.stringify(addDataBody));

    });
}
);


