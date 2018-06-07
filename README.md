# DSGVO - Benutzerdatenexport

Durch die am 25.5.18 in Kraft getretene DSGVO ist der Betreiber eines Shops auf Anfrage dazu verpflichtet alle Benutzerdaten 
bereitzustellen.

Dieses Modul Basiert auf dem Blog Beitrag von Clemens Scholz 
https://oxidforge.org/en/how-we-temporarily-handle-the-right-to-data-portability-art-20-gdpr.html


## Einrichtung

### Composer / Packagist

Geben Sie auf der CLI folgenden Befehl ein

    composer require 

### Manuelle Einrichtung

Fügen Sie folgenden Code in  die composer.json im Root Verzeichnis
      
      "require": {
        ...
        "codecommerce/dsgvo_userdata": "^1.0",
        ...
      }, 
      
      "repositories": {
          ....
          "codecommerce/dsgvo_userdata": {
            "type": "path",
            "url": "./source/modules/codecommerce/dsgvo_userdata/"
          },
          ...
        }
        
Fügen Sie anschließend den Befehl aus

    composer require codecommerce/dsgvo_userdata

Kopieren Sie nun den Ordner 'copy_this' in das Root Verzeichnis Ihres Shops.

Aktivieren Sie das Modul unter 'Erweiterungen'/'Module'.

Sie können den Text der E-Mail unter der CMS-Seite 'cc_dsgvo_userdata' anpassen und in andere Sprachen übersetzen.

## Erweiterungen

Um die Ausgabe der Daten zu erweitern nutzen Sie bitte die Funktion cc_dsgvo_userdata_export::externalDataHook()
 