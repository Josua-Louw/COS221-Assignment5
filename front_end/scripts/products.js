document.addEventListener('DOMContentLoaded', function() {
    const PRODUCTS_PER_PAGE = 20;
    let currentPage = 1;
    let totalProducts = 0;
    let allProducts = [];

    let apiKey = sessionStorage.getItem('apiKey') || localStorage.getItem('apiKey') || 
                 sessionStorage.getItem('apikey') || localStorage.getItem('apikey');
    
    if (!apiKey) {
        const storedUser = sessionStorage.getItem('user') || localStorage.getItem('user');
        if (storedUser) {
            try {
                const userObj = JSON.parse(storedUser);
                apiKey = userObj.apiKey || userObj.apikey;
            } catch (e) {
                console.error('Error parsing user data:', e);
            }
        }
    }

    const productList = document.querySelector('.product-list');
    if (!productList) {
        console.error('Product list container not found!');
        return;
    }

    const paginationContainer = document.createElement('div');
    paginationContainer.className = 'pagination';
    productList.after(paginationContainer);
    
    const loadingIndicator = document.createElement('div');
    loadingIndicator.className = 'loading-indicator';
    loadingIndicator.innerHTML = '<div class="spinner"></div><p>Loading products...</p>';
    productList.before(loadingIndicator);

    const categoryFilter = document.querySelector('.category-filter');
    const minPriceInput = document.querySelector('.min-price');
    const maxPriceInput = document.querySelector('.max-price');
    const applyFiltersBtn = document.querySelector('.apply-filters');
    const resetFiltersBtn = document.querySelector('.reset-filters');
    const searchInput = document.querySelector('.search-input');
    const ratingFilter = document.querySelector('.rating-filter');
    let searchTimeout;

    if (searchInput) {
        searchInput.addEventListener('input', () => {
            clearTimeout(searchTimeout);

            searchTimeout = setTimeout(() => {
                currentPage = 1;
                fetchProductsWithFilters();
            }, 300);
        });
    }

    fetchProducts();

    if (applyFiltersBtn) {
        applyFiltersBtn.addEventListener('click', () => {
            currentPage = 1;
            fetchProductsWithFilters();
        });
    }

    if (resetFiltersBtn) {
        resetFiltersBtn.addEventListener('click', () => {
            if (categoryFilter) categoryFilter.value = '';
            if (minPriceInput) minPriceInput.value = '';
            if (maxPriceInput) maxPriceInput.value = '';
            if (searchInput) searchInput.value = '';
            currentPage = 1;
            fetchProducts();
        });
    }

    async function fetchProducts() {
        showLoading();
        
        try {
            if (!apiKey) {
                throw new Error('Please login to view products');
            }

            const response = await getAllProducts();
            
            if (response.status === 'success') {
                allProducts = response.data;
                totalProducts = allProducts.length;
                renderProducts();
                setupPagination();
            } else {
                throw new Error(response.message || 'Failed to load products');
            }
        } catch (error) {
            console.error('Fetch error:', error);
            showError(error.message);
        } finally {
            hideLoading();
        }
    }

async function fetchProductsWithFilters() {
    if (!apiKey) {
        throw new Error('No API key found. Please login.');
    }

    showLoading();

    try {
        const getValue = (val) => val === '' ? undefined : val;
        
        const filters = {
            category: getValue(categoryFilter?.value),
            min_price: minPriceInput?.value ? parseFloat(minPriceInput.value) : undefined,
            max_price: maxPriceInput?.value ? parseFloat(maxPriceInput.value) : undefined,
            search: getValue(searchInput?.value.trim()),
            min_rating: getValue(ratingFilter?.value) ? parseFloat(ratingFilter.value) : undefined
        };

            if (filters.min_rating !== undefined && (filters.min_rating < 1 || filters.min_rating > 5)) {
            throw new Error('Rating filter must be between 1 and 5');
        }

            if (filters.min_price !== undefined && isNaN(filters.min_price)) {
                throw new Error('Minimum price must be a number');
            }
            if (filters.max_price !== undefined && isNaN(filters.max_price)) {
                throw new Error('Maximum price must be a number');
            }
            if (filters.min_price !== undefined && filters.max_price !== undefined && 
                filters.min_price > filters.max_price) {
                throw new Error('Minimum price cannot be greater than maximum price');
            }

            const response = await getFilteredProducts(
            undefined,
            undefined, 
            filters.category,
            filters.min_price,
            filters.max_price,
            filters.search,
            undefined,
            filters.min_rating 
        );
            
            if (response.status === 'success') {
                processProductData(response.data);
            } else {
                throw new Error(response.message || 'Failed to load filtered products');
            }
        } catch (error) {
            console.error('Filter fetch error:', error);
            showError(error.message);
        } finally {
            hideLoading();
        }
    }

    function processProductData(products) {
        allProducts = products;
        totalProducts = allProducts.length;
        currentPage = 1;
        renderProducts();
        setupPagination();
    }

 function renderProducts() {
    const startIndex = (currentPage - 1) * PRODUCTS_PER_PAGE;
    const endIndex = startIndex + PRODUCTS_PER_PAGE;
    const productsToDisplay = allProducts.slice(startIndex, endIndex);

    productList.innerHTML = '';

    if (productsToDisplay.length === 0) {
        productList.innerHTML = '<div class="no-products">No products found</div>';
        return;
    }

    productsToDisplay.forEach(product => {
        const productElement = document.createElement('div');
        productElement.className = 'product';
    
        const priceNum = Number(product.price);
        const price = !isNaN(priceNum) ? priceNum.toFixed(2) : '0.00';

        const avgRating = product.average_rating ? parseFloat(product.average_rating).toFixed(1) : null;
        
        const starRating = avgRating ? generateStarRating(avgRating) : '<div class="no-rating">Not rated yet</div>';

        const productID = product.product_id || product.id || product.id_product;

        productElement.innerHTML = `
            ${product.thumbnail ? `<img src="${escapeHtml(product.thumbnail)}" alt="${escapeHtml(product.title)}">` : ''}
            <div class="product-info">
                <h3>${escapeHtml(product.title || 'Untitled Product')}</h3>
                <div class="product-meta">
                    <p class="price">R${price}</p>
                    <div class="rating-container">
                        ${starRating}
                        ${avgRating ? `<span class="rating-text">${avgRating}/5</span>` : ''}
                    </div>
                </div>
                ${product.description ? `<p class="description">${escapeHtml(product.description)}</p>` : ''}
                ${product.category ? `<p class="category">${escapeHtml(product.category)}</p>` : ''}
                <button class="view-product" data-prod-id="${productID}">View Product</button>
            </div>
        `;

        const viewButton = productElement.querySelector(".view-product");
        viewButton.addEventListener("click", () => {
            if (!productID) {
                console.warn("No product ID found for:", product);
                return;
            }
            makeUpdateStats(apiKey, productID);
            const productData = {
                prod_id: productID,
                title: product.title,
                thumbnail: product.thumbnail,
                price: product.price,
                description: product.description,
                product_link: product.product_link,
                average_rating: avgRating // Include rating in product data
            };

            sessionStorage.setItem("selectedProduct", JSON.stringify(productData));
            window.location.href = "view_product.php";
        });

        productList.appendChild(productElement);
    });
}

    function makeUpdateStats(apikey, prod_id){
        const body = {
            type: 'UpdateStats',
            apikey: apikey,
            product_id: prod_id
        };
        sendRequest(body).then(data => {
            console.log(data);
        }).catch(err => {
            console.log(err);
        })
    }

function generateStarRating(rating) {
    const numericRating = parseFloat(rating);
    const fullStars = Math.floor(numericRating);
    const hasHalfStar = numericRating % 1 >= 0.5;
    const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);
    
    let starsHTML = '';
    
    for (let i = 0; i < fullStars; i++) {
        starsHTML += '<i class="fas fa-star"></i>';
    }
    
    if (hasHalfStar) {
        starsHTML += '<i class="fas fa-star-half-alt"></i>';
    }

    for (let i = 0; i < emptyStars; i++) {
        starsHTML += '<i class="far fa-star"></i>';
    }
    
    return `<div class="star-rating">${starsHTML}</div>`;
}


    function setupPagination() {
        paginationContainer.innerHTML = '';
        const totalPages = Math.ceil(totalProducts / PRODUCTS_PER_PAGE);

        if (totalPages <= 1) return;

        const prevButton = document.createElement('button');
        prevButton.textContent = 'Previous';
        prevButton.disabled = currentPage === 1;
        prevButton.addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                renderProducts();
                setupPagination();
            }
        });
        paginationContainer.appendChild(prevButton);

        for (let i = 1; i <= totalPages; i++) {
            const pageButton = document.createElement('button');
            pageButton.textContent = i;
            pageButton.className = currentPage === i ? 'active' : '';
            pageButton.addEventListener('click', () => {
                currentPage = i;
                renderProducts();
                setupPagination();
            });
            paginationContainer.appendChild(pageButton);
        }

        const nextButton = document.createElement('button');
        nextButton.textContent = 'Next';
        nextButton.disabled = currentPage === totalPages;
        nextButton.addEventListener('click', () => {
            if (currentPage < totalPages) {
                currentPage++;
                renderProducts();
                setupPagination();
            }
        });
        paginationContainer.appendChild(nextButton);
    }

    function showLoading() {
        loadingIndicator.style.display = 'flex';
        productList.style.opacity = '0.5';
    }

    function hideLoading() {
        loadingIndicator.style.display = 'none';
        productList.style.opacity = '1';
    }

    function showError(message) {
        productList.innerHTML = `<div class="error-message">${escapeHtml(message)}</div>`;
    }

    function escapeHtml(unsafe) {
        if (!unsafe) return '';
        return unsafe.toString()
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    async function sendRequest(body) {
        try {
            if (apiKey && !body.apikey) {
                body.apikey = apiKey;
            }

            const response = await fetch('../api/api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(body)
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const text = await response.text();
            const data = text ? JSON.parse(text) : {};
            return data;
        } catch (error) {
            return {
                status: 'error',
                message: error.message
            };
        }
    }

    async function getAllProducts() {
        const body = { type: 'GetAllProducts' };
        return await sendRequest(body);
    }

    async function getFilteredProducts(prod_id, brand, category, min_price, max_price, search, store_id, min_rating) {
    const body = {
        type: 'GetFilteredProducts',
        apikey: apiKey
    };

    if (prod_id !== undefined) body.prod_id = prod_id;
    if (brand !== undefined) body.brand_id = brand;
    if (category !== undefined) body.category = category;
    if (min_price !== undefined) body.min_price = min_price;
    if (max_price !== undefined) body.max_price = max_price;
    if (search !== undefined) body.search = search;
    if (store_id !== undefined) body.store_id = store_id;
    if (min_rating !== undefined) body.min_rating = min_rating;

    return await sendRequest(body);
}
});


