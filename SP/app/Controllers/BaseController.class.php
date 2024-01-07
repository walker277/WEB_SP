<?php

/**
 * Predek vsech controlleru, slouzi primarne pro zprehledneni kodu, jelikoz se kod mezi controllery casto opakoval
 */
class BaseController{
    /** @var DatabaseModel $db  Sprava databaze. */
    protected DatabaseModel $db;
    /** @var array $tplData pro udrzovani dat ktere pote vyuzivame v sablonach pro ziskani dat a vetveni kodu */
    protected array $tplData = [];

    /**
     * Inicializace pripojeni k databazi.
     */
    public function __construct() {
        // inicializace prace s DB
        require_once ("app/Models/DatabaseModel.class.php");
        $this->db = new DatabaseModel();
    }

    /**
     * Ulozi do tplData potrebna data se kterymi se dale pracuje
     * @param string $pageTitle titulek stranky
     * @return void
     */
    public function naplnVstupniData(string $pageTitle):void {
        // nazev
        $this->tplData['title'] = $pageTitle;
        //ziskame vsechny clanky
        $this->tplData['clanky'] = $this->db->getAllClanky();
        //ziskame vsechny uzivatele
        $this->tplData['uzivatele'] = $this->db->getAllUsers();
        //ziskame zda je uzivatel prihlasen
        $this->tplData['prihlasen'] = $this->db->isUserLogged();
    }

    /**
     * Metoda zjisti jestli nekdo zaslal dotaz a pokud ano tak ho vlozi do tabulky DOTAZ
     * @return void
     */
    public function priselDotaz():void{
        //otestovani jestli neprisel dotaz
        if(isset($_POST['odeslano'])){
            if ( (isset($_POST["email"]) && $_POST["email"] != "") && (isset($_POST["jmeno"]) && $_POST["jmeno"] != "") && (isset($_POST["dotaz"]) && $_POST["dotaz"] != "")) {
                $this->db->addNewDotaz($_POST['email'], $_POST['jmeno'], $_POST['dotaz']);
            }
        }
    }

