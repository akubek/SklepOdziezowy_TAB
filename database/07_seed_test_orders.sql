-- ==========================================
-- 1. GRUPA 1
-- ==========================================
INSERT INTO orders (id, user_id, status, payment_status, total_price, shipping_address, delivery_method, payment_method, created_at) VALUES 

-- User 1 (Regularny klient - 5 zamówień)
(1, 1, 'COMPLETED', 'PAID', 1028.99, '{"city": "Warszawa", "street": "Złota 44", "zip_code": "00-120"}', 'courier', 'payu', '2025-01-15 10:30:00'),
(2, 1, 'COMPLETED', 'PAID', 59.99, '{"city": "Warszawa", "street": "Złota 44", "zip_code": "00-120"}', 'paczkomat', 'blik', '2025-04-20 14:15:00'),
(3, 1, 'COMPLETED', 'PAID', 199.50, '{"city": "Warszawa", "street": "Złota 44", "zip_code": "00-120"}', 'courier', 'payu', '2025-08-05 09:00:00'),
(4, 1, 'COMPLETED', 'PAID', 1028.99, '{"city": "Warszawa", "street": "Złota 44", "zip_code": "00-120"}', 'courier', 'online', '2025-11-28 08:30:00'), -- Black Friday
(5, 1, 'PROCESSING', 'PAID', 89.99, '{"city": "Warszawa", "street": "Złota 44", "zip_code": "00-120"}', 'paczkomat', 'blik', '2025-12-10 18:45:00'),

-- User 2 (Okazjonalny - 2 zamówienia)
(6, 2, 'COMPLETED', 'PAID', 369.98, '{"city": "Kraków", "street": "Floriańska 12", "zip_code": "31-021"}', 'courier', 'transfer', '2025-05-10 11:20:00'),
(7, 2, 'SHIPPED', 'PAID', 99.99, '{"city": "Kraków", "street": "Floriańska 12", "zip_code": "31-021"}', 'paczkomat', 'blik', '2025-10-12 16:10:00'),

-- User 3 (Okazjonalny - 1 zamówienie)
(8, 3, 'COMPLETED', 'PAID', 199.50, '{"city": "Poznań", "street": "Półwiejska 5", "zip_code": "61-886"}', 'pickup', 'cash_on_delivery', '2025-07-07 12:00:00'),
-- User 4 (Okazjonalny - 1 zamówienie, nieopłacone)
(9, 4, 'NEW', 'UNPAID', 249.99, '{"city": "Wrocław", "street": "Świdnicka 8", "zip_code": "50-067"}', 'courier', 'cash_on_delivery', CURRENT_TIMESTAMP);
-- User 5 celowo pominięty (brak zakupów)

INSERT INTO order_items (order_id, variant_id, variant_name, quantity, unit_price) VALUES 

-- Zamówienie 1 (User 1) - 1028.99
(1, 4, 'Kurtka Zimowa Puchowa The North Face (M, black)', 1, 899.00),
(1, 9, 'Koszulka Sportowa Nike (S, black)', 1, 129.99),

-- Zamówienie 2 (User 1) - 59.99
(2, 19, 'Koszulka Oversize H&M (M, gray_melange)', 1, 59.99),

-- Zamówienie 3 (User 1) - 199.50
(3, 7, 'Koszula Elegancka Vistula (39, white)', 1, 199.50),

-- Zamówienie 4 (User 1) - Black Friday 1028.99
(4, 5, 'Kurtka Zimowa Puchowa The North Face (L, black)', 1, 899.00),
(4, 10, 'Koszulka Sportowa Nike (M, black)', 1, 129.99),

-- Zamówienie 5 (User 1) - 89.99
(5, 17, 'Sweter z Reniferem Smyk (104, red)', 1, 89.99),

-- Zamówienie 6 (User 2) - 369.98
(6, 12, 'Kurtka Ramoneska Zara (S, black)', 1, 249.99),
(6, 14, 'Koszula Wiskozowa Reserved (M, light_blue)', 1, 119.99),

-- Zamówienie 7 (User 2) - 99.99
(7, 16, 'Top Treningowy Puma (S, pink)', 1, 99.99),

-- Zamówienie 8 (User 3) - 199.50
(8, 8, 'Koszula Elegancka Vistula (40, white)', 1, 199.50),

-- Zamówienie 9 (User 4) - 249.99
(9, 13, 'Kurtka Ramoneska Zara (M, black)', 1, 249.99);

-- ==========================================
-- ZAMÓWIENIA: GRUPA 2 (18 Mężczyzn z wiekiem, ID 6 - 23)
-- ==========================================

INSERT INTO orders (id, user_id, status, payment_status, total_price, shipping_address, delivery_method, payment_method, created_at) VALUES 

