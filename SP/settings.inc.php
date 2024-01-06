<?php
///////////////////////////////////////////////////////
////////////// Zakladni nastaveni webu ////////////////
///////////////////////////////////////////////////////

////// nastaveni pristupu k databazi ///////

// prihlasovaci udaje k databazi
define("DB_SERVER","127.0.0.1");
define("DB_NAME","WSP");
define("DB_USER","root");
define("DB_PASS","");

// definice konkretnich nazvu tabulek
define("TABLE_UZIVATEL","UZIVATEL");
define("TABLE_PRAVO","pravo");

/** Tabulka s pohadkami. */
define("TABLE_CLANEK", "CLANEK");
define("TABLE_CLANKY_AUTORA", "CLANKY_AUTORA");
define("TABLE_DOTAZ", "DOTAZ");

/** Adresar kontroleru. */
const DIRECTORY_CONTROLLERS = "app/Controllers";
/** Adresar modelu. */
const DIRECTORY_MODELS = "app/Models";
/** Adresar sablon */
const DIRECTORY_VIEWS = "app/Views";

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
    "mojeClanky" => array(
        "title" => "Moje články",

        //// kontroler
        "file_name" => "mojeClankyController.class.php",
        "class_name" => "mojeClankyController",
    ),
    "mojeRecenze" => array(
        "title" => "Moje recenze",

        //// kontroler
        "file_name" => "mojeRecenzeController.class.php",
        "class_name" => "mojeRecenzeController",
    ),
    "spravaclanku" => array(
        "title" => "Správa článků",

        //// kontroler
        "file_name" => "spravaClankuController.class.php",
        "class_name" => "spravaClankuController",
    ),

    "domaci" => array(
        "title" => "Zveřejněné články",

        //// kontroler
        "file_name" => "domaciController.class.php",
        "class_name" => "domaciController",
    ),
    "dotazy" => array(
        "title" => "Dotazy",

        //// kontroler
        "file_name" => "dotazyController.class.php",
        "class_name" => "dotazyController",
    ),
    //// KONEC: Sprava uzivatelu ////
);

?>