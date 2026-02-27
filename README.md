# E-Commerce Dashboard & API

A full-featured Laravel e-commerce platform with a comprehensive admin dashboard, RESTful API, and database management system. This project includes user authentication, product management, order processing, and customer reviews functionality with multi-language support (Arabic, English, Dutch).

## 📋 Table of Contents

- [Features](#features)
- [Technology Stack](#technology-stack)
- [System Requirements](#system-requirements)
- [Installation & Setup](#installation--setup)
- [Database Structure](#database-structure)
- [API Endpoints & Documentation](#api-endpoints--documentation)
- [Configuration](#configuration)
- [Running the Application](#running-the-application)
- [Project Structure](#project-structure)

---

## ✨ Features

### Core Functionality
- **User Management**: Admin and customer authentication with role-based access control
- **Product Management**: Create, update, and manage products with images and translations
- **Category System**: Organize products into categories with multi-language support
- **Order Management**: View, track, and manage customer orders with detailed status updates
- **Shopping Cart & Checkout**: Guest and authenticated checkout processes
- **Payment Integration**: Stripe payment gateway with webhook support
- **Reviews & Ratings**: Customers can leave product reviews with ratings
- **Wishlists**: Users can save favorite products for later
- **Multi-Language Support**: Full support for Arabic (ar), English (en), and Dutch (nl)
- **SEO Optimization**: Meta tags and descriptions for products, categories, and pages
- **Static Pages**: Manage custom pages (About Us, Terms & Conditions, etc.)
- **Site Settings**: Manage global site configuration (logo, email, phone, etc.)

### Admin Dashboard Features
- Comprehensive admin panel for managing all aspects of the store
- Role-based permission system (Admin roles)
- Product and category management with bulk operations
- Order tracking and management
- Customer management
- Settings and configuration panel
- Email notifications for orders

---

## 🛠 Technology Stack

### Backend
- **Laravel 12.0** - PHP web application framework
- **PHP 8.2+** - Programming language
- **Laravel Sanctum 4.0** - API token authentication
- **Laravel Livewire 3.6.4** - Full-stack framework for dynamic components
- **Livewire Volt 1.7.0** - Single-file components for Livewire
- **Spatie Laravel Permission 6.24** - Role and permission management
- **Spatie Laravel Google Cloud Storage 2.3** - GCS integration for image storage
- **Astrotomic Laravel Translatable 11.16** - Multi-language support
- **Stripe PHP SDK 19.3** - Payment processing

### Frontend
- **Tailwind CSS 3.1** - Utility-first CSS framework
- **Tailwind CSS Forms 0.5.2** - Form styling plugin
- **DaisyUI 5.5.14** - Component library for Tailwind CSS
- **Vite 7.0.7** - Next-generation frontend build tool
- **Laravel Vite Plugin 2.0** - Integration between Vite and Laravel
- **Axios 1.11** - HTTP client for API calls
- **PostCSS 8.4.31** - CSS transformations

### Development & Testing
- **Pest 3.8** - Modern PHP testing framework
- **Pest Laravel Plugin 3.2** - Pest integration with Laravel
- **PHPStan** - Static analysis tool
- **Laravel Pint 1.24** - PHP code style formatter
- **Laravel Sail 1.41** - Docker development environment
- **FakerPHP 1.23** - Fake data generation for testing
- **Mockery 1.6** - Mocking library

### Database
- **SQLite** (default) or **MySQL/PostgreSQL** (via configuration)
- **Laravel Migrations** - Database schema management

---

## 💻 System Requirements

Before installation, ensure you have:

- **PHP**: 8.2 or higher
- **Composer**: Dependency manager for PHP
- **Node.js**: 16+ with npm or yarn
- **Git**: Version control system
- **Web Server**: Apache or Nginx (Laravel Sail provides Docker setup)
- **Database**: MySQL 8.0+, PostgreSQL, or SQLite (default)
- **GCS Account** (optional): For Google Cloud Storage image uploads
- **Stripe Account** (optional): For payment processing

---

## 📦 Installation & Setup

### Step 1: Clone the Repository

```bash
git clone https://github.com/your-username/ecommerce-dashboard.git
cd ecommerce-dashboard
```

### Step 2: Install PHP Dependencies

```bash
composer install
```

This command installs all PHP packages defined in `composer.json`. It may take a few minutes depending on your internet connection.

### Step 3: Create Environment Configuration

Copy the example environment file and generate the application key:

```bash
cp .env.example .env
php artisan key:generate
```

### Step 4: Configure Environment Variables

Edit the `.env` file with your specific settings:

```env
# Application Settings
APP_NAME="Your Store Name"
APP_ENV=local          # or 'production' for production
APP_DEBUG=true         # Set to false in production
APP_URL=http://localhost:8000

# Localization
APP_LOCALE=en
APP_FALLBACK_LOCALE=en

# Database Configuration (Default: SQLite)
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite

# For MySQL, use:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=ecommerce_db
# DB_USERNAME=root
# DB_PASSWORD=your_password

# Session
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Mail (for order notifications)
MAIL_MAILER=smtp
MAIL_HOST=your-mail-server.com
MAIL_PORT=587
MAIL_USERNAME=your-email@example.com
MAIL_PASSWORD=your-password
MAIL_FROM_ADDRESS="noreply@yourstore.com"

# Payment Gateway (Stripe)
STRIPE_PUBLIC_KEY=your_stripe_public_key
STRIPE_SECRET_KEY=your_stripe_secret_key

# Google Cloud Storage (Optional)
GOOGLE_CLOUD_ENGINE=gcs
GOOGLE_CLOUD_PROJECT_ID=your-project-id
GOOGLE_CLOUD_KEY_FILE=path/to/service-account-key.json
GOOGLE_CLOUD_STORAGE_BUCKET=your-bucket-name
```

### Step 5: Create Database

For SQLite (default):
```bash
touch database/database.sqlite
```

For MySQL:
```bash
mysql -u root -p -e "CREATE DATABASE ecommerce_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### Step 6: Run Database Migrations

```bash
php artisan migrate
```

This creates all necessary tables in your database. The migrations include:
- Users table with roles and permissions
- Products, Categories, and Product Images
- Orders and Order Items
- Reviews and Wishlists
- Settings and Static Pages
- Permission tables (Spatie)

### Step 7: Seed Initial Data (Optional)

```bash
php artisan db:seed
```

This populates your database with initial data like admin users, permissions, and sample categories.

### Step 8: Install Frontend Dependencies

```bash
npm install
```

### Step 9: Build Frontend Assets

```bash
npm run build
```

For development with hot reload:
```bash
npm run dev
```

---

## 🗄 Database Structure

### Complete Database Schema with Table Descriptions

#### 1. **Users Table**
Stores all user accounts (customers, admins, staff).

| Column | Type | Description |
|--------|------|-------------|
| id | BIGINT (PK) | Unique user identifier |
| name | VARCHAR | User's full name |
| email | VARCHAR (UNIQUE) | Email address for authentication |
| email_verified_at | TIMESTAMP | Email verification timestamp |
| password | VARCHAR | Hashed password |
| is_active | BOOLEAN | Account status (active/inactive) |
| phone | VARCHAR | User's phone number |
| status | BOOLEAN | User status (true = active, false = banned) |
| role | VARCHAR | User role (customer/admin/staff) |
| city | VARCHAR | User's city |
| address | TEXT | Full address for shipping/billing |
| last_login_at | TIMESTAMP | Timestamp of last login |
| remember_token | VARCHAR | Token for "Remember Me" functionality |
| created_at | TIMESTAMP | Account creation timestamp |
| updated_at | TIMESTAMP | Last update timestamp |

---

#### 2. **Categories Table**
Product categories for organizing inventory.

| Column | Type | Description |
|--------|------|-------------|
| id | BIGINT (PK) | Unique category identifier |
| image | VARCHAR | Category image URL/path |
| status | BOOLEAN | Category visibility (active/inactive) |
| created_at | TIMESTAMP | Creation timestamp |
| updated_at | TIMESTAMP | Last update timestamp |

**Related Table: category_translations**
| Column | Type | Description |
|--------|------|-------------|
| id | BIGINT (PK) | Translation record ID |
| category_id | BIGINT (FK) | Reference to categories table |
| locale | VARCHAR (INDEX) | Language code (ar, en, nl) |
| name | VARCHAR | Category name in that language |
| slug | VARCHAR (UNIQUE) | URL-friendly slug |
| meta_title | VARCHAR | SEO page title |
| meta_description | TEXT | SEO page description |

---

#### 3. **Products Table**
Core product information.

| Column | Type | Description |
|--------|------|-------------|
| id | BIGINT (PK) | Unique product identifier |
| price | DECIMAL(10,2) | Regular selling price |
| discount_price | DECIMAL(10,2) | Discounted price (nullable) |
| quantity | INT | Stock quantity available |
| sku | VARCHAR (UNIQUE) | Stock Keeping Unit for inventory |
| main_image | VARCHAR | Primary product image path |
| status | BOOLEAN | Product visibility (active/inactive) |
| created_at | TIMESTAMP | Creation timestamp |
| updated_at | TIMESTAMP | Last update timestamp |

**Related Table: product_translations**
| Column | Type | Description |
|--------|------|-------------|
| id | BIGINT (PK) | Translation record ID |
| product_id | BIGINT (FK) | Reference to products table |
| locale | VARCHAR (INDEX) | Language code (ar, en, nl) |
| name | VARCHAR | Product name in that language |
| slug | VARCHAR | URL-friendly product slug |
| short_description | TEXT | Brief product description |
| description | LONGTEXT | Detailed product description |
| meta_title | VARCHAR | SEO page title |
| meta_description | TEXT | SEO page description |

---

#### 4. **Product Images Table**
Additional product images beyond the main image.

| Column | Type | Description |
|--------|------|-------------|
| id | BIGINT (PK) | Unique image identifier |
| product_id | BIGINT (FK) | Reference to products table |
| image_path | VARCHAR | Image file path/URL |
| sort_order | INT | Display order in gallery |
| created_at | TIMESTAMP | Upload timestamp |
| updated_at | TIMESTAMP | Last update timestamp |

---

#### 5. **Orders Table**
Customer orders with payment and shipping information.

| Column | Type | Description |
|--------|------|-------------|
| id | BIGINT (PK) | Unique order identifier |
| user_id | BIGINT (FK, NULLABLE) | Customer (null for guest checkout) |
| number | VARCHAR (UNIQUE) | Order number (e.g., ORD-2026-001) |
| payment_method | VARCHAR | Payment type (cod, stripe, paypal) |
| payment_status | ENUM | Status: pending/paid/failed |
| status | ENUM | Order status: pending/processing/shipped/completed/cancelled |
| shipping_price | DECIMAL(10,2) | Shipping cost |
| tax_price | DECIMAL(10,2) | Tax amount |
| discount | DECIMAL(10,2) | Applied discount amount |
| total_price | DECIMAL(10,2) | Final total including all charges |
| customer_name | VARCHAR | Shipping recipient's name |
| customer_phone | VARCHAR | Shipping contact phone |
| customer_address | VARCHAR | Delivery address |
| customer_city | VARCHAR | Delivery city |
| email | VARCHAR | Order/notification email |
| locale | VARCHAR | Language used during checkout |
| notes | TEXT | Special order notes/requests |
| created_at | TIMESTAMP | Order creation timestamp |
| updated_at | TIMESTAMP | Last status update |

---

#### 6. **Order Items Table**
Individual products within each order.

| Column | Type | Description |
|--------|------|-------------|
| id | BIGINT (PK) | Unique line item ID |
| order_id | BIGINT (FK) | Reference to orders table |
| product_id | BIGINT (FK, NULLABLE) | Reference to products (nullable if product deleted) |
| product_name | VARCHAR | Product name captured at purchase |
| price | DECIMAL(10,2) | Unit price at time of purchase |
| quantity | INT | Quantity ordered |

---

#### 7. **Reviews Table**
Customer product reviews and ratings.

| Column | Type | Description |
|--------|------|-------------|
| id | BIGINT (PK) | Unique review identifier |
| product_id | BIGINT (FK) | Reference to products table |
| user_id | BIGINT (FK) | Reviewer (customer) |
| rating | TINYINT | Rating score (1-5) |
| comment | TEXT | Review text (nullable) |
| created_at | TIMESTAMP | Review submission timestamp |
| updated_at | TIMESTAMP | Last update timestamp |

---

#### 8. **Wishlists Table**
Saved products for authenticated users.

| Column | Type | Description |
|--------|------|-------------|
| id | BIGINT (PK) | Unique wishlist item ID |
| user_id | BIGINT (FK) | Customer who saved item |
| product_id | BIGINT (FK) | Product added to wishlist |
| created_at | TIMESTAMP | When item was added |
| updated_at | TIMESTAMP | Last update timestamp |
| UNIQUE | (user_id, product_id) | Prevent duplicate entries |

---

#### 9. **Pages Table**
Static content pages (About Us, Contact, Terms, etc.).

| Column | Type | Description |
|--------|------|-------------|
| id | BIGINT (PK) | Unique page identifier |
| slug | VARCHAR (UNIQUE) | URL slug (about-us, contact) |
| status | BOOLEAN | Page visibility |
| created_at | TIMESTAMP | Creation timestamp |
| updated_at | TIMESTAMP | Last update timestamp |

**Related Table: page_translations**
| Column | Type | Description |
|--------|------|-------------|
| id | BIGINT (PK) | Translation record ID |
| page_id | BIGINT (FK) | Reference to pages table |
| locale | VARCHAR (INDEX) | Language code |
| title | VARCHAR | Page title in that language |
| content | LONGTEXT | Page content (HTML supported) |
| meta_title | VARCHAR | SEO page title |
| meta_description | TEXT | SEO description |
| meta_keywords | VARCHAR | SEO keywords |

---

#### 10. **Settings Table**
Global site configuration.

| Column | Type | Description |
|--------|------|-------------|
| id | BIGINT (PK) | Settings identifier |
| site_email | VARCHAR | Site contact email |
| site_logo | VARCHAR | Logo image path |
| site_phone | VARCHAR | Site phone number |
| maintenance_mode | BOOLEAN | Maintenance mode toggle |
| created_at | TIMESTAMP | Creation timestamp |
| updated_at | TIMESTAMP | Last update timestamp |

**Related Table: setting_translations**
| Column | Type | Description |
|--------|------|-------------|
| id | BIGINT (PK) | Translation record ID |
| setting_id | BIGINT (FK) | Reference to settings table |
| locale | VARCHAR (INDEX) | Language code |
| site_name | VARCHAR | Site name in that language |
| site_description | TEXT | Site description |
| copyright | VARCHAR | Copyright notice |

---

#### 11. **Permission Tables** (Spatie Laravel Permission)
Role-based access control via Spatie.

| Table | Purpose |
|-------|---------|
| roles | Define user roles (admin, customer, staff) |
| permissions | Define system permissions (manage-products, view-orders) |
| model_has_roles | Link users to roles (many-to-many) |
| model_has_permissions | Link users to permissions |
| role_has_permissions | Link roles to permissions |

---

#### 12. **Additional System Tables**

**personal_access_tokens** - Laravel Sanctum API tokens
| Column | Type | Description |
|--------|------|-------------|
| id | BIGINT (PK) | Token identifier |
| tokenable_id | BIGINT | User ID |
| tokenable_type | VARCHAR | Model type |
| name | VARCHAR | Token name |
| token | VARCHAR (UNIQUE) | Actual token hash |
| abilities | JSON | Token permissions |
| last_used_at | TIMESTAMP | Last usage time |
| created_at | TIMESTAMP | Token creation time |
| expired_at | TIMESTAMP | Token expiration |

---

## 🔌 API Endpoints & Documentation

The application exposes a RESTful API with the base URL: `http://your-domain/api/v1`

### API Response Format

All API responses return JSON:
```json
{
  "success": true,
  "message": "Operation successful",
  "data": { /* response data */ }
}
```

### Public Endpoints (No Authentication Required)

#### **Settings**
```
GET /api/v1/settings
```
Returns global site settings.

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "site_email": "contact@store.com",
    "site_phone": "+1-800-123-4567",
    "site_logo": "https://cdn.example.com/logo.png",
    "site_name": "My Store",
    "site_description": "Best online store",
    "maintenance_mode": false
  }
}
```

---

#### **Categories**
```
GET /api/v1/categories
```
Retrieves all active product categories.

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Electronics",
      "slug": "electronics",
      "image": "https://cdn.example.com/electronics.jpg",
      "meta_title": "Electronics | Store",
      "meta_description": "Browse our electronics collection"
    }
  ]
}
```

---

#### **Latest Products**
```
GET /api/v1/products/latest
```
Fetches recently added products.

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Wireless Headphones",
      "slug": "wireless-headphones",
      "price": 99.99,
      "discount_price": 79.99,
      "main_image": "https://cdn.example.com/headphones.jpg",
      "short_description": "High-quality wireless headphones",
      "rating": 4.5,
      "reviews_count": 42
    }
  ]
}
```

---

#### **Products by Category**
```
GET /api/v1/products/category/{id}
```
Retrieves all products in a specific category.

**Parameters:**
- `id` (integer) - Category ID

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 5,
      "name": "USB-C Cable",
      "category": "Electronics",
      "price": 19.99,
      "quantity": 150,
      "main_image": "https://cdn.example.com/usb-c.jpg"
    }
  ]
}
```

---

#### **Product Details**
```
GET /api/v1/product/{slug}
```
Gets detailed information about a specific product.

**Parameters:**
- `slug` (string) - Product URL slug

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Wireless Headphones",
    "slug": "wireless-headphones",
    "price": 99.99,
    "discount_price": 79.99,
    "quantity": 250,
    "sku": "WH-001",
    "main_image": "https://cdn.example.com/headphones-main.jpg",
    "images": [
      { "id": 1, "image_path": "https://cdn.example.com/headphones-1.jpg" },
      { "id": 2, "image_path": "https://cdn.example.com/headphones-2.jpg" }
    ],
    "description": "Premium wireless headphones with noise cancellation",
    "short_description": "High-quality wireless headphones",
    "meta_title": "Wireless Headphones | Store",
    "meta_description": "Shop wireless headphones online",
    "reviews": [
      {
        "id": 1,
        "user_name": "John Doe",
        "rating": 5,
        "comment": "Excellent product!",
        "created_at": "2026-02-27T10:30:00Z"
      }
    ],
    "average_rating": 4.5,
    "reviews_count": 42
  }
}
```

---

#### **Pages**
```
GET /api/v1/pages
GET /api/v1/page/{slug}
```
Retrieve all static pages or a specific page.

**Response (Single Page):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "slug": "about-us",
    "title": "About Our Store",
    "content": "<h1>Welcome to Our Store</h1><p>We provide...</p>",
    "meta_title": "About Us",
    "meta_description": "Learn more about our store"
  }
}
```

---

#### **Search Products**
```
GET /api/v1/search?q={query}
```
Search products by name or description.

**Parameters:**
- `q` (string) - Search query

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Wireless Headphones",
      "price": 79.99,
      "main_image": "https://cdn.example.com/headphones.jpg"
    }
  ]
}
```

---

### Authentication Endpoints

#### **User Registration**
```
POST /api/v1/register
```
Create a new user account.

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "SecurePassword123",
  "password_confirmation": "SecurePassword123"
}
```

