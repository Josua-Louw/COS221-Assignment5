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