-- ---------------------------------------------------------
-- PODGRUPA 2A: 12 użytkowników (1-2 zakupy, głównie odzież męska) 
-- ID: 6 - 17
-- ---------------------------------------------------------
-- User 6 (1 zakup)
(20, 6, 'COMPLETED', 'PAID', 899.00, '{"city": "Wrocław", "street": "Rynek 12", "zip_code": "50-101"}', 'courier', 'payu', '2025-01-10 10:00:00'),
-- User 7 (2 zakupy)
(21, 7, 'COMPLETED', 'PAID', 199.50, '{"city": "Poznań", "street": "Półwiejska 10", "zip_code": "61-886"}', 'paczkomat', 'blik', '2025-03-05 14:20:00'),
(22, 7, 'COMPLETED', 'PAID', 129.99, '{"city": "Poznań", "street": "Półwiejska 10", "zip_code": "61-886"}', 'paczkomat', 'blik', '2025-06-15 09:15:00'),
-- User 8 (1 zakup - 2 sztuki koszulki)
(23, 8, 'COMPLETED', 'PAID', 259.98, '{"city": "Gdańsk", "street": "Długa 5", "zip_code": "80-827"}', 'courier', 'transfer', '2025-08-20 18:30:00'),
-- User 9 (1 zakup - kurtka + koszulka)
(24, 9, 'COMPLETED', 'PAID', 1028.99, '{"city": "Warszawa", "street": "Prosta 51", "zip_code": "00-838"}', 'courier', 'payu', '2025-11-28 08:00:00'),
-- User 10 (2 zakupy)
(25, 10, 'COMPLETED', 'PAID', 199.50, '{"city": "Kraków", "street": "Floriańska 9", "zip_code": "31-019"}', 'pickup', 'cash_on_delivery', '2025-02-14 12:00:00'),
(26, 10, 'COMPLETED', 'PAID', 199.50, '{"city": "Kraków", "street": "Floriańska 9", "zip_code": "31-019"}', 'paczkomat', 'online', '2025-09-10 16:45:00'),
-- User 11 (1 zakup)
(27, 11, 'COMPLETED', 'PAID', 899.00, '{"city": "Katowice", "street": "Mariacka 1", "zip_code": "40-014"}', 'courier', 'payu', '2025-12-05 11:10:00'),
-- User 12 (1 zakup)
(28, 12, 'SHIPPED', 'PAID', 129.99, '{"city": "Łódź", "street": "Piotrkowska 100", "zip_code": "90-001"}', 'paczkomat', 'blik', '2026-01-20 19:00:00'),
-- User 13 (2 zakupy)
(29, 13, 'COMPLETED', 'PAID', 129.99, '{"city": "Szczecin", "street": "Wielka 2", "zip_code": "70-001"}', 'paczkomat', 'blik', '2025-05-15 13:30:00'),
(30, 13, 'COMPLETED', 'PAID', 199.50, '{"city": "Szczecin", "street": "Wielka 2", "zip_code": "70-001"}', 'courier', 'online', '2025-10-02 10:20:00'),
-- User 14 (1 zakup)
(31, 14, 'COMPLETED', 'PAID', 899.00, '{"city": "Bydgoszcz", "street": "Mostowa 3", "zip_code": "85-110"}', 'courier', 'transfer', '2025-11-15 15:00:00'),
-- User 15 (1 zakup)
(32, 15, 'COMPLETED', 'PAID', 199.50, '{"city": "Lublin", "street": "Krakowskie Przedmieście 1", "zip_code": "20-002"}', 'pickup', 'cash_on_delivery', '2025-07-07 09:45:00'),
-- User 16 (2 zakupy)
(33, 16, 'COMPLETED', 'PAID', 199.50, '{"city": "Białystok", "street": "Lipowa 5", "zip_code": "15-424"}', 'courier', 'payu', '2025-04-12 11:11:00'),
(34, 16, 'PROCESSING', 'PAID', 129.99, '{"city": "Białystok", "street": "Lipowa 5", "zip_code": "15-424"}', 'paczkomat', 'blik', '2026-05-25 18:20:00'),
-- User 17 (1 zakup - 3 koszulki)
(35, 17, 'COMPLETED', 'PAID', 389.97, '{"city": "Rzeszów", "street": "Rejtana 10", "zip_code": "35-310"}', 'courier', 'online', '2025-08-30 14:00:00'),