**Response:**
```json
{
  "success": true,
  "message": "User registered successfully",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "role": "customer"
    },
    "token": "eyJ0eXAiOiJKV1QiLCJhbGc..."
  }
}
```

---

#### **User Login**
```
POST /api/v1/login
```
Authenticate user and get API token.

**Request Body:**
```json
{
  "email": "john@example.com",
  "password": "SecurePassword123"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "role": "customer"
    },
    "token": "eyJ0eXAiOiJKV1QiLCJhbGc..."
  }
}
```

---

### Protected Endpoints (Requires Authentication with Bearer Token)

Add the token to your request headers:
```
Authorization: Bearer {token}
```

#### **Get User Profile**
```
GET /api/v1/profile
```
Retrieve authenticated user's profile.

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "+1-234-567-8900",
    "city": "New York",
    "address": "123 Main St, Apt 4B",
    "role": "customer",
    "email_verified_at": "2026-02-20T15:30:00Z",
    "created_at": "2026-02-15T10:00:00Z"
  }
}
```

---

#### **Update User Profile**
```
PUT /api/v1/profile
```
Update user's profile information.

**Request Body:**
```json
{
  "name": "John Smith",
  "phone": "+1-987-654-3210",
  "city": "Boston",
  "address": "456 Oak Ave, Suite 5"
}
```

---

#### **User Logout**
```
POST /api/v1/logout
```
Invalidate user's current authentication token.

---

#### **Checkout (Create Order)**
```
POST /api/v1/checkout
```
Create a new order (works for both guest and authenticated users).

**Request Body:**
```json
{
  "items": [
    {
      "product_id": 1,
      "quantity": 2,
      "price": 79.99
    }
  ],
  "customer_name": "John Doe",
  "customer_email": "john@example.com",
  "customer_phone": "+1-234-567-8900",
  "customer_address": "123 Main St",
  "customer_city": "New York",
  "payment_method": "stripe",
  "shipping_price": 10.00,
  "tax_price": 5.50,
  "discount": 0,
  "total_price": 170.49
}
```

**Response:**
```json
{
  "success": true,
  "message": "Order created successfully",
  "data": {
    "id": 1,
    "number": "ORD-2026-001",
    "user_id": 1,
    "status": "pending",
    "payment_status": "pending",
    "total_price": 170.49,
    "items": [
      {
        "product_name": "Wireless Headphones",
        "quantity": 2,
        "price": 79.99
      }
    ]
  }
}
```

---

#### **View My Orders**
```
GET /api/v1/my-orders
```
Retrieve all orders for authenticated user.

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "number": "ORD-2026-001",
      "status": "shipped",
      "payment_status": "paid",
      "total_price": 170.49,
      "created_at": "2026-02-25T14:30:00Z",
      "items": [
        {
          "product_name": "Wireless Headphones",
          "quantity": 2,
          "price": 79.99
        }
      ]
    }
  ]
}
```

