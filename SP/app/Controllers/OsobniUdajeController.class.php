<?php
// nactu rozhrani kontroleru
require_once("app/Controllers/IController.interface.php");

/**
 * Ovladac zajistujici vypsani stranky s Osobnimi udaji uzivatele
 */
class OsobniUdajeController implements IController {
    /** @var DatabaseModel $db  Sprava databaze. */
    private $db;

    /**
     * Inicializace pripojeni k databazi.
     */
    public function __construct() {
        // inicializace prace s DB
        require_once ("app/Models/DatabaseModel.class.php");
        $this->db = new DatabaseModel();
    }

    /**
     * Vrati obsah strany s Osobnimi udaji.
     * @param string $pageTitle     Nazev stranky.
     * @return string               Vypis v sablone.
     */
    public function show(string $pageTitle):string {
        global $tplData;
        $tplData = [];
        //// vsechna data sablony budou globalni
        $tplData['prihlasen'] = $this->db->isUserLogged();
        // nazev
        $tplData['title'] = $pageTitle;

        // zpracovani odeslanych formularu na prihlaseni - mam akci?
        if(isset($_POST["action"])) {
            // mam pozadavek na login ?
            if ($_POST["action"] == "login") {
                // mam co ulozit?
                if ((isset($_POST["username"]) && $_POST["username"] != "") && (isset($_POST["heslo1"]) && $_POST["heslo1"] != "")) {
                    // prihlasim uzivatele
                    $res = $this->db->userLogin($_POST['username'], $_POST['heslo1']);
                    if ($res && $this->db->getLoggedUserData()['Zablokovany'] == 1){
                        $this->db->userLogout();
                        $tplData['prihlasen'] = false;
                        echo "<script>alert('ERROR: Uživatel je zablokovaný');</script>";
                    }elseif ($res) {
                        echo "<script>alert('OK: Uživatel byl přihlášen');</script>";
                        $tplData['prihlasen'] = true;
                        $tplData['id_pravo'] = $this->db->getLoggedUserData()['id_pravo'];
                        //// vypsani prislusne sablony
                        // zapnu output buffer pro odchyceni vypisu sablony
                        ob_start();
                        // pripojim sablonu, cimz ji i vykonam
                        require("app/Views/DomaciStrankaTemplate.tpl.php");
                        // ziskam obsah output bufferu, tj. vypsanou sablonu
                        $obsah = ob_get_clean();
                        return $obsah;
                    } else {
                        echo "<script>alert('ERROR: Přihlášení uživatele se nezdařilo');</script>";
                    }


                } else {
                    echo "<script>alert('Nebylo zadáno uživatelské jméno.');</script>";
                }
            }else if($_POST["action"] == "logout"){
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
                $obsah = ob_get_clean();

                // vratim sablonu naplnenou daty
                return $obsah;

            }
        }

        if($tplData['prihlasen']){
            $user = $this->db->getLoggedUserData();
            $UzivID = $user['id_uzivatel'];
            $tplData['nazevUzivatelovaPrava'] = $this->db->getRightById($user['id_pravo'])['nazev'];
        }

            //zpracovani odeslanych formularu
            if(isset($_POST['potvrzeni'])){
                //mam vsechny pozadovane hodnoty?
                if( (isset($_POST['email']) && ($_POST['email'] != "")) ){
                    $res = $this->db->updateUserEmail($UzivID, $_POST['email']);
                    if($res){
                        echo "<script>alert('OK: Uživatel byl upraven.');</script>";
                    }else{
                        echo "<script>alert('ERROR: Upravení uživatele se nezdařilo');</script>";
                    }
                }elseif ( (isset($_POST['username']) && ($_POST['username'] != "")) ){
                    $res = $this->db->updateUsername($UzivID, $_POST['username']);
                    if($res){
                        echo "<script>alert('OK: Uživatel byl upraven.');</script>";
                    }else{
                        echo "<script>alert('ERROR: Upravení uživatele se nezdařilo');</script>";
                    }
                }elseif ( (isset($_POST['jmeno_prijmeni']) && ($_POST['jmeno_prijmeni'] != ""))){
                    $res = $this->db->updateUserJmeno($UzivID, $_POST['jmeno_prijmeni']);
                    if($res){
                        echo "<script>alert('OK: Uživatel byl upraven.');</script>";
                    }else{
                        echo "<script>alert('ERROR: Upravení uživatele se nezdařilo');</script>";
                    }
                } elseif( (isset($_POST['heslo1']) && $_POST['heslo1'] != "") && (isset($_POST['heslo2']) && $_POST['heslo2'] != "")
                    && (isset($_POST['heslo3']) && $_POST['heslo3'] != "") && ($_POST['heslo2'] == $_POST['heslo3']) ){

                    //bylo zadano spravne soucasne heslo
                    if(password_verify($_POST['heslo1'],$user['password'])){
                        $res = $this->db->updateUserPass($UzivID, $_POST['heslo2']);
                        if($res){
                            echo "<script>alert('OK: Uživatel byl upraven.');</script>";
                        }else{
                            echo "<script>alert('ERROR: Upravení uživatele se nezdařilo');</script>";
                        }
                    }else{
                        echo "<script>alert('ERROR: Bylo zadáno špatné současné heslo uživatele');</script>";
                    }
                }
            }

        $tplData['prihlasen'] = $this->db->isUserLogged();

        if($tplData['prihlasen']) {
            $tplData['id_pravo'] = $this->db->getLoggedUserData()['id_pravo'];
        }

        if($tplData['prihlasen']) {
            //ulozim si data o uzivateli do pole
            $tplData['uzivatel'] = $this->db->getLoggedUserData();
            //// vypsani prislusne sablony
            // zapnu output buffer pro odchyceni vypisu sablony
            ob_start();
            // pripojim sablonu, cimz ji i vykonam
            require("app/Views/OsobniUdajeTemplate.tpl.php");
            // ziskam obsah output bufferu, tj. vypsanou sablonu
            $obsah = ob_get_clean();

            // vratim sablonu naplnenou daty
            return $obsah;
        }else{
            //ulozim si data o uzivateli do pole
            $tplData['uzivatel'] = $this->db->getLoggedUserData();
            var_dump($tplData['prihlasen']);
            //// vypsani prislusne sablony
            // zapnu output buffer pro odchyceni vypisu sablony
            ob_start();
            // pripojim sablonu, cimz ji i vykonam
            require("app/Views/IntroductionTemplate.tpl.php");
            // ziskam obsah output bufferu, tj. vypsanou sablonu
            $obsah = ob_get_clean();

            // vratim sablonu naplnenou daty
            return $obsah;
        }

    }
}

?>