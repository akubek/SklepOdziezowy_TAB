-- seed data for testing

-- sample categories
INSERT INTO categories (id, name, parent_id, image_path) VALUES 
(1, 'Odzież', NULL, 'https://placehold.co/400x500?text=Odzież'),
-- male, female, kid and unisex clothing categories
(11, 'Męska', 1, 'https://placehold.co/400x500?text=Męska'),
(12, 'Damska', 1, 'https://placehold.co/400x500?text=Damska'),
(13, 'Dziecięca', 1, 'https://placehold.co/400x500?text=Dziecięca'),
(14, 'Unisex', 1, 'https://placehold.co/400x500?text=Unisex'),
-- coats, shirts and t-shirts in male category
(101, 'Kurtki', 11, 'https://placehold.co/400x500?text=Kurtki+Męskie'),
(102, 'Koszule', 11, 'https://placehold.co/400x500?text=Koszule+Męskie'),
(103, 'T-Shirty', 11, 'https://placehold.co/400x500?text=T-Shirty+Męskie'),
-- coats, shirts and t-shirts in female category
(201, 'Kurtki', 12, 'https://placehold.co/400x500?text=Kurtki+Damskie'),
(202, 'Koszule', 12, 'https://placehold.co/400x500?text=Koszule+Damskie'),
(203, 'T-Shirty', 12, 'https://placehold.co/400x500?text=T-Shirty+Damskie'),
-- sweaters and t-shirts in child category
(301, 'Swetry', 13, 'https://placehold.co/400x500?text=Swetry'),
(302, 'Koszulki', 13, 'https://placehold.co/400x500?text=Koszulki'),
-- t-shirts in unisex category
(401, 'Koszulki', 14, 'https://placehold.co/400x500?text=Koszulki+Unisex'),
(402, 'Testowe', 14, 'https://placehold.co/400x500?text=Testowe+Unisex');

SELECT setval('categories_id_seq', (SELECT MAX(id) FROM categories));