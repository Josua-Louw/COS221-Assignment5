 //All javascipt files that use the api must be in a php file that requires header.php
 const API_Location = "http://localhost/COS221-Assignment5/api/api.php";

 //calls the api adn returns a json object of whatever the api returns
async function sendRequest(body) {
  const request = new XMLHttpRequest;

  request.onload = async function () {
    if (this.readyState == 4) {
      if (this.status == 200) {
        try {
            return await JSON.parse(this.responseText);
        } catch {
            return this.responseText;
        }
        
      } else {
        try {
          const repsonse = JSON.parse(this.responseText)
          return await repsonse;
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