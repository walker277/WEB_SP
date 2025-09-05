# Webový projekt WSP

**Popis:** Webová aplikace pro prohlížení, správu článků, recenzí a uživatelů (Konferenční web). Pro lokální spuštění je potřeba PHP, MySQL (např. přes phpMyAdmin) a lokální webový server (např. XAMPP).

---

## Instalace a spuštění

1. Naklonujte projekt:  
  
   git clone https://github.com/tvuj-uzivatel/tvuj-projekt.git

Nainstalujte lokální server, např. XAMPP, a spusťte Apache a MySQL.

Vytvořte databázi:
    Otevřete phpMyAdmin (http://localhost/phpmyadmin/)

Vytvořte novou databázi, např. WSP

Importujte instalační SQL skript, který se nachází v WEB_SP/SP/app/Models

Vyberte nově vytvořenou databázi

Klikněte na Import a nahrajte soubor .sql z projektu

Spusťte import

Případně upravte přístup k databázi pro lokální konfiguraci v souboru  WEB_SP/SP/settings.inc.php, změňte konstanty na příslušné hodnoty:

const DB_SERVER
const DB_NAME
const DB_USER
const DB_PASS

Umístěte projekt do složky htdocs ve XAMPPu:
    Windows: C:\xampp\htdocs\WEB_SP
    Linux: /opt/lampp/htdocs/WEB_SP
Spusťte web v prohlížeči:
    
    http://localhost/WEB_SP/

Poznámka: Informace o uživatelích, použitých technologiích a struktuře projektu jsou uvedeny v dokumentaci:
WEB_SP/SP/doc/dokumentace.pdf


