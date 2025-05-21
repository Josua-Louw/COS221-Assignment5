 document.addEventListener('DOMContentLoaded', function() {
            const dropdown = document.querySelector('.dropdown');
            const dropbtn = document.querySelector('.dropbtn');
            
            dropbtn.addEventListener('click', function(e) {
                e.preventDefault();
                const expanded = this.getAttribute('aria-expanded') === 'true';
                this.setAttribute('aria-expanded', !expanded);
                dropdown.classList.toggle('open');
            });
            
            document.addEventListener('click', function(e) {
                if (!dropdown.contains(e.target)) {
                    dropbtn.setAttribute('aria-expanded', 'false');
                    dropdown.classList.remove('open');
                }
            });
        });