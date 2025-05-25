document.addEventListener("DOMContentLoaded", async function () {
    const productData = JSON.parse(sessionStorage.getItem("selectedProduct"));

    if (!productData || !productData.prod_id) {
        alert("No product selected.");
        return;
    }

    const productID = productData.prod_id;

    //populate UI with stored product info
    document.getElementById("product-title").textContent = productData.title || "No Title";
    document.getElementById("product-description").textContent = productData.description || "No Description";
    document.getElementById("product-price").textContent = `R${productData.price || "0.00"}`;
    document.getElementById("main-image").src = productData.thumbnail || "https://via.placeholder.com/300";

    //set up external online store link if it is available
    const externalLink = document.getElementById("external-link");
    if (productData.product_link) {
        externalLink.href = productData.product_link;
        externalLink.style.display = "inline-block"; //Show button if link available
    } else{
        externalLink.style.display = "none";  //Hide if no link
    }

    const thumbnailContainer = document.getElementById("thumbnail-container");
    if (productData.images && productData.images.length > 0) {
        productData.images.forEach((src) => {
            const img = document.createElement("img");
            img.src = src;
            img.alt = "Thumbnail";
            img.width = 100;
            img.style.cursor = "pointer";
            img.addEventListener("click", () => {
                document.getElementById("main-image").src = src;
            });
            thumbnailContainer.appendChild(img);
        });
    }

    //Fetch ratings(does not work yet)
    try {
        const ratings = await getRatingsForProduct(productID);
        const ratingDisplay = document.getElementById("rating-stars");
        if (ratings && ratings.success && ratings.data && ratings.data.average != null) {
            const avgRating = ratings.data.average;
            ratingDisplay.textContent = `${avgRating.toFixed(1)} / 5`;
        }
    } catch (error) {
        console.error("Error loading ratings:", error);
    }
});

function getRatingsForProduct(productID) {
    const body = {
        type: 'GetRatings',
        prod_id: productID
    };
    return sendRequest(body);
}

function sendRequest(body) {
    return new Promise((resolve, reject) => {
        const request = new XMLHttpRequest();

        request.onreadystatechange = function () {
            if (this.readyState === 4) {
                try {
                    const response = JSON.parse(this.responseText);
                    resolve(response);
                } catch (err) {
                    reject("Invalid JSON from server");
                }
            }
        };

        request.open("POST", API_Location, true);
        request.setRequestHeader("Content-Type", "application/json");
        request.send(JSON.stringify(body));
    });
}