-- ---------------------------------------------------------
-- PODGRUPA 2B: 4 użytkowników (Dużo zakupów, np. 3-5 zamówień)
-- ID: 18 - 21
-- ---------------------------------------------------------
-- User 18 (3 zamówienia)
(36, 18, 'COMPLETED', 'PAID', 899.00, '{"city": "Warszawa", "street": "Hoża 12", "zip_code": "00-528"}', 'courier', 'payu', '2024-11-20 10:00:00'),
(37, 18, 'COMPLETED', 'PAID', 199.50, '{"city": "Warszawa", "street": "Hoża 12", "zip_code": "00-528"}', 'paczkomat', 'blik', '2025-03-10 09:00:00'),
(38, 18, 'COMPLETED', 'PAID', 129.99, '{"city": "Warszawa", "street": "Hoża 12", "zip_code": "00-528"}', 'paczkomat', 'blik', '2025-07-22 15:30:00'),
-- User 19 (4 zamówienia)
(39, 19, 'COMPLETED', 'PAID', 129.99, '{"city": "Kraków", "street": "Grodzka 5", "zip_code": "31-006"}', 'pickup', 'cash_on_delivery', '2025-01-05 12:00:00'),
(40, 19, 'COMPLETED', 'PAID', 129.99, '{"city": "Kraków", "street": "Grodzka 5", "zip_code": "31-006"}', 'paczkomat', 'blik', '2025-04-15 16:45:00'),
(41, 19, 'COMPLETED', 'PAID', 199.50, '{"city": "Kraków", "street": "Grodzka 5", "zip_code": "31-006"}', 'courier', 'online', '2025-09-01 10:15:00'),
(42, 19, 'SHIPPED', 'PAID', 899.00, '{"city": "Kraków", "street": "Grodzka 5", "zip_code": "31-006"}', 'courier', 'payu', '2025-11-30 08:30:00'),
-- User 20 (3 zamówienia)
(43, 20, 'COMPLETED', 'PAID', 329.49, '{"city": "Wrocław", "street": "Oławska 2", "zip_code": "50-123"}', 'courier', 'transfer', '2025-02-28 14:20:00'),
(44, 20, 'COMPLETED', 'PAID', 899.00, '{"city": "Wrocław", "street": "Oławska 2", "zip_code": "50-123"}', 'courier', 'payu', '2025-10-15 11:10:00'),
(45, 20, 'NEW', 'UNPAID', 129.99, '{"city": "Wrocław", "street": "Oławska 2", "zip_code": "50-123"}', 'paczkomat', 'blik', '2026-05-28 19:00:00'),
-- User 21 (5 małych zamówień w ciągu roku)
(46, 21, 'COMPLETED', 'PAID', 129.99, '{"city": "Poznań", "street": "Gwarna 8", "zip_code": "61-702"}', 'paczkomat', 'blik', '2025-01-20 09:00:00'),
(47, 21, 'COMPLETED', 'PAID', 129.99, '{"city": "Poznań", "street": "Gwarna 8", "zip_code": "61-702"}', 'paczkomat', 'blik', '2025-03-25 10:00:00'),
(48, 21, 'COMPLETED', 'PAID', 129.99, '{"city": "Poznań", "street": "Gwarna 8", "zip_code": "61-702"}', 'paczkomat', 'blik', '2025-06-10 12:30:00'),
(49, 21, 'COMPLETED', 'PAID', 199.50, '{"city": "Poznań", "street": "Gwarna 8", "zip_code": "61-702"}', 'courier', 'online', '2025-09-05 14:15:00'),
(50, 21, 'COMPLETED', 'PAID', 199.50, '{"city": "Poznań", "street": "Gwarna 8", "zip_code": "61-702"}', 'pickup', 'cash_on_delivery', '2025-12-12 16:45:00'),

-- ---------------------------------------------------------
-- PODGRUPA 2C: 1 użytkownik (1 ogromne zamówienie z wielu kategorii)
-- ID: 22
-- Kurtka męska (899.00) + 2x Ramoneska damska (2x249.99) + 3x Sweter dzieciecy (3x89.99) + 4x Koszulka Unisex (4x59.99)
-- Razem: 899.00 + 499.98 + 269.97 + 239.96 = 1908.91
-- ---------------------------------------------------------
(51, 22, 'COMPLETED', 'PAID', 1908.91, '{"city": "Gdynia", "street": "Świętojańska 50", "zip_code": "81-393"}', 'courier', 'payu', '2025-12-10 18:00:00');

-- ---------------------------------------------------------
-- PODGRUPA 2D: 1 użytkownik (Brak zamówień)
-- ID: 23 (Celowo pominięty w tabeli orders)
-- ---------------------------------------------------------


-- ==========================================
-- POZYCJE ZAMÓWIEŃ DLA GRUPY 2
-- ==========================================

INSERT INTO order_items (order_id, variant_id, variant_name, quantity, unit_price) VALUES 

-- U6 (O20)
(20, 4, 'Kurtka Zimowa Puchowa The North Face (M, black)', 1, 899.00),

-- U7 (O21, O22)
(21, 7, 'Koszula Elegancka Vistula (39, white)', 1, 199.50),
(22, 9, 'Koszulka Sportowa Nike (S, black)', 1, 129.99),

-- U8 (O23) - 2x
(23, 10, 'Koszulka Sportowa Nike (M, black)', 2, 129.99),

-- U9 (O24)
(24, 5, 'Kurtka Zimowa Puchowa The North Face (L, black)', 1, 899.00),
(24, 11, 'Koszulka Sportowa Nike (L, black)', 1, 129.99),

-- U10 (O25, O26)
(25, 8, 'Koszula Elegancka Vistula (40, white)', 1, 199.50),
(26, 8, 'Koszula Elegancka Vistula (40, white)', 1, 199.50),

-- U11 (O27)
(27, 4, 'Kurtka Zimowa Puchowa The North Face (M, black)', 1, 899.00),

-- U12 (O28)
(28, 9, 'Koszulka Sportowa Nike (S, black)', 1, 129.99),

-- U13 (O29, O30)
(29, 10, 'Koszulka Sportowa Nike (M, black)', 1, 129.99),
(30, 7, 'Koszula Elegancka Vistula (39, white)', 1, 199.50),

-- U14 (O31)
(31, 5, 'Kurtka Zimowa Puchowa The North Face (L, black)', 1, 899.00),

-- U15 (O32)
(32, 8, 'Koszula Elegancka Vistula (40, white)', 1, 199.50),

