## Środowisko deweloperskie WapuuGo oparte na Dockerze

## Struktura projektu

* **`docker-compose.yml`** – konfiguracja kontenerów (WordPress, Baza MariaDB, Auto-instalator).
* **`plugins/wapuugo-core/`** – **Tu pracujemy!** Jest to folder z naszą wtyczką do obsługi mapy. Wszystkie zmiany w tym miejscu są widoczne na stronie w czasie rzeczywistym.

Jeśli byśmy chcieli pisać inne wtyczki to robimy nowy folder w plugins

---

## Jak uruchomić środowisko?

1.  **Pobierz repozytorium:**

2.  **Uruchom kontenery:**
    ```bash
    docker compose up -d
    ```

3.  **Sprawdź logi i poczekaj na konfigurację:**
    Instalacja WordPressa dzieje się automatycznie. Uruchom poniższą komendę i poczekaj, aż zobaczysz napis `GOTOWE`:
    ```bash
    docker compose logs -f wp-cli
    ```

4.  **Zaloguj się do panelu:**
    * **Adres:** [http://localhost:8080/wp-admin](http://localhost:8080/wp-admin)
    * **Login:** `admin`
    * **Hasło:** `admin123`

⚠️ Powyższe dane logowania są przeznaczone **wyłącznie** do lokalnego środowiska (`localhost`). Zostały one zahardkodowane w celu pełnej automatyzacji.
**Nie istnieje konto admina z takimi danymi na naszej faktycznej stronie!!**

---

## Jak wdrażać zmiany na naszą stronę?

Gdy skończymy pracę nad wtyczką w środowisku lokalnym, na serwer docelowy przenosimy **tylko** zawartość folderu wtyczki.

1. Skompresuj folder `plugins/wapuugo-core` do formatu `.zip`.
2. W panelu WordPressa na produkcji wybierz: **Wtyczki -> Dodaj nową -> Wyślij wtyczkę na serwer**.
