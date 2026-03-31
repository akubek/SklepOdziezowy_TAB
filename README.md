# Projekt sklepu z odzieżą.

Projekt na przedmiot Tworzenie Aplikacji Bazodanowych dla kierunku Informatyka na Politechnice Śląskiej.

## Technologie
* **Backend:** PHP 8.x
* **Baza danych:** PostgreSQL
* **Frontend:** HTML, CSS, Vanilla JS

## Uruchamianie lokalnie:

1. Skopiuj plik `config/database.example.php`, zmień jego nazwę na `database.php` i wpisz w nim swoje hasło do PostgreSQL.
2. Otwórz pgAdmin lub DBeaver i utwórz pustą bazę danych.
3. Wykonaj skrypt `database/init.sql`, aby utworzyć strukturę tabel (uwaga: skrypt czyści bazę, jeśli już istniała!).
4. Wykonaj skrypt `database/seed.sql`, aby wypełnić bazę testowymi kategoriami i produktami.
5. Uruchom serwer klikając w plik `start.bat` (lub ręcznie: `php -S localhost:8000 -t public`).

## Autorzy
* Maciej Guja
* Augustin Jakub
* Artur Kubek
* Antoni Wójtowicz
