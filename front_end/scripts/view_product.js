
    const apiUrl = "http://localhost/COS221-Assignment5/api/api.php"; // Replace with actual API URL

    var allStores = [];
    const apiKey = sessionStorage.getItem('apikey');
    const user_id = JSON.parse(sessionStorage.getItem('user')).user_id;

    


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
        const externalLink = document.getElementById("external-link");
        if (productData.product_link) {
            externalLink.href = productData.product_link;
            externalLink.style.display = "inline-block"; //Show button if link available
        } else{
            externalLink.style.display = "none";  //Hide if no link
        }

    });


    document.addEventListener("DOMContentLoaded", function () 
    {
        const addRatingBtn = document.querySelector(".Add_rating");
        const ratingForm = document.getElementById("ratingForm");

        addRatingBtn.addEventListener("click", function () {
            ratingForm.style.display = "block";
        });
    });


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
        let typeSet = "SubmitRating";
        let button_rating = document.getElementById("rating_button_submit");
        if (button_rating.textContent === "Edit Rating"){
            typeSet = "EditRating";
        }
        const response = await fetch(apiUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                studentnum: "u24566170",
                apikey: apikey,
                type: typeSet,
                prod_id: prod_id,
                rating: parseInt(rating),
                comment: comment
            })
        });

        const result = await response.json();

        if (response.ok && result.status === 'success') {
            if (typeSet === "EditRating") {
                alert("Your review has been updated!");
                    } else {
                alert("Rating successfully submitted!");
            }
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
    const res = await fetch(apiUrl, {
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

    const ratingsDiv = document.querySelector(".All_Rating");
    const averageRatingElement = document.getElementById("average-rating");

    if (data.status === "success") {
        ratingsDiv.innerHTML = "<h3 class='All_Rating'>All the ratings of the product</h3>";

        if (data.data.length === 0) {
            ratingsDiv.innerHTML += "<p>No ratings yet.</p>";
            if (averageRatingElement) averageRatingElement.textContent = "No ratings yet.";
            return;
        }

        // ⭐ Calculate average rating
        const sum = data.data.reduce((total, r) => total + parseFloat(r.rating), 0);
        const avg = (sum / data.data.length).toFixed(1);
        const stars = "⭐".repeat(Math.round(avg));

        if (averageRatingElement) {
            averageRatingElement.innerHTML = `${stars} ${avg} / 5 <span class="review-count">(${data.data.length} review${data.data.length !== 1 ? 's' : ''})</span>`;
        }
        console.log(data);
        console.log(user_id);
        // Render each review
        data.data.forEach(r => {
            const ratingValue = parseInt(r.rating);
            const stars = "⭐".repeat(ratingValue);
            const name = r.name || "Anonymous";

            const p = document.createElement("p");
            p.innerHTML = `${name}: ${stars} — ${r.comment}`;
            ratingsDiv.appendChild(p);

            if(r.user_id===user_id){
                let button_rating = document.getElementById("rating_button_submit");
                button_rating.textContent = "Edit Rating";
            }

            if (r.user_id === user_id) {
                const deleteBtn = document.createElement("button");
                deleteBtn.textContent = "Delete";
                deleteBtn.classList.add("delete-rating-btn");

            deleteBtn.addEventListener("click", async () => {
                const confirmDelete = confirm("Are you sure you want to delete your rating?");
                if (!confirmDelete) return;

                try {
                    const response = await fetch(apiUrl, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            type: "DeleteRating",
                            apikey: apiKey,
                            rating_id: r.rating_id
                        })
                    });

                    const result = await response.json();

                    if (response.ok && result.status ==="success") {
                        alert("Rating deleted successfully.");
                        loadRatings(prod_id); //refresh list after deletion
                    } else {
                        alert("Failed to delete rating: " + (result.message || "Unknown error"));
                    }
                } catch (err) {
                    console.error("Delete error:", err);
                    alert("An error occurred while deleting the rating.");
                }
            });

            p.appendChild(deleteBtn);
        }
        });

    } else {
        console.error("Failed to fetch ratings:", data.message);
    }
}



