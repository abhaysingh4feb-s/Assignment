# Professional Contact Manager

A modern Laravel 9 contact management system with XML bulk import/export functionality.

## Features

- **Complete CRUD Operations**: Create, Read, Update, Delete contacts
- **XML Bulk Import/Export**: Import/export contacts with validation
- **Advanced Search**: Search by name, phone, email, company
- **Responsive UI**: Modern Bootstrap 5 interface
- **Smart Pagination**: Efficient data browsing
- **Form Validation**: Client and server-side validation
- **Error Handling**: Comprehensive error management

## Installation

1. **Clone & Install**
```bash
git clone <repository-url>
cd contact-manager
composer install
```

2. **Environment Setup**
```bash
cp .env.example .env
php artisan key:generate
```

3. **Database Configuration**
Update `.env` with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=contacts_db
DB_USERNAME=root
DB_PASSWORD=
```

4. **Create Database & Migrate**
```bash
mysql -u root -e "CREATE DATABASE contacts_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
php artisan migrate
```

5. **Start Development Server**
```bash
php artisan serve
```

Visit `http://localhost:8000` to access the application.

## XML Import Format

```xml
<?xml version="1.0" encoding="UTF-8"?>
<contacts>
    <contact>
        <name>John Doe</name>
        <phone>+1 555 123 4567</phone>
        <email>john@example.com</email>
        <company>Acme Corporation</company>
        <address>123 Main St, City, State</address>
        <notes>Important client</notes>
    </contact>
</contacts>
```

## Database Schema

| Column | Type | Required | Description |
|--------|------|----------|-------------|
| name | varchar(255) | Yes | Contact name |
| phone | varchar(20) | Yes | Phone number |
| email | varchar(255) | No | Email address |
| company | varchar(255) | No | Company name |
| address | text | No | Full address |
| notes | text | No | Additional notes |

## Key Routes

| Method | URL | Description |
|--------|-----|-------------|
| GET | `/contacts` | List contacts |
| POST | `/contacts` | Create contact |
| GET | `/contacts/{id}` | View contact |
| PUT | `/contacts/{id}` | Update contact |
| DELETE | `/contacts/{id}` | Delete contact |
| GET | `/contacts-import` | Import form |
| POST | `/contacts-import` | Process import |
| GET | `/contacts-export` | Export XML |

## Testing

Use the included `sample_contacts.xml` file to test XML import with 49 sample contacts.

## Architecture

- **Laravel 9**: Modern PHP framework
- **MySQL**: Reliable database with proper indexing
- **Bootstrap 5**: Responsive UI framework
- **MVC Pattern**: Clean separation of concerns
- **Form Requests**: Dedicated validation classes
- **Eloquent ORM**: Secure database interactions

## Security Features

- CSRF protection on all forms
- Input validation and sanitization
- SQL injection prevention
- XSS protection with Blade templates
- File upload validation for XML imports

Built with modern web development best practices for interview and production use.