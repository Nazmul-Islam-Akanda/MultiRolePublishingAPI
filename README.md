## 📬 MultiRolePublishingAPI
Pupose of this app is an internal publishing backend system that will power both a web dashboard and mobile
application. The platform allows different types of users (Admins, Editors, Authors) to manage and
publish content.

## 🚀 Getting Started  
### 🛠 Setup Instructions  

#### 1. Clone the repository  
git clone https://github.com/Nazmul-Islam-Akanda/MultiRolePublishingAPI.git  
cd MultiRolePublishingAPI  

#### 2. Copy .env file  
cp .env.example .env  

#### 3. Update the .env File   

DB_CONNECTION=mysql  
DB_HOST=127.0.0.1  
DB_PORT=3306  
DB_DATABASE=multirole  
DB_USERNAME=root  
DB_PASSWORD=  

# --- Laravel app key ---  
🔑 Laravel app key will be automatically generated during container startup using:  
run: php artisan key:generate  

#### 4. Create a database  
Create a database with name multirole  

#### 6. Running the Application  
run: composer install  
Run: php artisan migrate  
Run: php artisan db:seed  
Run: php artisan serve  

##### Note  
* First user creation will perform the role of admin  
* Please use the appropriate Bearer Token for the specific user when testing the APIs with the provided Postman collection. Ensure each request is authenticated correctly to reflect user-specific access and permissions.  