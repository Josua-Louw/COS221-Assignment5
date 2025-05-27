# COS221-Assignment5
## HOW TO SET UP
- import the cos221_data.sql file into your local phpmyadmin host
- read the config.php file which contains information on how to set up the .env file to allow the api to access your local database
- have the project in the root ht docs folder of XAMPP
- run XAMPP apache and MySQL modules
- access the project at ``` http://localhost/COS221-Assignment5/front_end/ ```
## Login System
- Backend: `api/login.php` (POST email/password)
- I have created a register.php and login.php with embedded html in it in the front_end folder (Josh)
- Frontend: `scripts/auth.js` (handles form submission)
- Session management: `includes/auth_functions.php`