-- U16 (O33, O34)
(33, 7, 'Koszula Elegancka Vistula (39, white)', 1, 199.50),
(34, 10, 'Koszulka Sportowa Nike (M, black)', 1, 129.99),

-- U17 (O35) - 3x
(35, 9, 'Koszulka Sportowa Nike (S, black)', 3, 129.99),

-- U18 (O36, O37, O38) - Dużo zamówień
(36, 4, 'Kurtka Zimowa Puchowa The North Face (M, black)', 1, 899.00),
(37, 7, 'Koszula Elegancka Vistula (39, white)', 1, 199.50),
(38, 9, 'Koszulka Sportowa Nike (S, black)', 1, 129.99),

-- U19 (O39, O40, O41, O42) - Dużo zamówień
(39, 10, 'Koszulka Sportowa Nike (M, black)', 1, 129.99),
(40, 11, 'Koszulka Sportowa Nike (L, black)', 1, 129.99),
(41, 8, 'Koszula Elegancka Vistula (40, white)', 1, 199.50),
(42, 5, 'Kurtka Zimowa Puchowa The North Face (L, black)', 1, 899.00),

-- U20 (O43, O44, O45)
(43, 7, 'Koszula Elegancka Vistula (39, white)', 1, 199.50),
(43, 9, 'Koszulka Sportowa Nike (S, black)', 1, 129.99),
(44, 4, 'Kurtka Zimowa Puchowa The North Face (M, black)', 1, 899.00),
(45, 10, 'Koszulka Sportowa Nike (M, black)', 1, 129.99),

-- U21 (O46 do O50)
(46, 9, 'Koszulka Sportowa Nike (S, black)', 1, 129.99),
(47, 10, 'Koszulka Sportowa Nike (M, black)', 1, 129.99),
(48, 11, 'Koszulka Sportowa Nike (L, black)', 1, 129.99),
(49, 7, 'Koszula Elegancka Vistula (39, white)', 1, 199.50),
(50, 8, 'Koszula Elegancka Vistula (40, white)', 1, 199.50),

-- U22 (O51) - OGROMNE ZAMÓWIENIE RÓŻNE KATEGORIE
(51, 4, 'Kurtka Zimowa Puchowa The North Face (M, black)', 1, 899.00),
(51, 12, 'Kurtka Ramoneska Zara (S, black)', 2, 249.99),
(51, 17, 'Sweter z Reniferem Smyk (104, red)', 3, 89.99),
(51, 19, 'Koszulka Oversize H&M (M, gray_melange)', 4, 59.99);

-- ==========================================
-- ZAMÓWIENIA: GRUPA 3 (18 Kobiet z wiekiem, ID 24 - 41)
-- ==========================================

INSERT INTO orders (id, user_id, status, payment_status, total_price, shipping_address, delivery_method, payment_method, created_at) VALUES 

-- ---------------------------------------------------------
-- PODGRUPA 3A: 12 użytkowniczek (1-2 zakupy, głównie odzież damska i dziecięca) 
-- ID: 24 - 35
-- ---------------------------------------------------------
-- User 24 (1 zakup)
(52, 24, 'COMPLETED', 'PAID', 249.99, '{"city": "Warszawa", "street": "Złota 44", "zip_code": "00-120"}', 'courier', 'payu', '2025-03-08 10:00:00'), -- Dzień Kobiet
-- User 25 (2 zakupy)
(53, 25, 'COMPLETED', 'PAID', 119.99, '{"city": "Kraków", "street": "Karmelicka 10", "zip_code": "31-128"}', 'paczkomat', 'blik', '2025-05-15 14:20:00'),
(54, 25, 'COMPLETED', 'PAID', 139.99, '{"city": "Kraków", "street": "Karmelicka 10", "zip_code": "31-128"}', 'paczkomat', 'blik', '2025-07-10 09:15:00'),
-- User 26 (1 zakup - 2 sztuki topu sportowego)
(55, 26, 'COMPLETED', 'PAID', 199.98, '{"city": "Gdańsk", "street": "Mariacka 5", "zip_code": "80-833"}', 'courier', 'transfer', '2025-01-20 18:30:00'),
-- User 27 (1 zakup - Ramoneska + Koszula)
(56, 27, 'COMPLETED', 'PAID', 369.98, '{"city": "Wrocław", "street": "Świdnicka 8", "zip_code": "50-067"}', 'courier', 'payu', '2025-09-28 08:00:00'),
-- User 28 (2 zakupy eleganckich koszul)
(57, 28, 'COMPLETED', 'PAID', 149.90, '{"city": "Poznań", "street": "Półwiejska 9", "zip_code": "61-886"}', 'pickup', 'cash_on_delivery', '2025-04-14 12:00:00'),
(58, 28, 'COMPLETED', 'PAID', 129.99, '{"city": "Poznań", "street": "Półwiejska 9", "zip_code": "61-886"}', 'paczkomat', 'online', '2025-11-10 16:45:00'),
-- User 29 (1 zakup)
(59, 29, 'COMPLETED', 'PAID', 119.99, '{"city": "Katowice", "street": "3 Maja 12", "zip_code": "40-096"}', 'courier', 'payu', '2025-02-05 11:10:00'),
-- User 30 (1 zakup - zimowa kurtka)
(60, 30, 'SHIPPED', 'PAID', 249.99, '{"city": "Łódź", "street": "Piotrkowska 45", "zip_code": "90-001"}', 'paczkomat', 'blik', '2025-10-20 19:00:00'),
-- User 31 (2 zakupy - głównie ubrania sportowe i dziecięce)
(61, 31, 'COMPLETED', 'PAID', 99.99, '{"city": "Szczecin", "street": "Wielka 15", "zip_code": "70-001"}', 'paczkomat', 'blik', '2025-06-15 13:30:00'),
(62, 31, 'COMPLETED', 'PAID', 49.99, '{"city": "Szczecin", "street": "Wielka 15", "zip_code": "70-001"}', 'courier', 'online', '2025-11-02 10:20:00'),
-- User 32 (1 zakup - koszula jeansowa)
(63, 32, 'COMPLETED', 'PAID', 159.99, '{"city": "Bydgoszcz", "street": "Gdańska 33", "zip_code": "85-005"}', 'courier', 'transfer', '2025-08-15 15:00:00'),
-- User 33 (1 zakup - tylko ubranko dla dziecka)
(64, 33, 'COMPLETED', 'PAID', 89.99, '{"city": "Lublin", "street": "Lipowa 1", "zip_code": "20-020"}', 'pickup', 'cash_on_delivery', '2025-12-05 09:45:00'),
-- User 34 (2 zakupy - koszula i koszulka unisex)
(65, 34, 'COMPLETED', 'PAID', 119.99, '{"city": "Białystok", "street": "Suraska 5", "zip_code": "15-422"}', 'courier', 'payu', '2025-03-12 11:11:00'),
(66, 34, 'PROCESSING', 'PAID', 59.99, '{"city": "Białystok", "street": "Suraska 5", "zip_code": "15-422"}', 'paczkomat', 'blik', '2025-06-25 18:20:00'),
-- User 35 (1 zakup - 2 zwiewne koszule Boho na lato)
(67, 35, 'COMPLETED', 'PAID', 279.98, '{"city": "Rzeszów", "street": "3 Maja 10", "zip_code": "35-030"}', 'courier', 'online', '2025-05-30 14:00:00'),

