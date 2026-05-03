# ⚡ NEA Billing System

A web-based **Electricity Billing Management System** built for the **Nepal Electricity Authority (NEA)** as an E-Governance final project. The system digitizes the electricity billing process — from bill generation by admins to online payment by customers — using **Khalti** as the integrated digital payment gateway.

---

## 📋 Table of Contents

- [About the Project](#about-the-project)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Project Structure](#project-structure)
- [Database Schema](#database-schema)
- [Prerequisites](#prerequisites)
- [How to Run](#how-to-run)
- [Default Credentials](#default-credentials)

---

## 📌 About the Project

The NEA Billing System is a role-based web application with two user types:

- **Admin** — Manages the system: adds branches, demand types, rates, payment options, generates electricity bills for customers, and views system-wide reports.
- **Customer (User)** — Registers, logs in, views their electricity bills, and pays online via the **Khalti** digital payment gateway.

---

## ✨ Features

### 🔐 Authentication
- Secure login and registration system for customers
- Role-based session management (Admin vs. Customer)
- MD5-hashed password storage
- Reserved `admin` username protection

### 👤 Customer Panel
| Feature | Description |
|---|---|
| **My Bills** | View all electricity bills with issue date, due date, units consumed, fine, discount, and total amount |
| **Pay Now** | Pay pending bills online using **Khalti** digital payment |
| **Payment History** | View a full history of past payments |
| **Support Center** | Access help and support information |
| **Fine & Discount Logic** | Automatic 2% fine if paid after due date; 2% discount if paid before due date |

### 🛠️ Admin Panel
| Feature | Description |
|---|---|
| **Generate Bill** | Generate electricity bills by entering customer SCNO (Service Connection Number), meter readings, issue date, and due date |
| **Add Branch** | Add NEA branch offices |
| **Add Demand Type** | Add electricity demand categories (e.g., Residential, Commercial) |
| **Add Rate** | Set per-unit electricity rates for each demand type |
| **Add Payment Option** | Configure available payment methods |
| **Generate Report** | View system-wide reports: customers by branch, customers by demand type, paid/pending bill count, and total revenue |
| **Search Customer** | Search and look up registered customer details |

### 💳 Khalti Payment Integration
- Initiates payment via **Khalti e-Payment API v2** (sandbox/dev mode)
- Verifies payment on the server side after callback
- Updates bill status to `Paid` upon successful payment

---

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| **Backend** | PHP (Core PHP, no framework) |
| **Database** | MySQL |
| **Frontend** | HTML5, CSS3, Vanilla JavaScript |
| **Payment Gateway** | [Khalti](https://khalti.com/) (Nepal digital wallet) |
| **Local Server** | XAMPP / WAMP / any PHP+MySQL server |
| **Session Management** | PHP Sessions |

---

## 📁 Project Structure

```
nea_billing_system/
│
├── admin/                        # Admin panel pages
│   ├── admin_dashboard.php       # Main admin dashboard with sidebar navigation
│   ├── generate_bill.php         # Bill generation form and logic
│   ├── search_customer.php       # Customer search functionality
│   ├── report.php                # System reports (revenue, bill stats, customers)
│   ├── add_branch.php            # Add NEA branches
│   ├── add_demand_type.php       # Add electricity demand types
│   ├── add_rate.php              # Set per-unit rates per demand type
│   └── add_payment_option.php    # Manage payment options
│
├── user/                         # Customer panel pages
│   ├── user_dashboard.php        # Customer dashboard
│   ├── my_bill.php               # View all bills with fine/discount calculation
│   ├── payment.php               # Payment initiation page
│   ├── khalti_payment.php        # Khalti API integration (initiate payment)
│   ├── verify_khalti.php         # Khalti payment verification after callback
│   ├── payment_success.php       # Success page after payment
│   ├── success.php               # Generic success redirect
│   ├── my_payment_history.php    # Payment history for the logged-in customer
│   └── support_center.php        # Support/help page
│
├── db/
│   └── connection.php            # MySQL database connection config
│
├── sql/
│   └── database.sql              # Full database schema and default admin seed
│
├── css/
│   ├── style.css                 # Main stylesheet (login, register, admin)
│   └── another.css               # Additional styles
│
├── js/
│   └── script.js                 # Frontend JS (AJAX section loading for dashboard)
│
├── assets/
│   └── logo.png                  # NEA logo
│
├── index.php                     # Entry point — redirects to login
├── login.php                     # Login page
├── register.php                  # Customer registration page
├── process_login.php             # Login form handler (authenticates admin & customers)
└── logout.php                    # Destroys session and redirects to login
```

---

## 🗄️ Database Schema

The system uses the following tables in a MySQL database named `nea_billing_system`:

| Table | Description |
|---|---|
| `admin` | Stores admin credentials |
| `customers` | Registered customer accounts with branch & demand type |
| `branches` | NEA branch offices |
| `demand_types` | Electricity demand categories |
| `rates` | Per-unit electricity rates linked to demand types |
| `payment_options` | Available payment methods |
| `bills` | Generated electricity bills per customer |
| `payment_history` | Record of all completed payments |

---

## ✅ Prerequisites

Before running the project, make sure you have the following installed:

- **XAMPP** (or WAMP / LAMP) — includes Apache, PHP, and MySQL
  - Download: https://www.apachefriends.org/
- **PHP** >= 7.4
- **MySQL** >= 5.7
- A modern web browser (Chrome, Firefox, Edge)

---

## 🚀 How to Run

### Step 1 — Clone or Copy the Project

Place the project folder inside your server's web root:

```
# For XAMPP (Windows):
C:\xampp\htdocs\egov_finalproject\nea_billing_system\

# For WAMP (Windows):
C:\wamp64\www\egov_finalproject\nea_billing_system\
```

### Step 2 — Start the Server

1. Open **XAMPP Control Panel**
2. Start **Apache** and **MySQL** modules

### Step 3 — Set Up the Database

1. Open your browser and go to: `http://localhost/phpmyadmin`
2. Click **New** to create a new database named `nea_billing_system`
3. Select the newly created database
4. Click the **Import** tab
5. Choose the file: `sql/database.sql`
6. Click **Go** to import the schema and seed data

> Alternatively, run this in the MySQL terminal:
> ```sql
> SOURCE C:/xampp/htdocs/egov_finalproject/nea_billing_system/sql/database.sql;
> ```

### Step 4 — Configure Database Connection (if needed)

Open `db/connection.php` and verify the credentials match your local setup:

```php
$servername = "localhost";
$username   = "root";   // your MySQL username
$password   = "";        // your MySQL password (blank by default in XAMPP)
$dbname     = "nea_billing_system";
```

### Step 5 — Run the Application

Open your browser and navigate to:

```
http://localhost/egov_finalproject/nea_billing_system/
```

You will be redirected to the **Login page**.

---

## 🔑 Default Credentials

| Role | Username | Password |
|---|---|---|
| **Admin** | `admin` | `admin123` |
| **Customer** | *(register a new account)* | *(set during registration)* |

> ⚠️ The admin account is seeded automatically when you import `database.sql`.  
> Customer accounts must be registered through the **Register** page.

---

## 💡 Important Notes

- **Khalti Integration** uses the **sandbox/dev** environment. To use it in production, replace the test API keys in `user/khalti_payment.php` with live keys from your [Khalti Merchant Dashboard](https://merchant.khalti.com/).
- Bills are calculated based on: `Units Consumed × Rate Per Unit ± Fine/Discount`
- The admin dashboard uses **AJAX-based section loading** — clicking sidebar buttons dynamically loads PHP sections into the main content area without full page reloads.
- Passwords are hashed using **MD5**. For production use, consider upgrading to `password_hash()` / `password_verify()` with bcrypt.

---

## 👨‍💻 Author

**Developed as an E-Governance Final Project**  
*6th Semester — College Project*

---