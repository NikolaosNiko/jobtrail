# n8n-Workflows

Exportierte n8n-Workflows (JSON) kommen in diesen Ordner. Im Container liegt er unter `/workflows`.

## Verbindung zu Laravel

- Basis-URL aus n8n heraus: `http://app:8000/api`
- Token erzeugen: `docker compose exec app php artisan app:api-token` → in n8n als Credential „Header Auth“ hinterlegen (`Authorization: Bearer <token>`)
- Immer den Header `Accept: application/json` mitschicken.

| Methode | Pfad | Zweck |
|---|---|---|
| POST | `/applications` | Stelle als „Gemerkt“ anlegen (`company`, `position`, `url` Pflicht). Bekannter Link → 200 mit bestehender Bewerbung, neu → 201 |
| GET | `/applications/follow-ups` | Bewerbungen seit 14 Tagen auf „Beworben“, noch nicht erinnert |
| POST | `/applications/{id}/reminded` | Bewerbung als erinnert markieren |

## Workflows

- **Link speichern** (`link-speichern.json`): jede Minute Telegram `getUpdates` → nur Nachrichten aus `TELEGRAM_CHAT_ID` mit Link → Seite laden → JSON-LD (`JobPosting`) auslesen, sonst Groq-LLM → `POST /applications` → Antwort in Telegram.
- **Follow-up-Erinnerung** (`follow-up-erinnerung.json`): täglich 8 Uhr → `GET /applications/follow-ups` → eine Telegram-Nachricht mit allen fälligen Bewerbungen → `POST /applications/{id}/reminded`, damit jede nur einmal erinnert wird. Gibt es nichts Fälliges, endet der Workflow ohne Nachricht. Über „Manuell testen“ lässt er sich jederzeit von Hand starten.

Export in n8n: Workflow öffnen → ⋯ → *Download*, Datei hier ablegen und committen. API-Schlüssel und Tokens nie mit exportieren.

## Einrichten

1. In `.env` eintragen: `TELEGRAM_BOT_TOKEN`, `TELEGRAM_CHAT_ID`, `JOBTRAIL_API_TOKEN` (Token mit `php artisan app:api-token` erzeugen), dann `docker compose up -d n8n`.
2. Workflows importieren: `docker compose exec n8n n8n import:workflow --input=/workflows/link-speichern.json` (ebenso `follow-up-erinnerung.json`)
3. In n8n beim Node „Groq Chat Model“ die Groq-Credential auswählen und beide Workflows aktivieren.

Hinweis: Der Bot darf keinen Webhook gesetzt haben (z. B. durch einen Telegram-Trigger in einer anderen n8n-Instanz), sonst schlägt `getUpdates` fehl.
