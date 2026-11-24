# JobTrail

![Tests](https://github.com/NikolaosNiko/jobtrail/actions/workflows/tests.yml/badge.svg)

Ein Bewerbungs-Tracker mit Kanban-Board – gebaut mit dem TALL-Stack und automatisiert mit n8n.

![Kanban-Board](docs/screenshots/board.png)

| Formular | Telegram |
|---|---|
| ![Formular](docs/screenshots/formular.png) | ![Telegram](docs/screenshots/telegram.png) |

## Features

- [x] Kanban-Board mit Drag & Drop (Livewire + Alpine.js)
- [x] Status-Workflow: Gemerkt → Beworben → Gespräch → Angebot / Absage
- [x] Bewerbungsdatum wird beim Wechsel auf „Beworben“ automatisch gesetzt
- [x] Bewerbungen anlegen, bearbeiten und löschen (Formular + Validierung)
- [x] REST-API mit Laravel Sanctum (für n8n)
- [x] n8n + Telegram: Link zur Stellenanzeige schicken → KI liest sie aus → Bewerbung wird angelegt
- [x] n8n: Erinnerung, wenn sich 14 Tage nichts tut

## Tech-Stack

| Bereich | Technologie |
|---|---|
| Backend | Laravel 13, PHP 8.5 |
| Frontend | Livewire 4, Alpine.js, Tailwind CSS 4 |
| Datenbank | MySQL 8.4 (Tests: SQLite in-memory) |
| Tests | Pest 5 |
| Automatisierung | n8n |
| Infrastruktur | Docker Compose, GitHub Actions |

## Lokal starten

Voraussetzung: Docker Desktop.

```bash
git clone https://github.com/NikolaosNiko/jobtrail.git
cd jobtrail
cp .env.example .env
docker compose up -d --build
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
```

| Dienst | URL |
|---|---|
| App | http://localhost:8000 |
| Vite (Hot Reload) | http://localhost:5173 |
| n8n | http://localhost:5678 |

## Tests

```bash
docker compose exec app ./vendor/bin/pest
```

## Architektur

Livewire kommt nur dort zum Einsatz, wo eine Seite wirklich interaktiv ist. Alles andere ist klassisches Blade mit Controllern.

- `app/Enums/ApplicationStatus.php` – Status inkl. deutscher Labels
- `app/Models/JobApplication.php` – Model mit Status-Logik (`moveTo()`)
- `app/Livewire/Board.php` – Kanban-Board (Livewire + Alpine.js)
- `app/Http/Controllers/JobApplicationController.php` + `app/Http/Requests/JobApplicationRequest.php` – Formulare und Validierung
- `resources/views/components/form/` – wiederverwendbare Formular-Komponenten
- `n8n/workflows/` – exportierte n8n-Workflows

## KI-gestützte Entwicklung

Das Projekt entsteht mit Claude Code als Coding-Agent. Vorgehen, Protokoll, Architekturentscheidungen und bekannte Grenzen stehen in [docs/ki-vorgehen.md](docs/ki-vorgehen.md).
