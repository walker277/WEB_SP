# Webový projekt WSP

**Popis:** Webová aplikace pro prohlížení, správu článků, recenzí a uživatelů (Konferenční web). Pro lokální spuštění je potřeba PHP, MySQL (např. přes phpMyAdmin) a lokální webový server (např. XAMPP).

---

## Instalace a spuštění
Naklonujte projekt:  
  
   git clone https://github.com/walker277/WEB_SP.git

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
    
    http://localhost/WEB_SP/SP/

Poznámka: Informace o uživatelích, použitých technologiích a struktuře projektu jsou uvedeny v dokumentaci:
WEB_SP/SP/doc/dokumentace.pdf


***Pozadavky projektu:***
    Technologie - povinně HTML5, CSS, PHP a SQL (MySQL nebo jiná databáze), volitelně Twig, JavaScript, AJAX, Bootstrap apod.
    Aplikace musí dodržovat MVC architekturu a využívat OOP (min. controllery a model).
    Web má jeden vstupní soubor (obvykle index.php), který na základě parametrů URL adresy provede požadovanou akci (tj. zavolá příslušný controller) a vypíše výstup uživateli.
    Pro práci s databází musí být využito PDO nebo jeho ekvivalent.
    Web musí být chráněn proti útokům typu XSS a SQL Injection.
    V databázi musí být hesla hashována (Bcrypt).
    Web musí využívat upload souborů.
    Web musí mít responzivní design (alespoň pro PC a mobil).
    Web musí mít alespoň 3 uživatelské role (po přihlášení v systému provádí příslušné činnosti, např. tvůrce obsahu, správce obsahu, správce uživatelů (admin), a volitelně správce administrátorů (superadmin)).
    K aplikaci musí být dodána dokumentace (viz dále) a skripty pro instalaci databáze (např. získané exportem databáze).
    Práce musí být osobně předvedena cvičícímu a po schválení odevzdána na CourseWare či Portál.
    Aplikaci není možné realizovat s využitím ucelených PHP frameworků (zakázáno např. Nette, Symfony atd.). Použití jejich komponent je možné pouze po schválení vyučujícím.
    Pro front-end je vhodné využít framework Bootstrap (getbootstrap.com) nebo jeho ekvivalent (Tailwind CSS, W3.CSS aj.).



