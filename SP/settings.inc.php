<?php
///////////////////////////////////////////////////////
////////////// Zakladni nastaveni webu ////////////////
///////////////////////////////////////////////////////

////// nastaveni pristupu k databazi ///////

// prihlasovaci udaje k databazi
const DB_SERVER = "127.0.0.1";
const DB_NAME = "WSP";
const DB_USER = "root";
const DB_PASS = "";

// definice konkretnich nazvu tabulek
const TABLE_UZIVATEL = "UZIVATEL";
const TABLE_PRAVO = "pravo";

/** Tabulka s pohadkami. */
const TABLE_CLANEK = "CLANEK";
const TABLE_CLANKY_AUTORA = "CLANKY_AUTORA";
const TABLE_DOTAZ = "DOTAZ";

/** Adresar kontroleru. */
const DIRECTORY_CONTROLLERS = "app/Controllers";

/** Klic defaultni webove stranky. */
const DEFAULT_WEB_PAGE_KEY = "uvod";


/** Dostupne webove stranky. */
const WEB_PAGES = array(
    //// Uvodni stranka ////
    "uvod" => array(
        "title" => "Úvod",

        //// kontroler
        "file_name" => "IntroductionController.class.php",
        "class_name" => "IntroductionController",
    ),
    //// KONEC: Uvodni stranka ////

    //// Sstranka s aktuality ////
    "aktuality" => array(
        "title" => "Aktuality",

        //// kontroler
        "file_name" => "AktualityController.class.php",
        "class_name" => "AktualityController",
    ),
    //// KONEC: Aktualit ////

    //// Registrace ////
    "registrace" => array(
        "title" => "Registrace",

        //// kontroler
        "file_name" => "RegistraceController.class.php",
        "class_name" => "RegistraceController",
    ),
    //// KONEC: Registracni stranky ////

    //// Stranka s Osobnimi udajy ////
    "osobniudaje" => array(
        "title" => "Osobní Údaje",

        //// kontroler
        "file_name" => "OsobniUdajeController.class.php",
        "class_name" => "OsobniUdajeController",
    ),
    //// KONEC: Osobnich udaju ////

    //// Sprava uzivatelu ////
    "spravaUzivatelu" => array(
        "title" => "Správa uživatelů",

        //// kontroler
        "file_name" => "spravaUzivateluController.class.php",
        "class_name" => "spravaUzivatelu",
    ),
    //// KONEC////
    //// Stranka s clanky autora ////
    "mojeClanky" => array(
        "title" => "Moje články",

        //// kontroler
        "file_name" => "mojeClankyController.class.php",
        "class_name" => "mojeClankyController",
    ),
    //// KONEC////
    //// Stranka s calnky recenzenta ////
    "mojeRecenze" => array(
        "title" => "Moje recenze",

        //// kontroler
        "file_name" => "mojeRecenzeController.class.php",
        "class_name" => "mojeRecenzeController",
    ),
    //// KONEC////
    //// Sprava clanku ////
    "spravaclanku" => array(
        "title" => "Správa článků",

        //// kontroler
        "file_name" => "spravaClankuController.class.php",
        "class_name" => "spravaClankuController",
    ),
    //// KONEC////
    //// Zverejnene clanky ////
    "domaci" => array(
        "title" => "Zveřejněné články",

        //// kontroler
        "file_name" => "domaciController.class.php",
        "class_name" => "domaciController",
    ),
    //// KONEC////
    //// Stranka s dotazy////
    "dotazy" => array(
        "title" => "Dotazy",

        //// kontroler
        "file_name" => "dotazyController.class.php",
        "class_name" => "dotazyController",
    ),
    //// KONEC////
);
