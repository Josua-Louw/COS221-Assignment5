// view_product.js


document.addEventListener("DOMContentLoaded", function () {
  //sample thumbnails (these will later be loaded from the API or DB)
  const thumbnails = [
    "https://via.placeholder.com/100?text=1",
    "https://via.placeholder.com/100?text=2",
    "https://via.placeholder.com/100?text=3"
  ];

  const mainImage = document.getElementById("main-image");
  const thumbnailContainer = document.getElementById("thumbnail-container");


  // Render thumbnails and add click behavior
  thumbnails.forEach((src) => {
    const img = document.createElement("img");
    img.src = src;
    img.alt = "Thumbnail";
    img.width = 100;
    img.style.cursor = "pointer";
    img.addEventListener("click", () => {
      mainImage.src = src;
    });
    thumbnailContainer.appendChild(img);
  });

  //only placeholder rating for now (replace with fetched average later)
  const ratingDisplay = document.getElementById("rating-stars");
  const dummyRating = 4;
  ratingDisplay.textContent = "★".repeat(dummyRating) + "☆".repeat(5 - dummyRating);

  //placeholder reviews (remove later)
  const reviews = [
    { user: "Alice", rating: 5, comment: "Great product! Highly recommend." },
    { user: "Bob", rating: 3, comment: "It was okay" },
    { user: "Charlie", rating: 4, comment: "Solid quality, good for the price." }
  ];

  const reviewList = document.getElementById("review-list");
  reviewList.innerHTML = ""; // clear 'Loading reviews...'
  reviews.forEach((review) => {
    const div = document.createElement("div");
    div.innerHTML = `<p><strong>${review.user}</strong> (${"★".repeat(review.rating)}${"☆".repeat(5 - review.rating)})<br>${review.comment}</p>`;
    reviewList.appendChild(div);
  });

  //Handle review submission (stub only for now)
  const reviewForm = document.getElementById("review-form");
  reviewForm.addEventListener("submit", function (e) {
    e.preventDefault();

    //check if user is logged in before they leave a review
    const user = JSON.parse(localStorage.getItem("user"));
    if (!user || !user.apikey) {
      alert("You must be logged in to review a product.");
      return;
    }

    //stop the same user from leaving multiple reviewss
    const existingReviews = Array.from(reviewList.children);
    const alreadyReviewed = existingReviews.some(div =>
      div.textContent.includes(user.name)
    );

    if (alreadyReviewed) {
      alert("You already submitted a review for this product.");
      return;
    }

    const comment = document.getElementById("review-text").value;
    const rating = document.getElementById("rating").value;


    if (!rating || !comment.trim()) return alert("Please complete the review.");

    //Display the submitted review instantly
    const div = document.createElement("div");
    div.innerHTML = `<p><strong>You</strong> (${"★".repeat(rating)}${"☆".repeat(5 - rating)})<br>${comment}</p>`;
    reviewList.prepend(div);

    reviewForm.reset();//clear form

    // TODO: Send review to backend when available
    console.log("Submitted review:", { comment, rating });
  });
});

function getProductByID(productID) {
  const body = {
    type: 'GetFilteredProducts',
	  prod_id: productID
  }

  return sendRequest(body);
}

function getRatingsForProduct(productID) {
  const body = {
    type: 'GetRatings',
	  prod_id: productID
  }

  return sendRequest(body)
}

function addRating(apikey, productID, rating, comment) {
  const body = {
    type: 'SubmitRating',
    apikey: apikey,
    prod_id: productID,
    rating: rating,
    comment: comment
  }

  return sendRequest(body);
}

function deleteRating(ratingID, apikey) {
  const body = {
    type: 'DeleteRating',
    rating_id: ratingID,
    apikey: apikey
  }

  return sendRequest(body);
}

function editRating(apikey, ratingID, rating, comment) {
  const body = {
    type: 'EditRating',
	  apikey: apikey,
	  rating_id: ratingID
  }

  if (rating != undefined) {
    body.rating = rating;
  }
  if (comment != undefined) {
    body.comment = comment;
  }

  return sendRequest(body);
}

//calls the api adn returns a json object of whatever the api returns
function sendRequest(body) {
  const request = new XMLHttpRequest;

  request.onload = function () {
    if (this.readyState == 4) {
      if (this.status == 200) {
        return JSON.parse(this.responseText);
      } else {
        try {
          const repsonse = JSON.parse(this.responseText)
          return repsonse;
        } catch {
          //only returns text if the error cannot be made into a JSON object
          return this.responseText;
        }
      }
    }
  }

  request.open("POST", API_Location, true);
  request.setRequestHeader("Content-Type","application/json");
  request.send(JSON.stringify(body));
}
