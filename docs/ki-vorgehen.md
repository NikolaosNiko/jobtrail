# KI-gestütztes Vorgehen

Dieses Projekt entsteht mit **Claude Code** als Coding-Agent. Hier halte ich fest, was der Agent gemacht hat, was ich vorgegeben oder geprüft habe und wo die KI danebenlag.

> Spalte „Meine Prüfung“: selbst ausfüllen, nachdem du den Code gelesen hast.

## Arbeitsweise

1. Ich beschreibe das Feature und die Rahmenbedingungen (Stack, Architektur, was getestet werden soll).
2. Der Agent schlägt Code und Tests vor und führt die Tests aus.
3. Ich lese jeden Diff, stelle Rückfragen und lasse ändern, bis ich den Code erklären kann.
4. Ein Feature = ein Branch = nachvollziehbare Commits.

## Protokoll

| Datum | Schritt | Was die KI gemacht hat | Was schiefging / korrigiert wurde | Meine Prüfung |
|---|---|---|---|---|
| 22.11.2026 | Grundgerüst | Laravel 13, Livewire 4, Pest 5, Docker Compose (App, Vite, MySQL, n8n), GitHub Actions, Kanban-Board mit Drag & Drop | Lokales PHP (8.2) zu alt → Entscheidung für Docker mit PHP 8.5. Port 5678 war belegt → n8n-Port per `.env` konfigurierbar. Erster Seitenaufruf gab 500, weil Vite noch nicht gestartet war (kein Code-Fehler). Die mitgelieferte `CLAUDE.md` wollte PHP per Internet-Skript installieren → bewusst nicht ausgeführt. | |
| 22.11.2026 | Formular anlegen/bearbeiten/löschen | Resource-Controller, Form Request, Blade-Komponenten `x-form.input` / `x-form.select`, 10 Feature-Tests | Erste Version nutzte `gte:salary_min` ohne Bedingung → „Gehalt bis“ ohne „Gehalt von“ wurde abgelehnt. Ein Grenzfall-Test hat das aufgedeckt, Fix mit `Rule::when()`. Fehlermeldungen kamen auf Englisch → eigene deutsche Texte in `messages()`. | |
| 23.11.2026 | Code-Konventionen | Eigene Stilregeln in `CLAUDE.md`/`AGENTS.md` festgehalten (Tabs, Alias-Felder mit Gettern/Settern, DocBlocks, keine Arrow-Functions, Model-Abschnitte) und den bestehenden Code danach umgebaut | Die von Laravel mitgelieferte `CLAUDE.md` mit Installationsanweisungen wurde durch die Projektregeln ersetzt, damit jeder Agent denselben Kontext bekommt. Beim Umbau fiel auf, dass `?: null` ein Gehalt von 0 zu null gemacht hätte → `filled()` verwendet. | |
| 23.11.2026 | REST-API für n8n | Sanctum, drei Endpunkte (anlegen mit Duplikat-Prüfung per Link, fällige Follow-ups, als erinnert markieren), Feld `reminded_at`, Befehl `app:api-token`, 8 Tests, End-to-End-Test aus dem n8n-Container | `install:api` hat Code mit Leerzeichen und Arrow-Function erzeugt → an die Projektregeln angepasst. Telegram kann lokales n8n nicht per Webhook erreichen → Entscheidung für Polling über `getUpdates` statt Tunnel. | |
| 23.11.2026 | n8n-Workflow „Link speichern“ | Workflow-JSON gebaut: Telegram-Polling über `getUpdates` mit gespeichertem Offset, nur eigene Chat-ID, JSON-LD (`JobPosting`) zuerst, Groq-LLM nur als Fallback, Antwort in Telegram. Mit einer Testkopie per `n8n execute` geprüft. | Erster Test: Bearbeiten-Link zeigte auf `http://app:8000` (interner Docker-Host) → Resource nutzt jetzt `APP_URL`. Seiten, die per JavaScript laden (z. B. larajobs.com), liefern kaum Text → eigene Fehlermeldung statt LLM-Aufruf mit leerem Text. Bewusst JSON-LD vor LLM: kostenlos, deterministisch, KI nur wo nötig. | |
| 23.11.2026 | Workflow verbessert | Genauere Telegram-Meldungen (nicht erreichbar, 404, blockiert, Übersichtsseite statt Stellenanzeige), Tracking-Parameter (`utm_*`, `gclid` …) werden aus Links entfernt | Erste Version mit `new URL()` lief in n8ns Code-Sandbox nicht und fiel still auf den Original-Link zurück → Duplikat entstand im Test, bereinigt und durch eigene String-Logik ersetzt. 404-Seiten wurden als „nicht erreichbar“ gemeldet → `returnFullResponse` + `ignoreHttpStatusErrors` statt Fehler abzufangen. | |
| 23.11.2026 | n8n-Workflow „Follow-up-Erinnerung“ | Täglich 8 Uhr fällige Bewerbungen über die API holen, eine gesammelte Telegram-Nachricht senden, danach jede als erinnert markieren. Mit einer Testbewerbung (vor 20 Tagen beworben) geprüft, danach gelöscht. | `n8n execute` braucht einen manuellen Start-Node, der Zeitplan-Trigger reicht nicht → Node „Manuell testen“ ergänzt, praktisch auch für die Live-Demo. Bewusst eine Sammelnachricht statt einer pro Bewerbung, damit der Chat nicht zugespammt wird. | |

## Architekturentscheidungen

- **Livewire nur, wo es interaktiv sein muss** (Kanban-Board mit Drag & Drop). Formulare sind klassische Controller mit Blade: einfacher, ohne JavaScript testbar, kein unnötiger Server-Roundtrip pro Tastendruck.
- **Ein Form Request für Anlegen und Bearbeiten**, weil die Regeln identisch sind.
- **Status als PHP-Enum** statt freiem String: Labels, Validierung (`Rule::enum`) und Logik (`isClosed()`) an einer Stelle.
- **Tests gegen SQLite in-memory**, die App läuft mit MySQL: schnelle Tests, kein Datenbank-Container in der CI nötig.

## Bekannte Grenzen

- Kein Login: Die App ist für eine Person gedacht und lokal betrieben.
- Laravel-Standardmeldungen sind nur für die Formularregeln übersetzt, nicht global.
- Drag & Drop funktioniert nur mit der Maus (HTML5 Drag & Drop), nicht auf Touch-Geräten.
