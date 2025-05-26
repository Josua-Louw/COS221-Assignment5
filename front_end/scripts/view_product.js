document.addEventListener("DOMContentLoaded", async function () {
    const productData = JSON.parse(sessionStorage.getItem("selectedProduct"));
    const user = JSON.parse(sessionStorage.getItem("user"));
    if (!productData || !productData.prod_id || !user || !user.apikey) {
        alert("Missing product or user data.");
        return;
    }

    const productID = productData.prod_id;
    const apikey = user.apikey;

    document.getElementById("product-title").textContent = productData.title ||"No Title";
    document.getElementById("product-description").textContent = productData.description ||"No Description";
    document.getElementById("product-price").textContent = `R${productData.price || "0.00"}`;
    document.getElementById("main-image").src = productData.thumbnail || "https://via.placeholder.com/300";
    const externalLink = document.getElementById("external-link");

    if (productData.product_link) {
        externalLink.href = productData.product_link;
        externalLink.style.display = "inline-block";
    } else {
        externalLink.style.display = "none";
    }

    const ratingDisplay = document.getElementById("rating-stars");
    const reviewList = document.getElementById("review-list");
    const ratingInput = document.getElementById("rating-input");
    const commentInput = document.getElementById("comment-input");
    const submitBtn = document.getElementById("submit-review-btn");
    const updateBtn = document.getElementById("update-review-btn");
    const deleteBtn = document.getElementById("delete-review-btn");

    let userRatingId = null;

    // Load ratings and user's review
    try {
        const response = await getRatingsForProduct(productID);
        if (response.status === "success") {
            const ratings = response.data;
            if (ratings.length > 0) {
                const total = ratings.reduce((sum, r) => sum + parseFloat(r.rating), 0);
                const avg = total / ratings.length;
                ratingDisplay.textContent = `${avg.toFixed(1)} / 5`;
                reviewList.innerHTML = "";
                ratings.forEach((r, index) => {
                    const div = document.createElement("div");
                    div.classList.add("review-item");
                    div.innerHTML = `<strong>${r.name}</strong> - ${r.rating}/5<br><p>${r.comment}</p>`;
                    reviewList.appendChild(div);

                    if (r.name === user.name) {
                        ratingInput.value = r.rating;
                        commentInput.value = r.comment;
                        userRatingId = r.rating_id;
                        submitBtn.style.display = "none";
                        updateBtn.style.display = "inline-block";
                        deleteBtn.style.display = "inline-block";
                    }
                });
            } else {
                reviewList.textContent = "No reviews yet.";
            }
        } else {
            ratingDisplay.textContent = "-";
            reviewList.textContent = "No reviews yet.";
        }
    } catch (error) {
        console.error("Rating fetch error:", error);
        reviewList.textContent = "Failed to load reviews.";
    }

    submitBtn.onclick = async () => {
        const rating = parseFloat(ratingInput.value);
        const comment = commentInput.value;
        if (!rating || !comment) return alert("Please enter a rating and comment.");

        const res = await sendRequest({
            type: "SubmitRating",
            apikey,
            prod_id: productID,
            rating,
            comment
        });

        if (res.status === "success") {
            alert("Review submitted.");
            location.reload();
        } else {
            alert(res.message || "Submission failed.");
        }
    };

    updateBtn.onclick = async () => {
        const rating = parseFloat(ratingInput.value);
        const comment = commentInput.value;
        if (!userRatingId) return;

        const res = await sendRequest({
            type: "EditRating",
            apikey,
            rating_id: userRatingId,
            rating,
            comment
        });

        if (res.status === "success") {
            alert("Review updated.");
            location.reload();
        } else {
            alert(res.message || "Update failed.");
        }
    };

    deleteBtn.onclick = async () => {
        if (!userRatingId) return;

        const res = await sendRequest({
            type: "DeleteRating",
            apikey,
            rating_id: userRatingId
        });

        if (res.status === "success") {
            alert("Review deleted.");
            location.reload();
        } else {
            alert(res.message || "Delete failed.");
        }
    };
});

// Helpers
function getRatingsForProduct(productID) {
    return sendRequest({
        type: "GetRatings",
        prod_id: productID
    });
}

async function sendRequest(body) {
    try {
        const response = await fetch("http://localhost/COS221-Assignment5/api/api.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(body)
        });
        return await response.json();
    } catch (error) {
        console.error("API error:", error);
        return { status: "error", message: error.message };
    }
}



