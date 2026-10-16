# PHP Form + MySQL

A small PHP contact form that saves submissions to MySQL using PDO prepared statements.

## Files
- `index.php` — form and submission handling
- `db.php` — database connection
- `schema.sql` — database and table setup

## Requirements
- PHP 8.0+
- MySQL 8+ or compatible MariaDB
- PHP extensions `pdo` and `pdo_mysql`
- Apache/Nginx configured to serve this folder

## 1. Create the database
For a local MySQL server, run:

```bash
sudo mysql < schema.sql
```

The sample SQL creates `php_form`, the `submissions` table, and a local database user. Change the sample password before using it.

## 2. Configure the app
Set these environment variables for the PHP/Apache service:

```text
DB_HOST=127.0.0.1
DB_NAME=php_form
DB_USER=php_user
DB_PASS=your_actual_password
```

If MySQL is on a separate server, set `DB_HOST` to that database server's private IP or private DNS name. Do not expose MySQL port 3306 to the internet.

For a quick lab only, you can edit the defaults in `db.php`; do not commit real passwords to a public Git repository.

## 3. Test locally
From this directory:

```bash
php -S 0.0.0.0:8080
```

Open `http://SERVER-IP:8080`. For a real deployment, use Apache/Nginx and PHP-FPM instead of PHP's built-in development server.

## 4. Put it on GitHub, then clone it on EC2
On your own computer, inside this folder:

```bash
git init
git add .
git commit -m "Add PHP form with MySQL"
git branch -M main
git remote add origin https://github.com/YOUR-USERNAME/php-form-app.git
git push -u origin main
```

On the EC2 instance:

```bash
sudo apt update
sudo apt install -y git php php-mysql mysql-client
git clone https://github.com/YOUR-USERNAME/php-form-app.git
cd php-form-app
```

Replace the URL with your own GitHub repository URL.

## AWS subnet lab notes
- A public subnet normally has a route to an Internet Gateway, and the EC2 instance needs a public IPv4 address plus suitable security-group rules to be reachable directly from the internet.
- A private subnet does not make the app directly internet-accessible. Use a load balancer, VPN, bastion/SSM-based administration, or another deliberate access path.
- If the database is on a separate private EC2/RDS instance, allow MySQL (TCP 3306) only from the app server's security group, not from `0.0.0.0/0`.
- For an HTTP lab, allow TCP 80 from your own IP where practical. Avoid exposing a development server or database publicly.
