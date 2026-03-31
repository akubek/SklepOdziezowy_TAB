-- clean database
DROP TABLE IF EXISTS order_items CASCADE;
DROP TABLE IF EXISTS orders CASCADE;
DROP TABLE IF EXISTS reviews CASCADE;
DROP TABLE IF EXISTS product_variants CASCADE;
DROP TABLE IF EXISTS products CASCADE;
DROP TABLE IF EXISTS promotions CASCADE;
DROP TABLE IF EXISTS categories CASCADE;
DROP TABLE IF EXISTS users CASCADE;

-- Następnie usuwamy nasze własne typy wyliczeniowe ENUM
DROP TYPE IF EXISTS gender_type CASCADE;
DROP TYPE IF EXISTS user_role_type CASCADE;
DROP TYPE IF EXISTS discount_type_enum CASCADE;


-- Initialize database structure

-- Enums
CREATE TYPE gender_type AS ENUM ('M', 'F', 'OTHER');
CREATE TYPE user_role_type AS ENUM ('CLIENT', 'EMPLOYEE', 'MANAGER');
CREATE TYPE discount_type_enum AS ENUM ('PERCENTAGE', 'FIXED_AMOUNT');


-- Tables with no fk dependencies
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255),
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    birth_date DATE,
    gender gender_type,
    address JSONB,
    phone_number VARCHAR(20),
    role user_role_type NOT NULL DEFAULT 'CLIENT',
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    parent_id INT REFERENCES categories(id) ON DELETE SET NULL
);

CREATE TABLE promotions (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    code VARCHAR(50) UNIQUE,
    discount_type discount_type_enum NOT NULL,
    discount_value DECIMAL(10,2) NOT NULL,
    conditions JSONB DEFAULT '{}'::jsonb,
    is_public BOOLEAN NOT NULL DEFAULT FALSE,
    start_date TIMESTAMP,
    end_date TIMESTAMP,
    is_active BOOLEAN NOT NULL DEFAULT TRUE
);


--products with fk dependencies
CREATE TABLE products (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    brand_name VARCHAR(100),
    category_id INT NOT NULL REFERENCES categories(id) ON DELETE RESTRICT,
    base_price DECIMAL(10,2) NOT NULL CHECK (base_price >= 0),
    attributes JSONB DEFAULT '{}'::jsonb,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE product_variants (
    id SERIAL PRIMARY KEY,
    product_id INT NOT NULL REFERENCES products(id) ON DELETE CASCADE,
    sku VARCHAR(100) UNIQUE NOT NULL,
    attributes JSONB NOT NULL DEFAULT '{}'::jsonb,
    images JSONB NOT NULL DEFAULT '[]'::jsonb,
    price_modifier DECIMAL(10,2) DEFAULT 0.00,
    stock_quantity INT NOT NULL DEFAULT 0 CHECK (stock_quantity >= 0)
);

CREATE TABLE reviews (
    id SERIAL PRIMARY KEY,
    product_id INT NOT NULL REFERENCES products(id) ON DELETE CASCADE,
    user_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE orders (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL REFERENCES users(id) ON DELETE RESTRICT,
    status VARCHAR(50) NOT NULL DEFAULT 'NEW',
    total_price DECIMAL(10,2) NOT NULL,
    applied_promotion_id INT REFERENCES promotions(id) ON DELETE SET NULL,
    shipping_address JSONB NOT NULL,
    delivery_method VARCHAR(100) NOT NULL,
    payment_method VARCHAR(100) NOT NULL,
    wants_invoice BOOLEAN NOT NULL DEFAULT FALSE,
    invoice_company_name VARCHAR(255),
    invoice_nip VARCHAR(50),
    employee_id INT REFERENCES users(id) ON DELETE SET NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    paid_at TIMESTAMP,
    shipped_at TIMESTAMP,
    completed_at TIMESTAMP
);

CREATE TABLE order_items (
    id SERIAL PRIMARY KEY,
    order_id INT NOT NULL REFERENCES orders(id) ON DELETE CASCADE,
    variant_id INT NOT NULL REFERENCES product_variants(id) ON DELETE RESTRICT,
    quantity INT NOT NULL CHECK (quantity > 0),
    unit_price DECIMAL(10,2) NOT NULL CHECK (unit_price >= 0)
);