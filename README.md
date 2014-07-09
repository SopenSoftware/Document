### Dokumentenverwaltung über SeedDMS
Die Anbindung von SeedDMS an sopen erfolgt durch den von SeedDMS bereitgestellte Web-Service. Die Tine-Applikation 'Document' realisiert die Anbindung in sopen. In den Detailansichten für Förderprojekte und Kontakte wird je ein zusätzlicher Reiter eingebunden, der die verknüpften Dokumente in SeedDMS listet. Die Verknüpfung erfolgt über
die eindeutige Objekt-ID des Förderprojekts, Kontakts oder Mitglieds. Die zu diesem Objekt gehörigen Dokumente befinden sich in einem Ordner in SeedDMS mit dem Namen des Datensatzes.

### Konfiguration
Die Konfiguration mit den Zugangsdaten zu SeedDMS befinden sich in der Datei config.php:
```php
  'seeddms' => array(
    'url' => 'http://localhost/seeddms',
    'user' => 'admin',
    'pass' => 'secret'
  ),
```

Der Upload der Dokumente erfolgt zur Zeit noch aussschließlich über SeedDMS.
