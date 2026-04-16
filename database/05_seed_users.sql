-- 05_seed_users.sql

-- Hasło dla WSZYSTKICH poniższych kont to: password

INSERT INTO users (id, email, password_hash, first_name, last_name, role) VALUES 
(1, 'manager@sklep.pl', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Anna', 'Zarządzająca', 'MANAGER'),
(2, 'pracownik@sklep.pl', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Jan', 'Pracowity', 'EMPLOYEE'),
(3, 'klient@sklep.pl', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Piotr', 'Kupujący', 'CLIENT');

-- Aktualizacja sekwencji
SELECT setval('users_id_seq', (SELECT MAX(id) FROM users));