# JobTrail – Konventionen für Coding-Agenten

Bewerbungs-Tracker mit Laravel 13, Livewire 4, Alpine.js, MySQL, Pest. Läuft komplett in Docker:
Befehle immer mit `docker compose exec app …` ausführen (z. B. `docker compose exec app ./vendor/bin/pest`).

## Architektur

- Livewire nur für wirklich interaktive Seiten (Kanban-Board). Formulare = Controller + Blade + Form Request.
- n8n ist ein eigener Dienst und spricht nur über die REST-API mit Laravel.

## Code-Stil

1. Einrückung mit Tabs, nicht mit Leerzeichen (Ausnahme: YAML).
2. Jedes Model hat ein Alias-Array `public static array $fields = ['alias' => 'db_spalte']`.
3. Zugriff auf Attribute nur über Getter und Setter, die über die Aliase gehen (`getCompanyName()`, `setCompanyName()`), nicht über `$model->spalte`.
4. Models sind in Abschnitte gegliedert, in dieser Reihenfolge: `Custom Functions`, `Relations`, `Scopes`, `Get und Set`.
5. Jede Methode hat einen DocBlock: kurzer Satz aus wenigen Wörtern, `@param` und `@return`.
6. Beziehungen über Eloquent-Relations abbilden.
7. Vergleiche mit `==` statt `===`.
8. Datensätze anlegen und ändern mit `$model = new Model();`, Setter aufrufen, `$model->save();` – kein `Model::create()` / `update()` mit Arrays.
9. Keine Arrow-Functions (`fn () =>`), Closures immer ausschreiben (`function () { return …; }`).
10. Bei Methodenketten jeder `->`-Aufruf in einer eigenen Zeile.
11. Keine erklärenden Kommentare im Code. Nur DocBlocks und die Abschnitts-Überschriften in Models.
12. Variablen und Parameter in snake_case (`$salary_min`, `$job_application`). Methoden bleiben camelCase.
