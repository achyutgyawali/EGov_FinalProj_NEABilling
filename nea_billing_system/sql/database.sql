CREATE DATABASE nea_billing_system;

USE nea_billing_system;

-- Admin table
CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Customer table
CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    contact VARCHAR(20) NOT NULL,
    address VARCHAR(255) NOT NULL,
    branch_id INT NOT NULL,
    demand_type_id INT NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Branch table
CREATE TABLE branches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

-- Demand type table
CREATE TABLE demand_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(50) NOT NULL UNIQUE
);

-- Rates for demand types
CREATE TABLE rates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    demand_type_id INT NOT NULL,
    rate_per_unit DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (demand_type_id) REFERENCES demand_types(id)
);

-- Payment options table
CREATE TABLE payment_options (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

-- Bills table
CREATE TABLE bills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    issue_date DATE NOT NULL,
    due_date DATE NOT NULL,
    units_consumed INT NOT NULL,
    fine DECIMAL(10, 2) DEFAULT 0,
    discount DECIMAL(10, 2) DEFAULT 0,
    status ENUM('Pending', 'Paid') DEFAULT 'Pending',
    FOREIGN KEY (customer_id) REFERENCES customers(id)
);

-- Payment history table
CREATE TABLE payment_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bill_id INT NOT NULL,
    payment_date DATE NOT NULL,
    amount_paid DECIMAL(10, 2) NOT NULL,
    payment_method_id INT NOT NULL,
    FOREIGN KEY (bill_id) REFERENCES bills(id),
    FOREIGN KEY (payment_method_id) REFERENCES payment_options(id)
);

-- Insert default admin credentials
INSERT INTO admin (username, password) VALUES ('admin', MD5('admin123'));