-- ---------------------------------------------------------
-- PODGRUPA 3B: 4 użytkowniczki (Dużo zakupów, np. 3-5 zamówień)
-- ID: 36 - 39
-- ---------------------------------------------------------
-- User 36 (3 zamówienia)
(68, 36, 'COMPLETED', 'PAID', 249.99, '{"city": "Warszawa", "street": "Nowy Świat 5", "zip_code": "00-029"}', 'courier', 'payu', '2024-11-25 10:00:00'),
(69, 36, 'COMPLETED', 'PAID', 99.99, '{"city": "Warszawa", "street": "Nowy Świat 5", "zip_code": "00-029"}', 'paczkomat', 'blik', '2025-01-10 09:00:00'),
(70, 36, 'COMPLETED', 'PAID', 149.90, '{"city": "Warszawa", "street": "Nowy Świat 5", "zip_code": "00-029"}', 'paczkomat', 'blik', '2025-04-22 15:30:00'),
-- User 37 (4 zamówienia koszul z różnych sklepów)
(71, 37, 'COMPLETED', 'PAID', 119.99, '{"city": "Kraków", "street": "Szewska 7", "zip_code": "31-009"}', 'pickup', 'cash_on_delivery', '2025-02-05 12:00:00'),
(72, 37, 'COMPLETED', 'PAID', 119.99, '{"city": "Kraków", "street": "Szewska 7", "zip_code": "31-009"}', 'paczkomat', 'blik', '2025-05-15 16:45:00'),
(73, 37, 'COMPLETED', 'PAID', 129.99, '{"city": "Kraków", "street": "Szewska 7", "zip_code": "31-009"}', 'courier', 'online', '2025-08-01 10:15:00'),
(74, 37, 'SHIPPED', 'PAID', 119.99, '{"city": "Kraków", "street": "Szewska 7", "zip_code": "31-009"}', 'courier', 'payu', '2025-10-30 08:30:00'),
-- User 38 (3 zamówienia - ubrała się na zimę wraz z dzieckiem)
(75, 38, 'COMPLETED', 'PAID', 249.99, '{"city": "Wrocław", "street": "Krupnicza 2", "zip_code": "50-075"}', 'courier', 'transfer', '2025-09-28 14:20:00'),
(76, 38, 'COMPLETED', 'PAID', 139.98, '{"city": "Wrocław", "street": "Krupnicza 2", "zip_code": "50-075"}', 'courier', 'payu', '2025-11-15 11:10:00'), -- Sweter dziecięcy + koszulka dziecięca
(77, 38, 'NEW', 'UNPAID', 139.99, '{"city": "Wrocław", "street": "Krupnicza 2", "zip_code": "50-075"}', 'paczkomat', 'blik', '2025-12-28 19:00:00'),
-- User 39 (5 małych zamówień w ciągu roku)
(78, 39, 'COMPLETED', 'PAID', 99.99, '{"city": "Poznań", "street": "Stary Rynek 1", "zip_code": "61-772"}', 'paczkomat', 'blik', '2025-01-20 09:00:00'),
(79, 39, 'COMPLETED', 'PAID', 59.99, '{"city": "Poznań", "street": "Stary Rynek 1", "zip_code": "61-772"}', 'paczkomat', 'blik', '2025-03-25 10:00:00'),
(80, 39, 'COMPLETED', 'PAID', 159.99, '{"city": "Poznań", "street": "Stary Rynek 1", "zip_code": "61-772"}', 'paczkomat', 'blik', '2025-06-10 12:30:00'),
(81, 39, 'COMPLETED', 'PAID', 119.99, '{"city": "Poznań", "street": "Stary Rynek 1", "zip_code": "61-772"}', 'courier', 'online', '2025-09-05 14:15:00'),
(82, 39, 'COMPLETED', 'PAID', 119.99, '{"city": "Poznań", "street": "Stary Rynek 1", "zip_code": "61-772"}', 'pickup', 'cash_on_delivery', '2025-12-12 16:45:00'),

