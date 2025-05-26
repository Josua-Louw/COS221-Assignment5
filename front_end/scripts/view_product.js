
    const apiUrl = "http://localhost/COS221-Assignment5/api/api.php"; // Replace with actual API URL

    var allStores = [];
    const apiKey = sessionStorage.getItem('apiKey');


    document.addEventListener("DOMContentLoaded", async function () {
        const productData = JSON.parse(sessionStorage.getItem("selectedProduct"));

        if (!productData || !productData.prod_id) {
            alert("No product selected.");
            return;
        }

        const productID = productData.prod_id;

        //populate UI with stored product info
        document.getElementById("product-title").textContent = productData.title || "No Title";
        document.getElementById("product-price").textContent = `R${productData.price || "0.00"}`;
        document.getElementById("main-image").src = productData.thumbnail || "https://via.placeholder.com/300";

        //set up external online store link if it is available
        // const externalLink = document.getElementById("external-link");
        // if (productData.product_link) {
        //     externalLink.href = productData.product_link;
        //     externalLink.style.display = "inline-block"; //Show button if link available
        // } else{
        //     externalLink.style.display = "none";  //Hide if no link
        // }

        // const thumbnailContainer = document.getElementById("thumbnail-container");
        // if (productData.images && productData.images.length > 0) {
        //     productData.images.forEach((src) => {
        //         const img = document.createElement("img");
        //         img.src = src;
        //         img.alt = "Thumbnail";
        //         img.width = 100;
        //         img.style.cursor = "pointer";
        //         img.addEventListener("click", () => {
        //             document.getElementById("main-image").src = src;
        //         });
        //         thumbnailContainer.appendChild(img);
        //     });
        // }

        // //Fetch ratings(does not work yet)
        // try {
        //     const ratings = await getRatingsForProduct(productID);
        //     const ratingDisplay = document.getElementById("rating-stars");
        //     if (ratings && ratings.success && ratings.data && ratings.data.average != null) {
        //         const avgRating = ratings.data.average;
        //         ratingDisplay.textContent = `${avgRating.toFixed(1)} / 5`;
        //     }
        // } catch (error) {
        //     console.error("Error loading ratings:", error);
        // }
    });

    // function getRatingsForProduct(productID) {
    //     const body = {
    //         type: 'GetRatings',
    //         prod_id: productID
    //     };
    //     return sendRequest(body);
    // }

    // function sendRequest(body) {
    //     return new Promise((resolve, reject) => {
    //         const request = new XMLHttpRequest();

    //         console.log(" " + this.responseText);
    //         request.onreadystatechange = function () {
    //             if (this.readyState === 4) {
    //                 try {
    //                     const response = JSON.parse(this.responseText);
    //                     resolve(response);
    //                 } catch (err) {
    //                     reject("Invalid JSON from server");
    //                 }
    //             }
    //         };

    //         request.open("POST", "http://localhost/COS221-Assignment5/api/api.php", true);
    //         request.setRequestHeader("Content-Type", "application/json");
    //         request.send(JSON.stringify(body));
    //     });
    // }


    document.addEventListener("DOMContentLoaded", function () 
    {
        const addRatingBtn = document.querySelector(".Add_rating");
        const ratingForm = document.getElementById("ratingForm");

        addRatingBtn.addEventListener("click", function () {
            ratingForm.style.display = "block";
        });
    });

    // async function submitRating() {
    //   const rating = document.getElementById("rating").value;
    //   const comment = document.getElementById("comment").value;
    //   const prod_id = new URLSearchParams(window.location.search).get("prod_id");
    //   const apikey = sessionStorage.getItem("apikey");

    // //   if (!apikey || !prod_id || !rating || comment.trim() === "") {
    // //     alert("❌ Please fill in all fields.");
    // //     return;
    // //   }

    //   const body = {
    //     studentnum: "u24566170",
    //     apikey: "36cf8d0b74a40ef80d4dad5817770faf",
    //     type: "SubmitRating",
    //     prod_id: 3,
    //     rating: 4,
    //     comment: "Good"
    //   };

    //   const res = await sendRequest(body);

    //   if (res.status === "success") {
    //     alert("✅ Rating successfully submitted!");
    //     document.getElementById("ratingForm").style.display = "none";
    //     document.getElementById("rating").value = "";
    //     document.getElementById("comment").value = "";
    //   } else {
    //     alert("❌ " + res.message);
    //   }
    // }



    async function submitRating() {
    const rating = document.getElementById("rating").value;
    const comment = document.getElementById("comment").value;
    const prod_id = JSON.parse(sessionStorage.getItem("selectedProduct"))?.prod_id;
    const apikey = sessionStorage.getItem("apikey");

    if (!rating || !comment || !prod_id || !apikey) {
        alert(" Please complete all fields.");
        return;
    }

    try {
        const response = await fetch(apiUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                studentnum: "u24566170",
                apikey: apikey,
                type: "SubmitRating",
                prod_id: prod_id,
                rating: parseInt(rating),
                comment: comment
            })
        });

        const result = await response.json();

        if (response.ok && result.status === 'success') {
            alert("Rating successfully submitted!");
            document.getElementById("ratingForm").style.display = "none";
            document.getElementById("rating").value = "";
            document.getElementById("comment").value = "";

            const ratingsDiv = document.querySelector(".All_Rating");
            ratingsDiv.innerHTML = "<h3 class='All_Rating'>All the ratings of the product</h3>"; 
            loadRatings(prod_id);
        } else {
            alert(" " + (result.message || "Unknown error"));
        }
    } catch (err) {
        console.error(" Fetch error:", err);
        alert(" Failed to submit rating.");
    }
}




    document.addEventListener("DOMContentLoaded", function () {
        const submitBtn = document.getElementById("submitRating");

        submitBtn.addEventListener("click", function (e) {
            e.preventDefault();
            submitRating();
        });
    });


    const productData = JSON.parse(sessionStorage.getItem("selectedProduct"));
    if (productData && productData.prod_id) {
        loadRatings(productData.prod_id);
    }


  async function loadRatings(prod_id) {
    const res = await fetch("http://localhost/COS221-Assignment5/api/api.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            type: "GetRatings",
            prod_id: prod_id
        })
    });

    const data = await res.json();

    if (data.status === "success") {
        const ratingsDiv = document.querySelector(".All_Rating");
        ratingsDiv.innerHTML = "";
        if (data.data.length === 0) {
            ratingsDiv.innerHTML = "<p>No ratings yet.</p>";
            return;
        }

        data.data.forEach(r => {
            const ratingValue = parseInt(r.rating);
            const stars = "⭐".repeat(ratingValue);
            const name = r.name || "Anonymous";

            const p = document.createElement("p");
            p.innerHTML = `${name}: ${stars} — ${r.comment}`;
            ratingsDiv.appendChild(p);
        });
    } else {
        console.error("Failed to fetch ratings:", data.message);
    }
}


