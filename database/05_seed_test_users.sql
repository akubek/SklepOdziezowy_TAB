INSERT INTO users (email, password_hash, first_name, last_name, birth_date, gender, role) VALUES 

-- ==========================================
-- GRUPA 1: 5 klientów BEZ podanego wieku (NULL)
-- ==========================================
('nobirth1@test.pl', 'dummy_hash', 'Jan', 'Kowalski', NULL, 'MALE', 'CLIENT'),
('nobirth2@test.pl', 'dummy_hash', 'Anna', 'Nowak', NULL, 'FEMALE', 'CLIENT'),
('nobirth3@test.pl', 'dummy_hash', 'Piotr', 'Wiśniewski', NULL, 'MALE', 'CLIENT'),
('nobirth4@test.pl', 'dummy_hash', 'Katarzyna', 'Wójcik', NULL, 'FEMALE', 'CLIENT'),
('nobirth5@test.pl', 'dummy_hash', 'Alex', 'Kowalczyk', NULL, 'OTHER', 'CLIENT'),

-- ==========================================
-- GRUPA 2: 18 Mężczyzn z podanym wiekiem
-- ==========================================
('m1@test.pl', 'dummy_hash', 'Adam', 'Kamiński', '1980-05-14', 'MALE', 'CLIENT'),
('m2@test.pl', 'dummy_hash', 'Michał', 'Lewandowski', '1992-11-22', 'MALE', 'CLIENT'),
('m3@test.pl', 'dummy_hash', 'Tomasz', 'Zieliński', '1975-02-10', 'MALE', 'CLIENT'),
('m4@test.pl', 'dummy_hash', 'Krzysztof', 'Szymański', '1968-07-30', 'MALE', 'CLIENT'),
('m5@test.pl', 'dummy_hash', 'Marcin', 'Woźniak', '2001-01-15', 'MALE', 'CLIENT'),
('m6@test.pl', 'dummy_hash', 'Paweł', 'Dąbrowski', '1985-09-09', 'MALE', 'CLIENT'),
('m7@test.pl', 'dummy_hash', 'Marek', 'Kozłowski', '1990-12-01', 'MALE', 'CLIENT'),
('m8@test.pl', 'dummy_hash', 'Łukasz', 'Jankowski', '1998-04-18', 'MALE', 'CLIENT'),
('m9@test.pl', 'dummy_hash', 'Mateusz', 'Mazur', '2003-08-25', 'MALE', 'CLIENT'),
('m10@test.pl', 'dummy_hash', 'Maciej', 'Kwiatkowski', '1979-10-10', 'MALE', 'CLIENT'),
('m11@test.pl', 'dummy_hash', 'Kamil', 'Krawczyk', '1995-03-12', 'MALE', 'CLIENT'),
('m12@test.pl', 'dummy_hash', 'Robert', 'Kaczmarek', '1982-06-06', 'MALE', 'CLIENT'),
('m13@test.pl', 'dummy_hash', 'Szymon', 'Piotrowski', '2000-02-28', 'MALE', 'CLIENT'),
('m14@test.pl', 'dummy_hash', 'Dawid', 'Grabowski', '1993-07-19', 'MALE', 'CLIENT'),
('m15@test.pl', 'dummy_hash', 'Jakub', 'Zając', '2005-11-05', 'MALE', 'CLIENT'),
('m16@test.pl', 'dummy_hash', 'Artur', 'Pawłowski', '1971-01-20', 'MALE', 'CLIENT'),
('m17@test.pl', 'dummy_hash', 'Patryk', 'Michalski', '1999-12-12', 'MALE', 'CLIENT'),
('m18@test.pl', 'dummy_hash', 'Kacper', 'Nowicki', '2004-09-17', 'MALE', 'CLIENT'),