-- ---------------------------------------------------------
-- PODGRUPA 3C: 1 użytkowniczka (1 ogromne zamówienie)
-- ID: 40
-- Ramoneska (249.99) + Lniana Zara (149.90) + Boho Mango (139.99) 
-- + 2x Sweter dziecko (2x89.99) + 2x Koszulka dziecko (2x49.99) + Unisex (59.99)
-- Razem: 249.99 + 149.90 + 139.99 + 179.98 + 99.98 + 59.99 = 879.83
-- ---------------------------------------------------------
(83, 40, 'COMPLETED', 'PAID', 879.83, '{"city": "Sopot", "street": "Bohaterów Monte Cassino 50", "zip_code": "81-704"}', 'courier', 'payu', '2025-11-20 18:00:00');

-- ---------------------------------------------------------
-- PODGRUPA 3D: 1 użytkowniczka (Brak zamówień)
-- ID: 41 (Celowo pominięta w tabeli orders)
-- ---------------------------------------------------------


-- ==========================================
-- POZYCJE ZAMÓWIEŃ DLA GRUPY 3
-- ==========================================

INSERT INTO order_items (order_id, variant_id, variant_name, quantity, unit_price) VALUES 

-- U24 (O52)
(52, 12, 'Kurtka Ramoneska Zara (S, black)', 1, 249.99),

-- U25 (O53, O54)
(53, 14, 'Koszula Wiskozowa Reserved (M, light_blue)', 1, 119.99),
(54, 28, 'Koszula w Kwiaty Boho Mango (M, pink)', 1, 139.99),

-- U26 (O55) - 2x Top Puma
(55, 16, 'Top Treningowy Puma (S, pink)', 2, 99.99),

-- U27 (O56)
(56, 13, 'Kurtka Ramoneska Zara (M, black)', 1, 249.99),
(56, 15, 'Koszula Wiskozowa Reserved (L, light_blue)', 1, 119.99),

-- U28 (O57, O58)
(57, 26, 'Koszula Lniana Premium Zara (M, white)', 1, 149.90),
(58, 27, 'Koszula Satynowa H&M (S, black)', 1, 129.99),

-- U29 (O59)
(59, 30, 'Elegancka Koszula z Bufkami Mohito (S, white)', 1, 119.99),

-- U30 (O60)
(60, 12, 'Kurtka Ramoneska Zara (S, black)', 1, 249.99),

-- U31 (O61, O62)
(61, 16, 'Top Treningowy Puma (S, pink)', 1, 99.99),
(62, 18, 'T-Shirt T-Rex Coccodrillo (110, bottle_green)', 1, 49.99),

-- U32 (O63)
(63, 29, 'Koszula Jeansowa Vintage Reserved (S, light_blue)', 1, 159.99),

-- U33 (O64)
(64, 17, 'Sweter z Reniferem Smyk (104, red)', 1, 89.99),

-- U34 (O65, O66)
(65, 14, 'Koszula Wiskozowa Reserved (M, light_blue)', 1, 119.99),
(66, 19, 'Koszulka Oversize H&M (M, gray_melange)', 1, 59.99),

-- U35 (O67) - 2x Boho
(67, 28, 'Koszula w Kwiaty Boho Mango (M, pink)', 2, 139.99),

-- U36 (O68, O69, O70)
(68, 12, 'Kurtka Ramoneska Zara (S, black)', 1, 249.99),
(69, 16, 'Top Treningowy Puma (S, pink)', 1, 99.99),
(70, 26, 'Koszula Lniana Premium Zara (M, white)', 1, 149.90),

-- U37 (O71, O72, O73, O74)
(71, 14, 'Koszula Wiskozowa Reserved (M, light_blue)', 1, 119.99),
(72, 15, 'Koszula Wiskozowa Reserved (L, light_blue)', 1, 119.99),
(73, 27, 'Koszula Satynowa H&M (S, black)', 1, 129.99),
(74, 30, 'Elegancka Koszula z Bufkami Mohito (S, white)', 1, 119.99),

-- U38 (O75, O76, O77)
(75, 13, 'Kurtka Ramoneska Zara (M, black)', 1, 249.99),
(76, 17, 'Sweter z Reniferem Smyk (104, red)', 1, 89.99),
(76, 18, 'T-Shirt T-Rex Coccodrillo (110, bottle_green)', 1, 49.99),
(77, 28, 'Koszula w Kwiaty Boho Mango (M, pink)', 1, 139.99),

