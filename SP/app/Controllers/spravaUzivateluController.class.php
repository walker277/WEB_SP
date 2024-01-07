<?php
// nactu rozhrani kontroleru
require_once("app/Controllers/IController.interface.php");
// pripojim objekt pro spolecny kod
require("app/Controllers/BaseController.class.php");
/**
 * Ovladac zajistujici vypsani stranky se spravou uzivatelu.
 */
class spravaUzivatelu extends BaseController implements IController {

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
     * Vrati obsah stranky se spravou uzivatelu.
     * @param string $pageTitle     Nazev stranky.
     * @return string               Vypis v sablone.
     */
    public function show(string $pageTitle):string {
        //// vsechna data sablony budou globalni
        /*global $tplData;
        $tplData = [];
        // nazev
        $tplData['title'] = $pageTitle;

        $tplData['clanky'] = $this->db->getAllClanky();
        $tplData['uzivatele'] = $this->db->getAllUsers();

        //otestovani jesli mame dotaz
        if(isset($_POST['odeslano'])){
            if ( (isset($_POST["email"]) && $_POST["email"] != "") && (isset($_POST["jmeno"]) && $_POST["jmeno"] != "") && (isset($_POST["dotaz"]) && $_POST["dotaz"] != "")) {
                $this->db->addNewDotaz($_POST['email'], $_POST['jmeno'], $_POST['dotaz']);
            }
        }*/
        global $tplData;
        $tplData = [];
        $this->naplnVstupniData($pageTitle);

        $this->priselDotaz();

        $obsah = $this->prihalsOdhlasUzivatele('app/Views/DomaciStrankaTemplate.tpl.php', 'app/Views/IntroductionTemplate.tpl.php');
        $tplData = $this->tplData;

        if($obsah != null ) {
            return $obsah;
        }

        /*// zpracovani odeslanych formularu na prihlaseni - mam akci?
        if(isset($_POST["action"])){
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
                    echo "<script>alert('OK: Uživatel byl odhlášen');</script>";
                    $tplData['prihlasen'] = false;
                    //// vypsani prislusne sablony
                    // zapnu output buffer pro odchyceni vypisu sablony
                    ob_start();
                    // pripojim sablonu, cimz ji i vykonam
                    require("app/Views/IntroductionTemplate.tpl.php");
                    // ziskam obsah output bufferu, tj. vypsanou sablonu
                    // vratim sablonu naplnenou daty
                    return ob_get_clean();
                }
            }
            // neznamy pozadavek
            else {
                echo "<script>alert('Chyba: Nebyla rozpoznána požadovaná akce.');</script>";
            }
        }*/

        $tplData['prihlasen'] = $this->db->isUserLogged();
        if($tplData['prihlasen']){
            $user = $this->db->getLoggedUserData();
            $UzivID = $user['id_uzivatel'];
        }

        //zpracovani odeslanych formularu
        if(isset($_POST['potvrzeni'])){
            //mam vsechny pozadovane hodnoty?
            if( (isset($_POST['pravo']) && ($_POST['pravo'] != "")) && (isset($_POST['id_u']) && ($_POST['id_u'] != ""))  ){
                $res = $this->db->updateUserRight($_POST['id_u'], $_POST['pravo']);
                if($res){
                    echo "<script>alert('OK: Uživatel byl upraven.');</script>";
                }else{
                    echo "<script>alert('ERROR: Upravení uživatele se nezdařilo');</script>";
                }
            }elseif ( (isset($_POST['pohlavi']) && ($_POST['pohlavi'] != "")) && (isset($_POST['id_uz']) && ($_POST['id_uz'] != "")) ){
                $res = $this->db->updateUserGender($_POST['id_uz'], $_POST['pohlavi']);
                if($res){
                    echo "<script>alert('OK: Uživatel byl upraven.');</script>";
                }else{
                    echo "<script>alert('ERROR: Upravení uživatele se nezdařilo');</script>";
                }
            }elseif ( (isset($_POST['datum']) && ($_POST['datum'] != "")) && (isset($_POST['id_uzi']) && ($_POST['id_uzi'] != "")) ){
                $res = $this->db->updateUserDate($_POST['id_uzi'], $_POST['datum']);
                if($res){
                    echo "<script>alert('OK: Uživatel byl upraven.');</script>";
                }else{
                    echo "<script>alert('ERROR: Upravení uživatele se nezdařilo');</script>";
                }
            }else if ( isset($_POST['id_uzivatel']) ){
                $poleKlicu[0] = ':kIdUzivatel';
                $poleHodnot[0] = $_POST['id_uzivatel'];
                $res = $this->db->deleteFromTable(TABLE_UZIVATEL, "id_uzivatel=:kIdUzivatel", $poleKlicu, $poleHodnot);
                if($res){
                    echo "<script>alert('Ok: Uživatel byl smazán z databáze');</script>";

                }else{
                    echo "<script>alert('ERRO: Smazaní uživatle se nezdařilo');</script>";
                }
            }else if( (isset($_POST['emailSez']) && ($_POST['emailSez'] != "")) && (isset($_POST['id_uziv']) && ($_POST['id_uziv'] != "")) ){
                $res = $this->db->updateUserEmail($_POST['id_uziv'], $_POST['emailSez']);
                if($res){
                    echo "<script>alert('OK: Uživatel byl upraven.');</script>";
                }else{
                    echo "<script>alert('ERROR: Upravení uživatele se nezdařilo');</script>";
                }
            }elseif ( (isset($_POST['usernameSez']) && ($_POST['usernameSez'] != "")) && (isset($_POST['id_uziva']) && ($_POST['id_uziva'] != "")) ){
                $res = $this->db->updateUsername($_POST['id_uziva'], $_POST['usernameSez']);
                if($res){
                    echo "<script>alert('OK: Uživatel byl upraven.');</script>";
                }else{
                    echo "<script>alert('ERROR: Upravení uživatele se nezdařilo');</script>";
                }
            }elseif ( (isset($_POST['jmenoSez']) && ($_POST['jmenoSez'] != "")) && (isset($_POST['id_uzivat']) && ($_POST['id_uzivat'] != "")) ){
                $res = $this->db->updateUserJmeno($_POST['id_uzivat'], $_POST['jmenoSez']);
                if($res){
                    echo "<script>alert('OK: Uživatel byl upraven.');</script>";
                }else{
                    echo "<script>alert('ERROR: Upravení uživatele se nezdařilo');</script>";
                }
            }elseif( (isset($_POST['hesloSez']) && $_POST['hesloSez'] != "") && (isset($_POST['id_uzivate']) && ($_POST['id_uzivate'] != "")) ){
                $res = $this->db->updateUserPass($_POST['id_uzivate'], $_POST['hesloSez']);
                if($res){
                    echo "<script>alert('OK: Uživatel byl upraven.');</script>";
                }else{
                    echo "<script>alert('ERROR: Upravení uživatele se nezdařilo');</script>";
                }
            }elseif( $_POST['potvrzeni'] != "" && $_POST['potvrzeni'] == 1 && (isset($_POST['id_uzivatell']) && ($_POST['id_uzivatell'] != ""))){
                //zablokovat
                $res = $this->db->updateZablokovaniUzivatele($_POST['id_uzivatell'], 1);
                if($res){
                    echo "<script>alert('OK: Uživatel byl upraven.');</script>";
                }else{
                    echo "<script>alert('ERROR: Upravení uživatele se nezdařilo');</script>";
                }
            }elseif( $_POST['potvrzeni'] != "" && $_POST['potvrzeni'] == 0 && (isset($_POST['id_uzivatell']) && ($_POST['id_uzivatell'] != "")) ){
                //povolit
                $res = $this->db->updateZablokovaniUzivatele($_POST['id_uzivatell'], 0);
                if($res){
                    echo "<script>alert('OK: Uživatel byl upraven.');</script>";
                }else{
                    echo "<script>alert('ERROR: Upravení uživatele se nezdařilo');</script>";
                }
            }
        }

        /*if($tplData['prihlasen']) {
            $users = $this->db->getAllUsers();
            $tplData['id_pravo'] = $this->db->getLoggedUserData()['id_pravo'];
            $tplData['uzivatele'] = $this->db->getAllUsers();
            $tplData['prava'] = $this->db->getAllRights();
            $tplData['uzivatel'] = $this->db->getLoggedUserData();
        }

        if($tplData['prihlasen']){
            //// vypsani prislusne sablony
            // zapnu output buffer pro odchyceni vypisu sablony
            ob_start();
            // pripojim sablonu, cimz ji i vykonam
            require("app/Views/spravaUzivateluTemplate.tpl.php");
            // ziskam obsah output bufferu, tj. vypsanou sablonu
            // vratim sablonu naplnenou daty
            return ob_get_clean();
        }else{
            //// vypsani prislusne sablony
            // zapnu output buffer pro odchyceni vypisu sablony
            ob_start();
            // pripojim sablonu, cimz ji i vykonam
            require("app/Views/IntroductionTemplate.tpl.php");
            // ziskam obsah output bufferu, tj. vypsanou sablonu
            // vratim sablonu naplnenou daty
            return ob_get_clean();
        }*/
        $tplData = $this->tplData;

        $obsah =  $this->rozpoznejPrihlasenehoOdhlaseneho('app/Views/spravaUzivateluTemplate.tpl.php','app/Views/IntroductionTemplate.tpl.php');


        return $obsah;


    }

}
