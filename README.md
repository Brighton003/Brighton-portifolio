# Setup Instructions - DBP Portfolio Website

This project is a personal profile website with a PHP backend and MySQL database for dynamic content management.

## Prerequisites
- Web Server (XAMPP, WAMP, or any Apache/PHP environment)
- MySQL Server
- PHP 8.0+
- Composer (for PHPMailer)

## Local Setup
1. **Clone/Copy Project:**
   Copy the project files to your server's web root (e.g., `C:\xampp\htdocs\brighton-portfolio`).

2. **Database Configuration:**
   - Open your MySQL management tool (e.g., phpMyAdmin).
   - Create a new database named `brighton_portfolio`.
   - Import the `database.sql` file provided in the root directory.

3. **Database Connection:**
   - Open `includes/db_connect.php`.
   - Update the `$user` and `$pass` variables with your MySQL credentials.

4. **Dependencies:**
   - Open your terminal in the project directory.
   - Run `composer install` to install PHPMailer.

5. **Contact Form Email:**
   - Open `contact_handler.php`.
   - If you want to use SMTP (recommended), uncomment and configure the SMTP block with your email credentials.

6. **Admin Access:**
   - Navigate to `http://localhost/brighton-portfolio/admin`.
   - Default login:
     - **Username:** `admin`
     - **Password:** `admin123`
   - **IMPORTANT:** Change your password immediately after logging in.

## Live Server Deployment
1. Upload all files to your server.
2. Create the MySQL database on your live hosting.
3. Import `database.sql`.
4. Update `includes/db_connect.php` with live credentials.
5. Ensure the `images/projects/` directory exists and has write permissions for image uploads.