-- U39 (O78, O79, O80, O81, O82)
(78, 16, 'Top Treningowy Puma (S, pink)', 1, 99.99),
(79, 19, 'Koszulka Oversize H&M (M, gray_melange)', 1, 59.99),
(80, 29, 'Koszula Jeansowa Vintage Reserved (S, light_blue)', 1, 159.99),
(81, 30, 'Elegancka Koszula z Bufkami Mohito (S, white)', 1, 119.99),
(82, 14, 'Koszula Wiskozowa Reserved (M, light_blue)', 1, 119.99),

-- U40 (O83) - OGROMNE ZAMÓWIENIE
(83, 12, 'Kurtka Ramoneska Zara (S, black)', 1, 249.99),
(83, 26, 'Koszula Lniana Premium Zara (M, white)', 1, 149.90),
(83, 28, 'Koszula w Kwiaty Boho Mango (M, pink)', 1, 139.99),
(83, 17, 'Sweter z Reniferem Smyk (104, red)', 2, 89.99),
(83, 18, 'T-Shirt T-Rex Coccodrillo (110, bottle_green)', 2, 49.99),
(83, 19, 'Koszulka Oversize H&M (M, gray_melange)', 1, 59.99);

-- ==========================================
-- ZAMÓWIENIA: GRUPA 4 (4 osoby 'OTHER' z wiekiem, ID 42 - 45)
-- ==========================================

INSERT INTO orders (id, user_id, status, payment_status, total_price, shipping_address, delivery_method, payment_method, created_at) VALUES 

-- ---------------------------------------------------------
-- PODGRUPA 4A: 1 użytkownik (Regularny - 5 zamówień) 
-- ID: 42
-- ---------------------------------------------------------
(84, 42, 'COMPLETED', 'PAID', 59.99, '{"city": "Warszawa", "street": "Marszałkowska 100", "zip_code": "00-024"}', 'paczkomat', 'blik', '2025-01-12 10:30:00'),
(85, 42, 'COMPLETED', 'PAID', 899.00, '{"city": "Warszawa", "street": "Marszałkowska 100", "zip_code": "00-024"}', 'courier', 'payu', '2025-04-18 14:15:00'),
(86, 42, 'COMPLETED', 'PAID', 99.99, '{"city": "Warszawa", "street": "Marszałkowska 100", "zip_code": "00-024"}', 'paczkomat', 'blik', '2025-07-22 09:00:00'),
(87, 42, 'COMPLETED', 'PAID', 239.98, '{"city": "Warszawa", "street": "Marszałkowska 100", "zip_code": "00-024"}', 'courier', 'online', '2025-11-28 08:30:00'), -- Black Friday
(88, 42, 'PROCESSING', 'PAID', 129.99, '{"city": "Warszawa", "street": "Marszałkowska 100", "zip_code": "00-024"}', 'paczkomat', 'blik', '2025-12-10 18:45:00'),

-- ---------------------------------------------------------
-- PODGRUPA 4B: 2 użytkowników (Okazjonalni - 1-2 zamówienia) 
-- ID: 43, 44
-- ---------------------------------------------------------
-- User 43 (2 zamówienia - ramoneska damska, koszula męska i koszulka unisex)
(89, 43, 'COMPLETED', 'PAID', 309.98, '{"city": "Kraków", "street": "Krupnicza 15", "zip_code": "31-123"}', 'courier', 'transfer', '2025-05-10 11:20:00'),
(90, 43, 'SHIPPED', 'PAID', 199.50, '{"city": "Kraków", "street": "Krupnicza 15", "zip_code": "31-123"}', 'paczkomat', 'blik', '2025-10-12 16:10:00'),

-- User 44 (1 zamówienie - odzież Unisex)
(91, 44, 'COMPLETED', 'PAID', 119.98, '{"city": "Gdynia", "street": "Świętojańska 10", "zip_code": "81-368"}', 'pickup', 'cash_on_delivery', '2025-07-07 12:00:00');

-- ---------------------------------------------------------
-- PODGRUPA 4C: 1 użytkownik (Brak zamówień)
-- ID: 45 (Celowo pominięty)
-- ---------------------------------------------------------


-- ==========================================
-- POZYCJE ZAMÓWIEŃ DLA GRUPY 4
-- ==========================================

INSERT INTO order_items (order_id, variant_id, variant_name, quantity, unit_price) VALUES 

-- U42 (O84 - O88)
(84, 19, 'Koszulka Oversize H&M (M, gray_melange)', 1, 59.99),
(85, 4, 'Kurtka Zimowa Puchowa The North Face (M, black)', 1, 899.00),
(86, 16, 'Top Treningowy Puma (S, pink)', 1, 99.99),
(87, 14, 'Koszula Wiskozowa Reserved (M, light_blue)', 2, 119.99),
(88, 10, 'Koszulka Sportowa Nike (M, black)', 1, 129.99),

-- U43 (O89 - O90)
(89, 13, 'Kurtka Ramoneska Zara (M, black)', 1, 249.99),
(89, 20, 'Koszulka Oversize H&M (L, gray_melange)', 1, 59.99),
(90, 8, 'Koszula Elegancka Vistula (40, white)', 1, 199.50),

