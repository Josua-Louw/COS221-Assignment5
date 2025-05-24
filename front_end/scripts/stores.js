
        document.querySelectorAll('.btn-follow').forEach(button => {
            button.addEventListener('click', function() {
                const storeId = this.getAttribute('data-store-id');
                // This will be replaced with actual API call to follow endpoint
                console.log('Following store:', storeId);
                this.textContent = 'Following';
                this.style.backgroundColor = '#e0e0e0';
            });
        });

        document.querySelector('.search-btn').addEventListener('click', function() {
            const searchTerm = document.querySelector('.search-input').value;
            const storeType = document.querySelector('select').value;
            console.log('Searching for:', searchTerm, 'Type:', storeType);
        });
  