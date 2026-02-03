# Soundex Database System - Installation Guide

## 📋 Overview
This is a complete PHP/MySQL database system for the Soundex electronics store website with user management, e-commerce, service booking, and application processing capabilities.

## 🗂️ Files Created

### Core Database Files:
- `db_config.php` - Database connection configuration
- `create_tables.php` - Creates all database tables
- `insert_sample_data.php` - Inserts sample products and services

### PHP Classes:
- `UserManager.php` - User authentication and management
- `ProductManager.php` - Product catalog and shopping cart
- `ServiceManager.php` - Service booking and management
- `ApplicationManager.php` - Internship/scholarship applications
- `ContactManager.php` - Contact forms and messaging

### API & Integration:
- `api.php` - RESTful API endpoint for all database operations
- `js/soundex_api.js` - JavaScript helper for connecting frontend to backend

## 🛠️ Installation Steps

### 1. Database Setup
1. Create a MySQL database named `soundex_db`
2. Update `db_config.php` with your database credentials:
   ```php
   $host = 'localhost';
   $dbname = 'soundex_db';
   $username = 'your_username';
   $password = 'your_password';
   ```

### 2. Create Database Tables
Run the table creation script:
```bash
php create_tables.php
```

### 3. Insert Sample Data
Add sample products and services:
```bash
php insert_sample_data.php
```

This creates:
- 8 sample products (speakers)
- 5 sample services (repairs, diagnostics, etc.)
- Admin user (username: admin, password: admin123)

### 4. Web Server Configuration
Place all files in your web server directory:
- Apache: Usually `C:\xampp\htdocs\soundex\`
- Make sure the `uploads/` directory is writable

### 5. Update HTML Forms
Include the JavaScript API helper in your HTML pages:
```html
<script src="js/soundex_api.js"></script>
```

## 🎯 Features Implemented

### 1. User Management System
- ✅ User registration and login
- ✅ Session management
- ✅ Password hashing
- ✅ User profiles

### 2. E-commerce System
- ✅ Product catalog
- ✅ Shopping cart (with localStorage fallback)
- ✅ Order processing
- ✅ Inventory management

### 3. Service Booking System
- ✅ Device repair booking
- ✅ Service scheduling
- ✅ Technician assignment
- ✅ Status tracking

### 4. Application System
- ✅ Internship/scholarship applications
- ✅ File upload handling
- ✅ Application status tracking
- ✅ Document management

### 5. Contact & Communication
- ✅ Contact form submissions
- ✅ FAQ feedback system
- ✅ Message management
- ✅ Customer inquiries

## 📱 API Endpoints

All endpoints are accessed via `api.php?action=[action_name]`

### User Actions:
- `register` - Create new user account
- `login` - User authentication
- `validate_session` - Check session validity
- `logout` - End user session

### Product Actions:
- `get_products` - Retrieve all products
- `add_to_cart` - Add item to shopping cart
- `get_cart` - Get cart contents
- `remove_from_cart` - Remove item from cart
- `create_order` - Process order

### Service Actions:
- `book_service` - Book repair/service
- `get_services` - Get available services

### Application Actions:
- `submit_application` - Submit internship application
- `get_applications` - Get application list

### Contact Actions:
- `send_message` - Send contact message
- `get_messages` - Get contact messages
- `submit_faq_feedback` - Submit FAQ feedback

## 🔧 Usage Examples

### JavaScript Integration:
```javascript
// Login user
const result = await SoundexAPI.loginUser('username', 'password');

// Add to cart
await SoundexAPI.addToCart(productId, quantity);

// Book service
await SoundexAPI.bookService({
    customer_name: 'John Doe',
    email: 'john@example.com',
    phone: '1234567890',
    device_type: 'Smartphone',
    issue_description: 'Screen broken'
});
```

### Direct API Calls:
```javascript
// Get products
fetch('api.php?action=get_products')
    .then(response => response.json())
    .then(data => console.log(data.products));

// Submit application
const formData = new FormData();
formData.append('full_name', 'John Doe');
formData.append('email', 'john@example.com');
// ... add other fields

fetch('api.php?action=submit_application', {
    method: 'POST',
    body: formData
});
```

## 🔐 Security Features

- Password hashing with PHP's `password_hash()`
- SQL injection prevention with prepared statements
- Session token management
- CSRF protection ready
- File upload validation

## 📁 Directory Structure

```
soundex/
├── db_config.php
├── create_tables.php
├── insert_sample_data.php
├── api.php
├── UserManager.php
├── ProductManager.php
├── ServiceManager.php
├── ApplicationManager.php
├── ContactManager.php
├── js/
│   └── soundex_api.js
├── uploads/
│   └── applications/
└── pages/
    ├── index.html
    ├── signup.html
    ├── INTERNSHIP.html
    └── ... (your existing pages)
```

## 🚀 Getting Started

1. Set up your database and run the setup scripts
2. Test the API with the sample admin account
3. Integrate the JavaScript helpers into your existing forms
4. Customize the database structure as needed

## 🆘 Troubleshooting

### Common Issues:
- **Database Connection Failed**: Check `db_config.php` credentials
- **Permission Denied**: Ensure web server can write to `uploads/` directory
- **API Not Working**: Check PHP error logs and ensure all PHP files are accessible
- **CORS Errors**: Make sure API headers are properly set

### Testing:
Visit `http://localhost/soundex/create_tables.php` to create tables
Visit `http://localhost/soundex/insert_sample_data.php` to add sample data

## 📞 Support

For issues or questions about the database system, check the PHP error logs or contact the development team.