---

#### **Product Reviews**
```
POST /api/v1/products/{product}/reviews
```
Submit a review for a purchased product.

**Parameters:**
- `product` (integer) - Product ID

**Request Body:**
```json
{
  "rating": 5,
  "comment": "Excellent product, highly recommend!"
}
```

---

#### **Wishlist Management**
```
GET /api/v1/wishlist
POST /api/v1/wishlist/toggle
GET /api/v1/wishlist/ids
```

**Toggle Product in Wishlist (POST):**
```json
{
  "product_id": 5
}
```

**Get Wishlist IDs:**
Returns a list of product IDs in user's wishlist.

---

#### **Guest Wishlist**
```
POST /api/v1/wishlist/guest
```
Manage wishlist for non-authenticated users.

**Request Body:**
```json
{
  "product_ids": [1, 2, 5]
}
```

---

### Webhook Endpoints

#### **Stripe Payment Webhook**
```
POST /api/webhook/stripe
```
Handles Stripe payment status updates. Configure this URL in your Stripe dashboard.

---

## ⚙️ Configuration

### Environment Configuration Files

The main configuration is in the `.env` file. Additional configurations are in the `config/` directory:

- **config/app.php** - Application settings (name, timezone, locale)
- **config/database.php** - Database connection settings
- **config/mail.php** - Email configuration
- **config/filesystems.php** - File storage settings (local, s3, gcs)
- **config/permission.php** - Spatie permission configuration
- **config/translatable.php** - Multi-language configuration
- **config/services.php** - Third-party services (Stripe, etc.)

