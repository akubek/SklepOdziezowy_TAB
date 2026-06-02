-- Widok do Raportu 1
CREATE OR REPLACE VIEW v_sales_report AS
SELECT 
    DATE(o.created_at) AS sale_date,
    p.brand_name,
    c.name AS category_name,
    pc.name AS parent_category_name,
    SUM(oi.quantity) AS total_quantity,
    SUM(oi.quantity * oi.unit_price) AS total_revenue
FROM orders o
JOIN order_items oi ON o.id = oi.order_id
JOIN product_variants pv ON oi.variant_id = pv.id
JOIN products p ON pv.product_id = p.id
-- Dołączamy kategorię produktu (np. "Kurtki")
JOIN categories c ON p.category_id = c.id
-- Dołączamy kategorię nadrzędną (np. "Męska") za pomocą LEFT JOIN, 
-- na wypadek gdyby produkt był przypisany do kategorii głównej
LEFT JOIN categories pc ON c.parent_id = pc.id
WHERE 
    o.status != 'CANCELLED' 
    AND o.payment_status = 'PAID'
GROUP BY 
    DATE(o.created_at),
    p.brand_name,
    c.name,
    pc.name;

-- Widok do Raportu 2
CREATE OR REPLACE VIEW v_demographics_report AS
SELECT 
    COALESCE(u.gender::text, 'Brak danych') AS gender,
    
    -- Wyliczanie wieku i grupowanie w przedziały (Age Buckets)
    CASE 
        WHEN u.birth_date IS NULL THEN 'Brak danych'
        WHEN EXTRACT(YEAR FROM AGE(CURRENT_DATE, u.birth_date)) < 18 THEN '<18'
        WHEN EXTRACT(YEAR FROM AGE(CURRENT_DATE, u.birth_date)) BETWEEN 18 AND 24 THEN '18-24'
        WHEN EXTRACT(YEAR FROM AGE(CURRENT_DATE, u.birth_date)) BETWEEN 25 AND 34 THEN '25-34'
        WHEN EXTRACT(YEAR FROM AGE(CURRENT_DATE, u.birth_date)) BETWEEN 35 AND 44 THEN '35-44'
        WHEN EXTRACT(YEAR FROM AGE(CURRENT_DATE, u.birth_date)) >= 45 THEN '45+'
        ELSE 'Brak danych'
    END AS age_group,
    
    -- Wyciąganie miasta z kolumny JSONB
    COALESCE(o.shipping_address->>'city', 'Nieznane') AS city,
    
    -- Hierarchia kategorii
    pc.name AS parent_category_name,
    c.name AS category_name,
    
    -- Marka i konkretny produkt
    p.brand_name,
    p.name AS product_name,

    -- Data zamówienia do późniejszego filtrowania
    o.created_at::date AS order_date,
    
    -- Agregacje
    SUM(oi.quantity) AS total_products_bought,
    SUM(oi.quantity * oi.unit_price) AS total_amount_spent

FROM users u
JOIN orders o ON u.id = o.user_id
JOIN order_items oi ON o.id = oi.order_id
JOIN product_variants pv ON oi.variant_id = pv.id
JOIN products p ON pv.product_id = p.id
JOIN categories c ON p.category_id = c.id
LEFT JOIN categories pc ON c.parent_id = pc.id

WHERE 
    o.status != 'CANCELLED' 
    AND o.payment_status = 'PAID'
    
GROUP BY 
    u.gender,
    age_group,
    o.shipping_address->>'city',
    pc.name,
    c.name,
    p.brand_name,
    p.name,
    o.created_at::date;

-- widok pomocniczy do popularnosci produktow
CREATE OR REPLACE VIEW v_popular_products AS
SELECT 
    DATE(o.created_at) AS sale_date,
    p.id AS product_id,
    p.name AS product_name,
    p.brand_name,
    c.name AS category_name,
    SUM(oi.quantity) AS total_sold,
    SUM(oi.quantity * oi.unit_price) AS total_revenue
FROM products p
JOIN product_variants pv ON p.id = pv.product_id
JOIN order_items oi ON pv.id = oi.variant_id
JOIN orders o ON oi.order_id = o.id
JOIN categories c ON p.category_id = c.id
WHERE 
    o.status != 'CANCELLED' 
    AND o.payment_status = 'PAID'
GROUP BY 
    DATE(o.created_at),
    p.id, 
    p.name, 
    p.brand_name, 
    c.name;

CREATE OR REPLACE VIEW v_users_extended AS
SELECT 
    id AS user_id,
    created_at::date AS registration_date,
    COALESCE(gender::text, 'Brak danych') AS gender,
    CASE 
        WHEN birth_date IS NULL THEN 'Brak danych'
        WHEN EXTRACT(YEAR FROM AGE(CURRENT_DATE, birth_date)) < 18 THEN '<18'
        WHEN EXTRACT(YEAR FROM AGE(CURRENT_DATE, birth_date)) BETWEEN 18 AND 24 THEN '18-24'
        WHEN EXTRACT(YEAR FROM AGE(CURRENT_DATE, birth_date)) BETWEEN 25 AND 34 THEN '25-34'
        WHEN EXTRACT(YEAR FROM AGE(CURRENT_DATE, birth_date)) BETWEEN 35 AND 44 THEN '35-44'
        WHEN EXTRACT(YEAR FROM AGE(CURRENT_DATE, birth_date)) >= 45 THEN '45+'
        ELSE 'Brak danych'
    END AS age_group
FROM users;

CREATE OR REPLACE VIEW v_registration_report AS
SELECT 
    registration_date,
    age_group,
    gender,
    COUNT(*) AS new_users_count
FROM v_users_extended
GROUP BY registration_date, age_group, gender;
