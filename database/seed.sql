-- seed data for testing

-- sample categories
INSERT INTO categories (id, name, parent_id) VALUES 
(1, 'Odzież', NULL),
-- male, female, kid and unisex clothing categories
(11, 'Męska',1),
(12, 'Damska',1),
(13, 'Dziecięca', 1),
(14, 'Unisex',1),
-- coats, shirst and t-shirts in male category
(101, 'Kurtki',11),
(102, 'Koszule',11),
(103, 'T-Shirty',11),
-- coats, shirst and t-shirts in female category
(201, 'Kurtki',12),
(202, 'Koszule',12),
(203, 'T-Shirty',12),
-- sweaters and t-shirst in child category
(301, 'Swetry',13),
(302, 'Koszulki',13),
-- t-shirst in unisex category
(401, 'Koszulki',14);

-- Sample products - t-shirts
INSERT INTO products (category_id, brand_name, name, description, base_price) VALUES 
(103, 'Nike', 'Koszulka Sportowa Basic', 'Oddychająca koszulka.', 129.99),
(203, 'Nike', 'Koszulka Sportowa Basic', 'Oddychająca koszulka.', 129.99);