### Key Configuration Steps

1. **Set Application URL**
   ```
   APP_URL=http://localhost:8000
   ```

2. **Configure Database** (SQLite default, or switch to MySQL)
   ```
   DB_CONNECTION=sqlite
   # or
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_DATABASE=ecommerce_db
   DB_USERNAME=root
   DB_PASSWORD=password
   ```

3. **Enable/Disable Maintenance Mode**
   ```
   php artisan down          # Enable maintenance
   php artisan up            # Disable maintenance
   ```

4. **Configure Mail Notifications**
   Update `MAIL_*` variables in `.env` for order emails.

5. **Setup Payment Gateway**
   Add Stripe keys to `.env`:
   ```
   STRIPE_PUBLIC_KEY=pk_test_...
   STRIPE_SECRET_KEY=sk_test_...
   ```

---

## 🚀 Running the Application

### Option 1: Using Laravel Artisan (Built-in Server)

```bash
php artisan serve
```

Access the application at `http://localhost:8000`

To run on a different port:
```bash
php artisan serve --port=8080
```

### Option 2: Using Docker (Laravel Sail)

```bash
./vendor/bin/sail up
```

### Option 3: Using a Web Server (Production)

Configure your web server (Nginx or Apache) to point to the `public/` directory.

**Nginx Configuration:**
```nginx
server {
    listen 80;
    server_name your-domain.com;
    
    root /path/to/project/public;
    index index.php;
    
    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

---

### Running Development Commands

#### **Start Development Server with All Features**
```bash
composer run dev
```

This command starts:
- PHP development server on port 8000
- Queue listener (processes background jobs)
- Vite dev server with hot reload
- Pail log viewer

#### **Build Frontend for Production**
```bash
npm run build
```

#### **Run Tests**
```bash
composer run test
```

Or use Pest directly:
```bash
php artisan test
./vendor/bin/pest
```

#### **Code Formatting and Linting**
```bash
php artisan pint           # Fix code style issues
composer run test          # Run tests with PHPUnit/Pest
```

#### **Generate IDE Helper Files** (for better code completion)
```bash
php artisan ide-helper:generate
php artisan ide-helper:models
```

---

## 📁 Project Structure

```
ecommerce-dashboard/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/              # API controllers
│   │   │   │   ├── FrontendController.php
│   │   │   │   ├── AuthController.php
│   │   │   │   ├── CheckoutController.php
│   │   │   │   ├── ReviewController.php
│   │   │   │   └── WishlistController.php
│   │   │   └── Admin/            # Admin panel controllers
│   │   ├── Middleware/           # Custom middleware
│   │   └── Requests/             # Form validation requests
│   ├── Livewire/                 # Livewire components for admin
│   ├── Mail/                     # Mailables for notifications
│   ├── Models/                   # Eloquent models
│   │   ├── User.php
│   │   ├── Product.php
│   │   ├── Category.php
│   │   ├── Order.php
│   │   ├── Review.php
│   │   └── ...
│   ├── Traits/                   # Reusable traits
│   └── Providers/                # Service providers
├── config/                       # Configuration files
├── database/
│   ├── migrations/               # Database schema
│   └── seeders/                  # Database seeding
├── resources/
│   ├── css/                      # Tailwind CSS files
│   ├── js/                       # JavaScript files
│   └── views/                    # Blade templates
├── routes/
│   ├── api.php                   # API routes
│   ├── web.php                   # Web routes (admin)
│   └── console.php               # Artisan commands
├── storage/                      # File uploads
├── tests/                        # Test files
├── vendor/                       # Composer dependencies
├── public/                       # Web-accessible files
├── .env.example                  # Environment template
├── composer.json                 # PHP dependencies
├── package.json                  # JavaScript dependencies
├── vite.config.js               # Vite configuration
└── README.md                     # This file
```

---

## 📝 Common Commands Reference

```bash
# Artisan Commands
php artisan tinker                    # Interactive shell
php artisan migrate                   # Run migrations
php artisan migrate:rollback          # Rollback migrations
php artisan db:seed                   # Seed database
php artisan cache:clear               # Clear application cache
php artisan config:cache              # Cache configuration
php artisan route:list                # List all routes
php artisan make:model ModelName      # Create new model
php artisan make:migration create_table_name  # Create migration
php artisan make:controller ControllerName    # Create controller
php artisan storage:link              # Create storage symlink

