# Tembea Tanzania — PHP + MySQL

Premium tourism website starter with:
- Responsive landing page and destination cards
- Booking enquiry form saved to MySQL
- Admin login and booking status dashboard
- CSRF protection, password hashing, PDO prepared statements and output escaping
- Payment status field for admin records only (no payment gateway is integrated)

## Requirements
- PHP 8.1+ (PHP 8.2 recommended)
- MySQL 8+ or compatible MariaDB
- Apache (XAMPP is convenient for local testing)
- PHP extensions: `pdo_mysql`, `mbstring`

## Run on XAMPP (Windows)
1. Extract the `tembea_tanzania` folder into `C:\xampp\htdocs\`.
2. Start **Apache** and **MySQL** from XAMPP Control Panel.
3. Open phpMyAdmin at `http://localhost/phpmyadmin`.
4. Import `schema.sql`. It creates the `tembea_tanzania` database and sample destination records.
5. Open `config.php` and adjust `DB_HOST`, `DB_NAME`, `DB_USER`, and `DB_PASS` to match your MySQL setup.
6. Visit `http://localhost/tembea_tanzania/`.
7. Create the first admin at `http://localhost/tembea_tanzania/setup_admin.php`. Use a strong password (12+ characters).
8. After admin creation, **delete `setup_admin.php` from the server**.
9. Open `http://localhost/tembea_tanzania/admin/login.php` and sign in.

## Booking flow
- Visitor chooses a destination and submits a booking enquiry.
- The enquiry is saved with status `pending` and payment status `not_started`.
- Admin can change booking status and record payment status after checking an external payment record.
- The website does not send emails or charge money by itself in this starter.

## Payment gateway
No real payment provider is connected. Before accepting money, choose a provider available to your registered business (for example, a mobile-money or card gateway), obtain credentials, implement server-side payment initiation and signed callback/webhook verification, and test in sandbox mode. Never put secret keys in public JavaScript or trust a browser redirect as proof of payment. Only mark a booking paid after verified provider confirmation.

## Production checklist
- Use HTTPS and a proper domain.
- Use a dedicated database user with limited permissions; do not use MySQL root.
- Store secrets in environment variables, not in source control.
- Delete `setup_admin.php`.
- Back up the database, set up monitoring, rate limiting and spam protection.
- Verify all destination pricing, images, operators, legal terms, refund/cancellation policy and availability before launch.
- The seeded prices and dashboard numbers are examples; do not advertise them as verified live offers.
- External Unsplash images and Google Fonts need internet access. Replace image URLs with licensed/local assets before commercial launch.
- Admin can currently manage booking/payment status and view destinations; create/edit/delete destination UI is not included yet.

## Important
This is a working starter project, not a fully audited production booking/payment platform. Test locally before deployment.
