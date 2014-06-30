Die Anbindung von SeedDMS an sopen erfolgt durch den von
SeedDMS bereitgestellte Web-Service. Die Tine-Applikation
'Document' realisiert die Anbindung in sopen. In den
Detailansichten für Förderprojekte und Kontakte wird
je ein zusätzlicher Reiter eingebunden, der die verknüpften
Dokumente in SeedDMS listet. Die Verknüpfung erfolgt über
die eindeutige Objekt-ID des Förderprojekts bzw. Kontakts.
Die zu diesem Objekt gehörigen Dokumente befinden sich in
einem gleichlautenen Ordner in SeedDMS. Die Konfiguration
mit den Zugangsdaten zu SeedDMS befinden sich in der Datei
config.php.

  'seeddms' => array(
    'url' => 'http://localhost/seeddms',
    'user' => 'admin',
    'pass' => 'secret',
    'parentFolder' => 4711
  ),

'parentFolder ist die Ordner-ID eines Unterordners in SeedDMS,
welcher die oben beschriebenen Unterverzeichnisse pro Förderprojekt
bzw. Kontakt beinhaltet.

Der Upload der Dokumente erfolgt zur Zeit noch aussschließlich
über SeedDMS.
