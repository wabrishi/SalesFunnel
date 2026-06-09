# Sales Funnel CRM

A custom, web-based Sales Funnel CRM built from scratch using Core PHP 8+, MySQL 8+, and Tailwind CSS. This application manages the entire sales lifecycle—from Lead Generation and Follow-Up Management to Opportunity Tracking (Kanban) and Customer Management.

## Architecture
- **Backend:** Core PHP (No Laravel, CodeIgniter, or Symphony)
- **Design Pattern:** MVC (Model-View-Controller)
- **Database:** MySQL via PDO (Prepared Statements only)
- **Frontend:** Vanilla JS & Tailwind CSS (No React, Vue, Bootstrap)
- **Routing:** Custom Lightweight PHP Router
- **Migrations:** Custom PHP Migration Engine

---

## 🛠️ Step-by-Step Setup Guide

Follow these instructions to get the application running on your local machine.

### 1. Prerequisites
Ensure you have the following installed on your system:
- **PHP 8.0** or higher
- **MySQL 8.0** or higher (or MariaDB equivalent)
- **Composer** (for basic autoloading)
- **Node.js & npm** (for compiling Tailwind CSS)
- **XAMPP / LAMP / MAMP** or you can use the built-in PHP development server.

### 2. Clone the Repository
Clone the project to your local web directory (e.g., `htdocs` for XAMPP, or `/var/www/html` for LAMP):
```bash
git clone https://github.com/wabrishi/SalesFunnel.git
cd SalesFunnel
```

### 3. Install Dependencies
Install the PHP autoloader via Composer:
```bash
composer install
```

Install the Node modules required for Tailwind CSS:
```bash
npm install
```

### 4. Configure Environment
Copy the example environment file to `.env`:
```bash
cp .env.example .env
```
Open the `.env` file and update your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sales_funnel_crm
DB_USERNAME=root
DB_PASSWORD=your_database_password
```

### 5. Setup the Database
Ensure your MySQL server is running. Create the database you specified in the `.env` file:
```sql
CREATE DATABASE IF NOT EXISTS sales_funnel_crm;
```

Run the custom migration script to generate the schema, tables, and seed the initial permissions and Admin account:
```bash
php migrate.php migrate
```

### 6. Build the Frontend Assets
Compile the Tailwind CSS file so the application has styling:
```bash
npx tailwindcss -i ./resources/css/app.css -o ./public/css/app.css
```

### 7. Run the Application
You can run the application using PHP's built-in web server. From the root of the project, run:
```bash
cd public
php -S localhost:8000
```
Then, open your browser and navigate to: **http://localhost:8000**

*(Note: If you are using Apache/XAMPP, simply point your virtual host document root to the `public/` folder of this project).*

---

## 🔑 Default Credentials

The database migration automatically seeds a Super Admin user. You can log in with:

- **Email:** `admin@example.com`
- **Password:** `password123`

---

## 📂 Project Structure

*   `app/`: Core application logic (Controllers, Helpers, Middleware, Repositories, Services).
*   `database/migrations/`: Custom PHP migration files.
*   `public/`: Publicly accessible files (`index.php`, `.htaccess`, compiled CSS).
*   `resources/css/`: Source Tailwind CSS file.
*   `routes/`: Contains the `web.php` route definitions.
*   `views/`: Plain PHP view templates.

## 📄 Project Context & Roadmap
Please refer to the `PROJECT_CONTEXT.md` file for an in-depth look at the development roadmap, architectural rules, database entities, and module features.
