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
    const currentPage = window.location.pathname.split('/').pop();
    const links = document.querySelectorAll('.nav-link, .auth-link');
    links.forEach(link => {
        const href = link.getAttribute('href');
        if (href === currentPage){
            link.classList.add('active');
        }
    });
});