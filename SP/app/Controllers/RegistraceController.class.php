<?php
// nactu rozhrani kontroleru
require_once("app/Controllers/IController.interface.php");
// pripojim objekt pro spolecny kod
require("app/Controllers/BaseController.class.php");
/**
 * Ovladac zajistujici vypsani stranky s Registraci uzivatelu
 */
class RegistraceController extends BaseController implements IController {
    /** @var DatabaseModel $db  Sprava databaze. */
    //private DatabaseModel $db;

    /**
     * Inicializace pripojeni k databazi.
     */
    public function __construct() {
        // inicializace prace s DB
        //require_once ("app/Models/DatabaseModel.class.php");
        //$this->db = new DatabaseModel();
        parent::__construct();
    }

    /**
     * Vrati obsah strany s Registraci.
     * @param string $pageTitle     Nazev stranky.
     * @return string               Vypis v sablone.
     */
    public function show(string $pageTitle):string {
        //// vsechna data sablony budou globalni
        global $tplData;
        $tplData = [];
        $this->naplnVstupniData($pageTitle);

        $this->priselDotaz();
        /*global $tplData;
        $tplData = [];

        // nazev
        $tplData['title'] = $pageTitle;

        $rights = $this->db->getAllRights();
        $tplData['clanky'] = $this->db->getAllClanky();
        $tplData['uzivatele'] = $this->db->getAllUsers();


        //otestovani jesli mame dotaz
        if(isset($_POST['odeslano'])){
            if ( (isset($_POST["email"]) && $_POST["email"] != "") && (isset($_POST["jmeno"]) && $_POST["jmeno"] != "") && (isset($_POST["dotaz"]) && $_POST["dotaz"] != "")) {
                $this->db->addNewDotaz($_POST['email'], $_POST['jmeno'], $_POST['dotaz']);
            }
        }*/


        if(isset($_POST['potvrzeni'])){
            //mam vsechny pozadovane hodnoty?
            if( isset($_POST['email']) && isset($_POST['username']) && isset($_POST['jmeno']) && isset($_POST['heslo1'])
                && isset($_POST['narozeni']) && isset($_POST['heslo2']) && isset($_POST['pohlavi'])
                && ($_POST['heslo1'] == $_POST['heslo2'])
                && ($_POST['email'] != "") && ($_POST['username'] != "") && ($_POST['jmeno'] != "") && ($_POST['heslo1'] != "")
                && ($_POST['narozeni'] != "") && ($_POST['heslo2'] != "") && ($_POST['pohlavi'] != "") ){
                $password = $_POST['heslo2'];
                //zahashovani hesla
                $hash = password_hash($password, PASSWORD_BCRYPT);
                if($this->db->jeUsernameVolne($_POST['username'],$this->db->getAllUsers())){
                    $res = $this->db->addNewUser($_POST['username'], $hash, $_POST['jmeno'], $_POST['email'], $_POST['pohlavi'], $_POST['narozeni'] );//byl vlozen?
                    if($res){
                        echo "<script>alert('OK: Uživatel byl přidán do databáze');</script>";
                    }else{
                        echo "<script>alert('ERROR: Vložení uživatle do databáze se nezdařilo');</script>";
                    }
                }else{
                    echo "<script>alert('ERROR: Uzivatelske jmeno je zabrane, a proto si zvolte jine');</script>";
                }

            }else{ //nemame vsechny atributy
                echo "<script>alert('ERROR: Nebyly přijaty požadované atributy uživatele');</script>";
            }
        }


        $obsah = $this->prihalsOdhlasUzivatele('app/Views/DomaciStrankaTemplate.tpl.php', 'app/Views/IntroductionTemplate.tpl.php');
        $tplData = $this->tplData;

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
                    }elseif($res){
                        echo "<script>alert('OK: Uživatel byl přihlášen');</script>";
                        $tplData['prihlasen'] = true;
                        $tplData['id_pravo'] = $this->db->getLoggedUserData()['id_pravo'];
                        //// vypsani prislusne sablony
                        // zapnu output buffer pro odchyceni vypisu sablony
                        ob_start();
                        // pripojim sablonu, cimz ji i vykonam
                        require("app/Views/DomaciStrankaTemplate.tpl.php");
                        // ziskam obsah output bufferu, tj. vypsanou sablonu
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

        $tplData = $this->tplData;

        $obsah =  $this->rozpoznejPrihlasenehoOdhlaseneho('app/Views/OsobniUdajeTemplate.tpl.php','app/Views/RegistraceTemplate.tpl.php');


        return $obsah;

        //ulozeni stavu prihlaseni abychom mohli v sablone rozlisovat hlavicky
        /*$tplData['prihlasen'] = $this->db->isUserLogged();
        if($tplData['prihlasen']) {
            $tplData['id_pravo'] = $this->db->getLoggedUserData()['id_pravo'];
        }
        //Pokud je uzivatel prihlasen zobrazime sablonu ktera je urcena prihlasenym uzivatelum
        if($this->db->isUserLogged()){
            //// vypsani prislusne sablony
            // zapnu output buffer pro odchyceni vypisu sablony
            ob_start();
            // pripojim sablonu, cimz ji i vykonam
            require("app/Views/OsobniUdajeTemplate.tpl.php");
            // ziskam obsah output bufferu, tj. vypsanou sablonu
            return ob_get_clean();
        } else{
            //// vypsani prislusne sablony
            // zapnu output buffer pro odchyceni vypisu sablony
            ob_start();
            // pripojim sablonu, cimz ji i vykonam
            require("app/Views/RegistraceTemplate.tpl.php");
            // ziskam obsah output bufferu, tj. vypsanou sablonu
            // vratim sablonu naplnenou daty
            return ob_get_clean();
        }*/

    }
}