-- U44 (O91)
(91, 19, 'Koszulka Oversize H&M (M, gray_melange)', 1, 59.99),
(91, 20, 'Koszulka Oversize H&M (L, gray_melange)', 1, 59.99);

-- ==========================================
-- ZAMÓWIENIA: GRUPA 5 (5 osób z wiekiem, BEZ podanej płci, ID 46 - 50)
-- ==========================================

INSERT INTO orders (id, user_id, status, payment_status, total_price, shipping_address, delivery_method, payment_method, created_at) VALUES 

-- ---------------------------------------------------------
-- PODGRUPA 5A: 1 użytkownik (Regularny - 5 zamówień) 
-- ID: 46
-- ---------------------------------------------------------
(92, 46, 'COMPLETED', 'PAID', 149.99, '{"city": "Lublin", "street": "Królewska 10", "zip_code": "20-109"}', 'paczkomat', 'blik', '2025-02-18 10:30:00'),
(93, 46, 'COMPLETED', 'PAID', 159.99, '{"city": "Lublin", "street": "Królewska 10", "zip_code": "20-109"}', 'courier', 'payu', '2025-05-20 14:15:00'),
(94, 46, 'COMPLETED', 'PAID', 159.99, '{"city": "Lublin", "street": "Królewska 10", "zip_code": "20-109"}', 'pickup', 'cash_on_delivery', '2025-08-05 09:00:00'),
(95, 46, 'COMPLETED', 'PAID', 209.98, '{"city": "Lublin", "street": "Królewska 10", "zip_code": "20-109"}', 'courier', 'online', '2025-11-28 11:30:00'), -- Black Friday
(96, 46, 'PROCESSING', 'PAID', 99.99, '{"city": "Lublin", "street": "Królewska 10", "zip_code": "20-109"}', 'paczkomat', 'blik', '2025-12-18 18:45:00'),

-- ---------------------------------------------------------
-- PODGRUPA 5B: 3 użytkowników (Okazjonalni - 1-2 zamówienia) 
-- ID: 47, 48, 49
-- ---------------------------------------------------------
-- User 47 (2 zamówienia - Koszula satynowa, a potem ubranka dla dziecka)
(97, 47, 'COMPLETED', 'PAID', 129.99, '{"city": "Białystok", "street": "Warszawska 50", "zip_code": "15-062"}', 'courier', 'transfer', '2025-04-10 11:20:00'),
(98, 47, 'COMPLETED', 'PAID', 139.98, '{"city": "Białystok", "street": "Warszawska 50", "zip_code": "15-062"}', 'paczkomat', 'blik', '2025-09-12 16:10:00'),

-- User 48 (1 zamówienie - Ramoneska i Lniana koszula Zara)
(99, 48, 'COMPLETED', 'PAID', 399.89, '{"city": "Katowice", "street": "Chorzowska 100", "zip_code": "40-101"}', 'courier', 'payu', '2025-07-07 12:00:00'),

-- User 49 (1 zamówienie - Największa kurtka The North Face XL z modyfikatorem ceny)
(100, 49, 'SHIPPED', 'PAID', 949.00, '{"city": "Kielce", "street": "Sienkiewicza 15", "zip_code": "25-007"}', 'courier', 'online', '2025-11-15 08:00:00');

-- ---------------------------------------------------------
-- PODGRUPA 5C: 1 użytkownik (Brak zamówień)
-- ID: 50 (Celowo pominięty)
-- ---------------------------------------------------------


-- ==========================================
-- POZYCJE ZAMÓWIEŃ DLA GRUPY 5
-- ==========================================

INSERT INTO order_items (order_id, variant_id, variant_name, quantity, unit_price) VALUES 

-- U46 (O92 - O96)
(92, 24, 'Koszulka Termoaktywna Rush Under Armour (M, red)', 1, 149.99),
(93, 25, 'T-Shirt Premium Oversize Nike (L, beige)', 1, 159.99),
(94, 29, 'Koszula Jeansowa Vintage Reserved (S, light_blue)', 1, 159.99),
(95, 21, 'Koszulka Treningowa Tiro Adidas (M, navy_blue)', 1, 119.99),
(95, 23, 'Koszulka Compression Reebok (M, black)', 1, 89.99),
(96, 22, 'T-Shirt Classics Logo Puma (M, white)', 1, 99.99),

-- U47 (O97 - O98)
(97, 27, 'Koszula Satynowa H&M (S, black)', 1, 129.99),
(98, 18, 'T-Shirt T-Rex Coccodrillo (110, bottle_green)', 1, 49.99),
(98, 17, 'Sweter z Reniferem Smyk (104, red)', 1, 89.99),

-- U48 (O99)
(99, 13, 'Kurtka Ramoneska Zara (M, black)', 1, 249.99),
(99, 26, 'Koszula Lniana Premium Zara (M, white)', 1, 149.90),

-- U49 (O100)
(100, 6, 'Kurtka Zimowa Puchowa The North Face (XL, black)', 1, 949.00);

-- ==========================================
-- AKTUALIZACJA SEKWENCJI
-- ==========================================
SELECT setval('orders_id_seq', (SELECT MAX(id) FROM orders));
SELECT setval('order_items_id_seq', (SELECT MAX(id) FROM order_items));


