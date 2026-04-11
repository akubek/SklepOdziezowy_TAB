# Projekt sklepu z odzieżą.

Projekt na przedmiot Tworzenie Aplikacji Bazodanowych dla kierunku Informatyka na Politechnice Śląskiej.

## Technologie
* **Backend:** PHP 8.x
* **Baza danych:** PostgreSQL
* **Frontend:** HTML, CSS, Vanilla JS

## Uruchamianie lokalnie (Docker - Rekomendowane):

Projekt wykorzystuje konteneryzację, wymagany jest zainstalowany Docker oraz Docker Compose.

1. Sklonuj repozytorium na swój dysk.
2. Skopiuj plik `.env.example`, zmień jego nazwę na `.env` i uzupełnij zmienne, przede wszystkim hasło.
3. Otwórz terminal w folderze projektu i wpisz: `docker-compose up -d`
4. Baza danych powinna zostać automatycznie zainicjowana strukturą z pliku `database/init.sql` i testowymi danymi `database/seed.sql`. 

**Dostępne usługi:**
* Aplikacja (Sklep): [http://localhost:8000](http://localhost:8000)
* Zarządzanie bazą (Adminer): [http://localhost:8081](http://localhost:8081) *(Serwer: db, dane logowania takie jak ustawione w .env)*

Wyłączenie środowiska: `docker-compose down` 

## Autorzy
* Maciej Guja
* Augustin Jakub
* Artur Kubek
* Antoni Wójtowicz