    /**
     * Slouzi k rozpoznani akce prihlaseni/odhlaseni a naslednemu vybrani spravne sablony podle ktere bude vykreslovat
     * @param string $controllerPrihlaseni sablona ktera se zobrazi po prihlaseni uzivatele
     * @param string $controllerOdhlaseni sablona ktera se zobrazi po odhlaseni uzivatele
     * @return false|string|null
     */
    public function prihalsOdhlasUzivatele( string $controllerPrihlaseni, string $controllerOdhlaseni): false|string|null
    {
        //globalni promena pro data
        global $tplData;
        // zpracovani odeslanych formularu na prihlaseni - mam akci?
        if(isset($_POST["action"])){
            // mam pozadavek na login ?
            if($_POST["action"] == "login") {
                // mam co ulozit?
                if ( (isset($_POST["username"]) && $_POST["username"] != "") && (isset($_POST["heslo1"]) && $_POST["heslo1"] != "") ) {
                    // prihlasim uzivatele zahrnuje porovnani hesla s hashem
                    $res = $this->db->userLogin($_POST['username'], $_POST['heslo1']);
                    //test jestli uzivatel zablokovan
                    if ($res && $this->db->getLoggedUserData()['Zablokovany'] == 1){
                        $this->db->userLogout();
                        $this->tplData['prihlasen'] = false;
                        echo "<script>alert('ERROR: Uživatel je zablokovaný');</script>";
                    }else if($res){
                        echo "<script>alert('OK: Uživatel byl přihlášen');</script>";
                        $this->tplData['prihlasen'] = true;
                        $this->tplData['id_pravo'] = $this->db->getLoggedUserData()['id_pravo'];
                        $tplData = $this->tplData;
                        //// vypsani prislusne sablony
                        // zapnu output buffer pro odchyceni vypisu sablony
                        ob_start();
                        // pripojim sablonu, cimz ji i vykonam
                        require($controllerPrihlaseni);
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
                    $this->tplData['prihlasen'] = false;
                    echo "<script>alert('OK: Uživatel byl odhlášen');</script>";
                    $tplData = $this->tplData;
                    //// vypsani prislusne sablony
                    // zapnu output buffer pro odchyceni vypisu sablony
                    ob_start();
                    // pripojim sablonu, cimz ji i vykonam
                    require($controllerOdhlaseni);
                    // ziskam obsah output bufferu, tj. vypsanou sablonu
                    return ob_get_clean();
                }
            }
            // neznamy pozadavek
            else {
                echo "<script>alert('Chyba: Nebyla rozpoznána požadovaná akce.');</script>";
                return null;
            }
        }
        return null;
    }

    /**
     * Metoda zjisti jestli je uzivatel prihlasen a podle toho nastavi tplData a
     * nasledne pripoji pozadovanou sablonu pro vykresleni.
     * @param $prihlasenyController - sablona pro prihlaseneho uzivatele
     * @param $odhlasenyController - sablona pro odhlaseneho uzivatele
     * @return false|string
     */
    public function rozpoznejPrihlasenehoOdhlaseneho($prihlasenyController, $odhlasenyController): false|string{
        global $tplData;
        $this->tplData['prihlasen'] = $this->db->isUserLogged();
        //je uzivatel prihlasen
        if($this->tplData['prihlasen']) {
            //ziskame pravo uzivatele
            $this->tplData['id_pravo'] = $this->db->getLoggedUserData()['id_pravo'];
            //vsechny clanky
            $this->tplData['clanky'] = $this->db->getAllClanky();
            //vsechny uzivatele
            $this->tplData['uzivatele'] = $this->db->getAllUsers();
            //uzivatelova data
            $user = $this->db->getLoggedUserData();
            //uzivatelovo id
            $UzivID = $user['id_uzivatel'];
            //nazev prava
            $this->tplData['nazevUzivatelovaPrava'] = $this->db->getRightById($user['id_pravo'])['nazev'];
            //uzivatelova data
            $this->tplData['uzivatel'] = $this->db->getLoggedUserData();
            //vsechna prava
            $this->tplData['prava'] = $this->db->getAllRights();
            //vsechny recenzenty
            $this->tplData['recenzenti'] = $this->db->vyberRecenzenty();
            //id uzivatele
            $this->tplData['prihlasenyID'] = $this->db->getLoggedUserData()['id_uzivatel'];
            //jmeno uzivatele
            $this->tplData['prihlasenyJmeno'] = $this->db->getLoggedUserData()['jmeno_prijmeni'];
            //vsechny clanky vsech autoru
            $this->tplData['clankyAutoru'] = $this->db->getAllClankyAutoru();
            //vsechny id clanku autora
            $idUzivatelovoClanku = $this->db->getAllAutoroviClankyID($this->tplData['prihlasenyID'],$this->tplData['clankyAutoru']);
            //vsechny autorovi clanky
            $this->tplData['autoroviClanky'] = $this->db->getAllAutoroviClanky($idUzivatelovoClanku, $this->tplData['clanky']);
            //vsechny id autoru clanku
            $this->tplData['idAutoruClanku'] = $this->db->getAllUzivIdClanku($idUzivatelovoClanku, $this->tplData['clankyAutoru']);
            //vsechny uzivatele clanku
            $this->tplData['autoriClanku'] = $this->db->getAllUzivClanku($this->tplData['idAutoruClanku'],$this->db->getAllUsers());
        }
        //Pokud je uzivatel prihlasen zobrazime sablonu ktera je urcena prihlasenym uzivatelum
        $tplData = $this->tplData;
        // zapnu output buffer pro odchyceni vypisu sablony
        ob_start();
        if($this->db->isUserLogged()){
            //// vypsani prislusne sablony
            // pripojim sablonu, cimz ji i vykonam
            require($prihlasenyController);
        } else{
            //// vypsani prislusne sablony
            // zapnu output buffer pro odchyceni vypisu sablony
            // pripojim sablonu, cimz ji i vykonam
            require($odhlasenyController);
        }
        // ziskam obsah output bufferu, tj. vypsanou sablonu
        $obsah = ob_get_clean();
        $tplData = $this->tplData;
        // vratim sablonu naplnenou daty
        return $obsah;
    }

}