-- 03_seed_products.sql

-- Wstawianie produktów tylko do "liści" kategorii
INSERT INTO products (id, category_id, brand_name, name, description, base_price) VALUES 

-- === ODZIEŻ MĘSKA ===
-- 101: Kurtki (Męskie)
(1, 101, 'The North Face', 'Kurtka Zimowa Puchowa', 'Bardzo ciepła kurtka na srogą zimę, wodoodporna.', 899.00),
-- 102: Koszule (Męskie)
(2, 102, 'Vistula', 'Koszula Elegancka Slim Fit', 'Biała koszula bawełniana, idealna do garnituru.', 199.50),

-- 103: T-Shirty (Męskie) - Kategoria z wieloma produktami do testów widoku
(3, 103, 'Nike', 'Koszulka Sportowa Basic', 'Oddychająca koszulka do biegania i na siłownię.', 129.99),
(10, 103, 'Adidas', 'Koszulka Treningowa Tiro', 'Szybkoschnący materiał z technologią Aeroready.', 119.99),
(11, 103, 'Puma', 'T-Shirt Classics Logo', 'Klasyczna bawełniana koszulka z dużym logo na piersi.', 99.99),
(12, 103, 'Reebok', 'Koszulka Compression', 'Dopasowana koszulka, idealna pod inne warstwy ubrań.', 89.99),
(13, 103, 'Under Armour', 'Koszulka Termoaktywna Rush', 'Oddaje energię do mięśni, poprawia wydolność.', 149.99),
(14, 103, 'Nike', 'T-Shirt Premium Oversize', 'Bardzo gruby, mięsisty materiał, krój oversize.', 159.99),


-- === ODZIEŻ DAMSKA ===
-- 201: Kurtki (Damskie)
(4, 201, 'Zara', 'Kurtka Ramoneska Skórzana', 'Klasyczna czarna ramoneska z ekoskóry z paskiem.', 249.99),

-- 202: Koszule (Damskie) - Kategoria z wieloma produktami do testów widoku
(5, 202, 'Reserved', 'Koszula Wiskozowa', 'Lekka i zwiewna koszula damska na co dzień, kolor błękitny.', 119.99),
(15, 202, 'Zara', 'Koszula Lniana Premium', 'W 100% z naturalnego lnu, bardzo przewiewna, na upalne dni.', 149.90),
(16, 202, 'H&M', 'Koszula Satynowa', 'Elegancka koszula z delikatnym połyskiem, lejący materiał.', 129.99),
(17, 202, 'Mango', 'Koszula w Kwiaty Boho', 'Wiosenny, kwiatowy wzór, luźny krój z falbankami.', 139.99),
(18, 202, 'Reserved', 'Koszula Jeansowa Vintage', 'Klasyczna katana koszulowa z niebieskiego denimu.', 159.99),
(19, 202, 'Mohito', 'Elegancka Koszula z Bufkami', 'Ozdobne rękawy, dopasowana w talii, kolor perłowa biel.', 119.99),

-- 203: T-Shirty (Damskie)
(6, 203, 'Puma', 'Top Treningowy', 'Damska koszulka bez rękawów z przewiewnej siateczki.', 99.99),


-- === ODZIEŻ DZIECIĘCA ===
-- 301: Swetry (Dziecięce)
(7, 301, 'Smyk', 'Sweter z Reniferem', 'Ciepły świąteczny sweterek dla chłopca lub dziewczynki.', 89.99),
-- 302: Koszulki (Dziecięce)
(8, 302, 'Coccodrillo', 'T-Shirt T-Rex', 'Bawełniana koszulka z nadrukiem dinozaura.', 49.99),

-- === ODZIEŻ UNISEX ===
-- 401: Koszulki (Unisex)
(9, 401, 'H&M', 'Koszulka Oversize', 'Gładka, bawełniana koszulka o bardzo luźnym kroju.', 59.99),

-- === PRODUKTY TESTOWE (EDGE CASES) ===
(100, 402, 'TEST_BRAND', 'TEST_NO_STOCK', 'Ten produkt służy do testowania braku dostępności na magazynie. Nie powinien dać się dodać do koszyka.', 0.00),
(101, 402, 'LayoutFix', 'TEST_LAYOUT', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 9.99),
(102, 402, 'Luxury Gold', 'Złoty Zegarek Kolekcjonerski', 'Produkt o bardzo wysokiej cenie, aby sprawdzić formatowanie waluty i dużych liczb w widoku.', 99999.99);

-- Aktualizacja sekwencji dla tabeli products
SELECT setval('products_id_seq', (SELECT MAX(id) FROM products));