-- ==========================================
-- GRUPA 3: 18 Kobiet z podanym wiekiem
-- ==========================================
('f1@test.pl', 'dummy_hash', 'Agnieszka', 'Adamczyk', '1981-03-08', 'FEMALE', 'CLIENT'),
('f2@test.pl', 'dummy_hash', 'Magdalena', 'Dudek', '1994-08-15', 'FEMALE', 'CLIENT'),
('f3@test.pl', 'dummy_hash', 'Ewa', 'Wieczorek', '1976-12-24', 'FEMALE', 'CLIENT'),
('f4@test.pl', 'dummy_hash', 'Barbara', 'Jabłońska', '1965-04-11', 'FEMALE', 'CLIENT'),
('f5@test.pl', 'dummy_hash', 'Małgorzata', 'Król', '1988-01-05', 'FEMALE', 'CLIENT'),
('f6@test.pl', 'dummy_hash', 'Joanna', 'Majewska', '1991-05-30', 'FEMALE', 'CLIENT'),
('f7@test.pl', 'dummy_hash', 'Krystyna', 'Olszewska', '1959-10-14', 'FEMALE', 'CLIENT'),
('f8@test.pl', 'dummy_hash', 'Monika', 'Stępień', '1985-07-22', 'FEMALE', 'CLIENT'),
('f9@test.pl', 'dummy_hash', 'Paulina', 'Malinowska', '1997-02-18', 'FEMALE', 'CLIENT'),
('f10@test.pl', 'dummy_hash', 'Zofia', 'Jaworska', '1955-11-02', 'FEMALE', 'CLIENT'),
('f11@test.pl', 'dummy_hash', 'Dorota', 'Górka', '1983-09-27', 'FEMALE', 'CLIENT'),
('f12@test.pl', 'dummy_hash', 'Aleksandra', 'Sikora', '2002-06-19', 'FEMALE', 'CLIENT'),
('f13@test.pl', 'dummy_hash', 'Julia', 'Walczak', '2005-04-03', 'FEMALE', 'CLIENT'),
('f14@test.pl', 'dummy_hash', 'Maja', 'Baran', '2001-12-30', 'FEMALE', 'CLIENT'),
('f15@test.pl', 'dummy_hash', 'Karolina', 'Rutkowska', '1996-08-08', 'FEMALE', 'CLIENT'),
('f16@test.pl', 'dummy_hash', 'Natalia', 'Michalak', '1999-01-11', 'FEMALE', 'CLIENT'),
('f17@test.pl', 'dummy_hash', 'Wiktoria', 'Szewczyk', '2004-10-25', 'FEMALE', 'CLIENT'),
('f18@test.pl', 'dummy_hash', 'Zuzanna', 'Ostrowska', '2006-03-14', 'FEMALE', 'CLIENT'),

-- ==========================================
-- GRUPA 4: 4 osoby Inna ('OTHER') z podanym wiekiem
-- ==========================================
('o1@test.pl', 'dummy_hash', 'Max', 'Tomaszewski', '1993-05-12', 'OTHER', 'CLIENT'),
('o2@test.pl', 'dummy_hash', 'Sam', 'Pietrzak', '1998-11-09', 'OTHER', 'CLIENT'),
('o3@test.pl', 'dummy_hash', 'Alex', 'Zawadzki', '2000-07-07', 'OTHER', 'CLIENT'),
('o4@test.pl', 'dummy_hash', 'Charlie', 'Bąk', '1989-02-14', 'OTHER', 'CLIENT'),

-- ==========================================
-- GRUPA 5: 5 osób z podanym wiekiem, BEZ podanej płci (NULL)
-- ==========================================
('nogender1@test.pl', 'dummy_hash', 'Patryk', 'Maciejewski', '1995-10-10', NULL, 'CLIENT'),
('nogender2@test.pl', 'dummy_hash', 'Anna', 'Włodarczyk', '1982-04-04', NULL, 'CLIENT'),
('nogender3@test.pl', 'dummy_hash', 'Kamil', 'Borkowski', '2001-08-16', NULL, 'CLIENT'),
('nogender4@test.pl', 'dummy_hash', 'Ewelina', 'Czarnecka', '1977-12-05', NULL, 'CLIENT'),
('nogender5@test.pl', 'dummy_hash', 'Adrian', 'Kurek', '1990-06-20', NULL, 'CLIENT');

-- Aktualizacja sekwencji, żeby uniknąć konfliktów ID przy przyszłych rejestracjach
SELECT setval('users_id_seq', (SELECT MAX(id) FROM users));
