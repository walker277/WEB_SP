<?php
// nactu rozhrani kontroleru
require_once("app/Controllers/IController.interface.php");
// pripojim objekt pro spolecny kod
require_once("app/Controllers/BaseController.class.php");


/**
 * Ovladac zajistujici vypsani stranky s Aktualitami
 */
class AktualityController extends BaseController implements IController {
    /** @var DatabaseModel $db  Sprava databaze. */
    //private DatabaseModel $db;

    /**
     * Inicializace pripojeni k databazi.
     */
    public function __construct() {
        /*// inicializace prace s DB
        require_once ("app/Models/DatabaseModel.class.php");
        $this->db = new DatabaseModel();*/
        parent::__construct();
    }

    /**
     * Vrati obsah strany s Aktuality.
     * @param string $pageTitle     Nazev stranky.
     * @return string               Vypis v sablone.
     */
    public function show(string $pageTitle):string {
        //// vsechna data sablony budou globalni
        global $tplData;
        $tplData = [];
        /*// nazev
        $tplData['title'] = $pageTitle;

        $rights = $this->db->getAllRights();
        $tplData['clanky'] = $this->db->getAllClanky();
        $tplData['uzivatele'] = $this->db->getAllUsers();*/

        //naplnime vstupni data jako je titulek stranky a zjisteni jestli je uzivatel prihlasen atd.
        $this->naplnVstupniData($pageTitle);

        //podivame se jestli nekdo zaslal dotaz a pokud ano tak ho vlozime do tabulky DOTAZ
        $this->priselDotaz();

        /*//otestovani jestli neprisel dotaz
        if(isset($_POST['odeslano'])){
            if ( (isset($_POST["email"]) && $_POST["email"] != "") && (isset($_POST["jmeno"]) && $_POST["jmeno"] != "") && (isset($_POST["dotaz"]) && $_POST["dotaz"] != "")) {
                $this->db->addNewDotaz($_POST['email'], $_POST['jmeno'], $_POST['dotaz']);
            }
        }*/

        //testujeme jestli se nekdo chce prihlasit a zda se jedna opravdu o uzivatele ktery se prihlasuje
        // a kdyz ano tak ho prihlasime. zahrnuje testovani i pro odhlaseni
        $obsah = $this->prihalsOdhlasUzivatele('app/Views/DomaciStrankaTemplate.tpl.php', 'app/Views/IntroductionTemplate.tpl.php');
        //obnoveni tplData
        $tplData = $this->tplData;
        //pokud je obsah ruzny od null tak se prihlasujeme nebo odhlasujeme
        if($obsah != null ) {
            return $obsah;
        }


        // zpracovani odeslanych formularu na prihlaseni - mam akci?
        /*if(isset($_POST["action"])){
            // mam pozadavek na login ?
            if($_POST["action"] == "login") {
                // mam co ulozit?
                if ( (isset($_POST["username"]) && $_POST["username"] != "") && (isset($_POST["heslo1"]) && $_POST["heslo1"] != "") ) {
                    // prihlasim uzivatele
                    $res = $this->db->userLogin($_POST['username'], $_POST['heslo1']);
                    if ($res && $this->db->getLoggedUserData()['Zablokovany'] == 1){
                        $this->db->userLogout();
                        $tplData['prihlasen'] = false;
                        echo "<script>alert('ERROR: Uživatel je zablokovaný');</script>";
                    }else if($res){
                       echo "<script>alert('OK: Uživatel byl přihlášen');</script>";
                       $tplData['prihlasen'] = true;
                       $tplData['id_pravo'] = $this->db->getLoggedUserData()['id_pravo'];
                       //// vypsani prislusne sablony
                       // zapnu output buffer pro odchyceni vypisu sablony
                       ob_start();
                       // pripojim sablonu, cimz ji i vykonam
                       require("app/Views/DomaciStrankaTemplate.tpl.php");
                       // ziskam obsah output bufferu, tj. vypsanou sablonu
                        // vratim sablonu naplnenou daty
                       return ob_get_clean();
                   }else{
                       echo "<script>alert('ERROR: Přihlášení uživatele se nezdařilo');</script>";
                   }

                } else {
                    echo "<script>alert('Nebylo zadáno uživatelské jméno.');</script>";
                }
            }// mam pozadavek na logout?
            else if(isset($_POST['action'])){
                if($_POST["action"] == "logout"){
                    // odhlasim uzivatele
                    $this->db->userLogout();
                    $tplData['prihlasen'] = false;
                    echo "<script>alert('OK: Uživatel byl odhlášen');</script>";
                    //// vypsani prislusne sablony
                    // zapnu output buffer pro odchyceni vypisu sablony
                    ob_start();
                    // pripojim sablonu, cimz ji i vykonam
                    require("app/Views/IntroductionTemplate.tpl.php");
                    // ziskam obsah output bufferu, tj. vypsanou sablonu
                    return ob_get_clean();
                }
            }
            // neznamy pozadavek
            else {
                echo "<script>alert('Chyba: Nebyla rozpoznána požadovaná akce.');</script>";
            }
        }*/
        //rozlisujeme jaky controller bude vyuzit kdyz je uzivatel prihlasen nebo odhlasen
        return $this->rozpoznejPrihlasenehoOdhlaseneho('app/Views/AktualityTemplate.tpl.php','app/Views/AktualityTemplate.tpl.php');
        /*//ulozeni stavu prihlaseni abychom mohli v sablone rozlisovat hlavicky
        $tplData['prihlasen'] = $this->db->isUserLogged();
        if($tplData['prihlasen']) {
            $tplData['id_pravo'] = $this->db->getLoggedUserData()['id_pravo'];
        }
        //Pokud je uzivatel prihlasen zobrazime sablonu ktera je urcena prihlasenym uzivatelum
        ob_start();
        require("app/Views/AktualityTemplate.tpl.php");
        // vratim sablonu naplnenou daty
        return ob_get_clean();*/

    }

}


