<?php
// nactu rozhrani kontroleru
require_once("app/Controllers/IController.interface.php");

/**
 * Ovladac zajistujici vypsani stranky se spravou uzivatelu.
 */
class spravaClankuController implements IController {

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
     * Vrati obsah stranky se spravou uzivatelu.
     * @param string $pageTitle     Nazev stranky.
     * @return string               Vypis v sablone.
     */
    public function show(string $pageTitle):string {

        //// vsechna data sablony budou globalni
        global $tplData;
        $tplData = [];
        // nazev
        $tplData['title'] = $pageTitle;

        $tplData['clanky'] = $this->db->getAllClanky();
        $tplData['uzivatele'] = $this->db->getAllUsers();

        $tplData['prihlasen'] = $this->db->isUserLogged();
        if($tplData['prihlasen']){
            $user = $this->db->getLoggedUserData();
            $UzivID = $user['id_uzivatel'];
        }

        //otestovani jesli mame dotaz
        if(isset($_POST['odeslano'])){
            if ( (isset($_POST["email"]) && $_POST["email"] != "") && (isset($_POST["jmeno"]) && $_POST["jmeno"] != "") && (isset($_POST["dotaz"]) && $_POST["dotaz"] != "")) {
                $this->db->addNewDotaz($_POST['email'], $_POST['jmeno'], $_POST['dotaz']);
            }
        }

        // zpracovani odeslanych formularu na prihlaseni - mam akci?
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
                        $obsah = ob_get_clean();
                        // vratim sablonu naplnenou daty
                        return $obsah;
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
                    $obsah = ob_get_clean();
                    // vratim sablonu naplnenou daty
                    return $obsah;
                }
            }
            // neznamy pozadavek
            else {
                echo "<script>alert('Chyba: Nebyla rozpoznána požadovaná akce.');</script>";
            }
        }
        //stav clanku
        if(isset($_POST['stavC']) ){
            if($_POST['stavC'] == "povolitC"){
                $this->db->updateClanekSchvalen($_POST['idClanek'], 2);
            }else{
                $this->db->updateClanekSchvalen($_POST['idClanek'], 1);
            }
        }
        //pridani recenzenta
        if(isset($_POST['pridat']) ) {
            if(isset($_POST['recenzent']) && $_POST['recenzent'] != ""){
                $clanek = $this->db->getClanekById($_POST['idClanek']);
                if($clanek['recenzent_1'] == null){

                    $this->db->updateClanekRecenzent($clanek, $_POST['recenzent'], 'recenzent_1', "", 0);
                }else if($clanek['recenzent_2'] == null){
                    if($clanek['recenzent_1'] != $_POST['recenzent']){
                        $this->db->updateClanekRecenzent($clanek, $_POST['recenzent'], 'recenzent_2', "", 0);
                    }
                }else if ($clanek['recenzent_3'] == null){
                    if($clanek['recenzent_1'] != $_POST['recenzent'] && $clanek['recenzent_2'] != $_POST['recenzent'] ) {
                        $this->db->updateClanekRecenzent($clanek, $_POST['recenzent'], 'recenzent_3', "", 0);
                    }
                }else{
                    echo "<script>alert('Chyba: počet přidělených recenzentů je vyčerpaný pro nového recenzenta musítě nějakého odebrat.');</script>";
                }
            }
        }

        //odebrani recenzenta
        if(isset($_POST['smazatA'])){
            var_dump($_POST['smazatA']);
            //pokud mazeme prvního recenzenta musíme ho vymazat přesunout ostatní pokud jsou
            if($_POST['smazatA'] === "recenzent_1"){
                //smazeme recenzenta
                $clanek = $this->db->getClanekById($_POST['idClanek']);
                $this->db->updateClanekRecenzent($clanek, 0, 'recenzent_1', "", 0);
                //podivame se na dalsi recenzenty a pripadne posuneme
                if($clanek['recenzent_2'] != null){
                   $recenzent2 = $clanek['recenzent_2'];
                    $komentar2 = $clanek['komentar_2'];
                    $hodnoceni2 = $clanek['hodnoceni_2'];
                    //presuneme tak ze ho smazeme
                    $this->db->updateClanekRecenzent($clanek, 0, 'recenzent_2', "", 0);
                    //a dame ho do recenzent 1
                    $this->db->updateClanekRecenzent($clanek, $recenzent2, 'recenzent_1', $komentar2, $hodnoceni2);
                }
                //to samé pro třetího
                if($clanek['recenzent_3'] != null){
                    $recenzent3 = $clanek['recenzent_3'];
                    $komentar3 = $clanek['komentar_3'];
                    $hodnoceni3 = $clanek['hodnoceni_3'];
                    //presuneme tak ze ho smazeme
                    $this->db->updateClanekRecenzent($clanek, 0, 'recenzent_3', "", 0);
                    //a dame ho do recenzent 1
                    $this->db->updateClanekRecenzent($clanek, $recenzent3, 'recenzent_2', $komentar3, $hodnoceni3);
                }
            }else if($_POST['smazatA'] === "recenzent_2"){
                //smazeme recenzenta
                $clanek = $this->db->getClanekById($_POST['idClanek']);
                $this->db->updateClanekRecenzent($clanek, 0, 'recenzent_2', "", 0);
                //podivame se na dalsi recenzenty a pripadne posuneme
                if($clanek['recenzent_3'] != null){
                    $recenzent3 = $clanek['recenzent_3'];
                    $komentar3 = $clanek['komentar_3'];
                    $hodnoceni3 = $clanek['hodnoceni_3'];
                    //presuneme tak ze ho smazeme
                    $this->db->updateClanekRecenzent($clanek, 0, 'recenzent_3', "", 0);
                    //a dame ho do recenzent 1
                    $this->db->updateClanekRecenzent($clanek, $recenzent3, 'recenzent_2', $komentar3, $hodnoceni3);
                }
            }else if($_POST['smazatA'] === "recenzent_3"){
                //smazeme recenzenta
                $clanek = $this->db->getClanekById($_POST['idClanek']);
                $this->db->updateClanekRecenzent($clanek, 0, 'recenzent_3', "", 0);
            }
        }



        if($tplData['prihlasen']) {
            $users = $this->db->getAllUsers();
            $tplData['id_pravo'] = $this->db->getLoggedUserData()['id_pravo'];
            $tplData['uzivatele'] = $this->db->getAllUsers();
            $tplData['prava'] = $this->db->getAllRights();
            $tplData['uzivatel'] = $this->db->getLoggedUserData();
            $tplData['clanky'] = $this->db->getAllClanky();
            $tplData['recenzenti'] = $this->db->vyberRecenzenty();
            //$tplData['recenzujici'] = $this->db->getAllRecenzujiciClanky($tplData['clanky'], $tplData['recenzenti']);
        }

        if($tplData['prihlasen']){
            //// vypsani prislusne sablony
            // zapnu output buffer pro odchyceni vypisu sablony
            ob_start();
            // pripojim sablonu, cimz ji i vykonam
            require("app/Views/spravaClankuTemplate.tpl.php");
            // ziskam obsah output bufferu, tj. vypsanou sablonu
            $obsah = ob_get_clean();

            // vratim sablonu naplnenou daty
            return $obsah;
        }else{
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