# Composer Commands
composer install                      # Install dependencies
composer update                       # Update dependencies
composer require vendor/package       # Add new package
composer remove vendor/package        # Remove package

# NPM Commands
npm install                          # Install node dependencies
npm run dev                          # Development server with hot reload
npm run build                        # Build for production
npm run format                       # Format code
```

---

## 🔒 Security Best Practices

1. **Never commit `.env` files** to version control
2. **Keep Laravel updated** to get security patches
3. **Use HTTPS in production** for all API endpoints
4. **Validate all user inputs** on the backend
5. **Implement rate limiting** on API endpoints
6. **Use strong passwords** for admin accounts
7. **Enable email verification** for new accounts
8. **Regular database backups** recommended
9. **Use environment variables** for sensitive data

---

## 📞 Support & Troubleshooting

### Common Issues

**Q: Port 8000 is already in use**
```bash
php artisan serve --port=8080
```

**Q: Database migration fails**
```bash
php artisan migrate:rollback          # Rollback previous
php artisan migrate --fresh           # Fresh migration (WARNING: deletes data)
```

**Q: Frontend assets not loading**
```bash
npm run build                         # Rebuild assets
php artisan storage:link              # Create storage symlink
```

**Q: Composer update fails**
```bash
composer install --no-interaction     # Force installation
composer dump-autoload                # Regenerate autoloader
```

---

## 📄 License

This project is licensed under the MIT License. See LICENSE file for details.

---

## 👨‍💻 Contributing

Contributions are welcome! Please follow the existing code style and include tests for new features.

1. Fork the repository
2. Create feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Open Pull Request

---

**Last Updated:** February 27, 2026  
**Laravel Version:** 12.0  
**PHP Version Required:** 8.2+  

For more information, visit the [Laravel Documentation](https://laravel.com/docs).
