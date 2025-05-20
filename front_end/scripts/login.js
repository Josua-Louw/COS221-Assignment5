const authContainer = document.getElementById("auth-buttons");
const apikey = localStorage.getItem("apikey");

if(apikey){
    authContainer.innerHTML = '<a href = "logout.php" class = "button">Logout</a>';
}
else{
     authContainer.innerHTML =  `<a href = "login.php" class = "button">Login</a>
                                <a href = "register.php" class = "button">Register</a>
                                 `;
}