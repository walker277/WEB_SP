
<?php
// nactu rozhrani kontroleru
require_once("app/Controllers/IController.interface.php");
// pripojim objekt pro spolecny kod
require("app/Controllers/BaseController.class.php");
/**
 * Ovladac zajistujici vypsani stranky se spravou uzivatelu.
 */
class mojeClankyController extends BaseController implements IController {

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
        $tplData['uzivatele'] = $this->db->getAllUsers();
        $tplData['clanky'] = $this->db->getAllClanky();
        $tplData['uzivatele'] = $this->db->getAllUsers();*/


        //otestovani jesli mame dotaz
        /*if(isset($_POST['odeslano'])){
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

        $this->tplData['pocetSpoluAutoru'] = 0;
        if(isset($_POST['zadano'])){
            if(isset($_POST['pocetAutoru']) && $_POST['pocetAutoru'] != ""){
                $pocetUzivatelu = $this->db->getPocetAutoru($this->db->getAllUsers());
                if( $_POST['pocetAutoru']<= ($pocetUzivatelu-1)){
                    $tplData['pocetSpoluAutoru'] = $_POST['pocetAutoru'];
                }else{
                    echo "<script>alert('ERORR: zadal jste vice autoru nez je v databazi');</script>";
                }
            }
        }

        if($this->db->isUserLogged()){
            $tplData['autori'][] = $this->db->getLoggedUserData()['jmeno_prijmeni'];
            $this->tplData['autori'][] = $tplData['autori'];
            $tplData['autoriU'][] = $this->db->getLoggedUserData();
            $this->tplData['autoriU'][] = $tplData['autoriU'];
        }

        //zpracovani formularu
        if(isset($_POST['pridat'])){
            if(isset($_POST['autor']) && $_POST['autor'] != ""){

                foreach ($_POST['autor'] as $aI) {
                    var_dump($aI);
                    $tplData['autoriU'][] = $this->db->getUserById($aI);
                    $tplData['autori'][] = $this->db->getUserById($aI)['jmeno_prijmeni'];
                }
                // Počet výskytů každého jména
                $pocetVyskytu = array_count_values($tplData['autori']);
                // Najdi duplikáty
                $duplikaty = array_filter($pocetVyskytu, function($value) {
                    return $value > 1;
                });
                if (count($duplikaty) > 0) {
                    echo "<script>alert('ERORR: zadal jste duplicitni autory');</script>";
                    $tplData['autori'] = null;
                    $tplData['autoriU'] = null;
                    $tplData['autori'][] = $this->db->getLoggedUserData()['jmeno_prijmeni'];
                    $tplData['autoriU'][] = $this->db->getLoggedUserData();
                } else {
                    echo "<script>alert('OK: autori byli pridani');</script>";

                }
            }
        }



        //zpracovani formularu
        if(isset($_POST['potvrdit'])){
            //mam vsechny pozadovane hodnoty?
            if( isset($_POST['clanek']) && isset($_FILES['soubor']) && isset($_POST['abstrakt']) && isset($_POST['uziv']) && isset($_POST['autoriJm']) &&
                ($_POST['clanek'] != "") && ($_FILES['soubor'] != "") && ($_POST['abstrakt'] != "") && ($_POST['uziv'] != "") && ($_POST['autoriJm'] != "")){
                $fileName = $_FILES['soubor']['name'];
                $fileType = $_FILES['soubor']['type'];
                $fileExt = explode('.',$fileName);
                $fileSize = $_FILES['soubor']['size'];
                //Pro případ že přípona je velkými převedeme na malé
                $fileActualExt = strtolower(end($fileExt));
                if($fileActualExt === 'pdf' && $fileType === 'application/pdf'){//test typu souboru
                    if($fileSize < 1000000){//test velikosti souboru
                        $cesta = 'soubory/' .basename($_FILES["soubor"]["name"]);
                        if (move_uploaded_file($_FILES["soubor"]["tmp_name"], $cesta)) {
                            echo "<script>alert('OK: soubor byl nahran');</script>";
                            $res = $this->db->addNewClanek($_POST['clanek'], $cesta, $_POST['abstrakt'], $_POST['autoriJm']);
                            //byl vlozen?
                            if($res){
                                $clanekID = $this->db->getPosledniClanek()['idCLANEK'];
                                //musime vytvorit vztahy mezi uzivateli clanky pro vsechny autory
                                var_dump($_POST['uziv']);
                                foreach ($_POST['uziv'] as $uI){
                                    //string je potreba prevest na int abychom ziskali uzivatele
                                    $u = $this->db->getUserById(intval($uI));
                                    //var_dump($u['id_uzivatel']);
                                    $this->db->addNewClankyAutora($clanekID, $u['id_uzivatel']);
                                }
                                echo "<script>alert('OK: Článek byl přidán do databáze');</script>";
                            }else{
                                echo "<script>alert('ERROR: Vložení článku do databáze se nezdařilo');</script>";
                            }
                        } else {
                            echo "<script>alert('ERORR: nahravani souboru selhalo');</script>";
                        }
                    }else{
                        echo "<script>alert('ERROR: Soubor je moc velký!!!');</script>";
                    }
                }else{
                    echo "<script>alert('ERROR: Soubor není typu pdf!!!');</script>";
                }
            }else{ //nemame vsechny atributy
                echo "<script>alert('ERROR: Nebyly přijaty požadované atributy uživatele');</script>";
            }
        }
        // pozadavek na odstraneni
        if(isset($_POST['odstran']) && $_POST['odstran'] != "" && isset($_POST['clanekS']) && $_POST['clanekS'] != "" ){
            //mažeme článek odstranit
            $this->db->odstranClanek($_POST['clanekS']);
        }

        // pozadavek na upravu
        if(isset($_POST['uprav']) && $_POST['uprav'] != "" && isset($_POST['clanekU']) && $_POST['clanekU'] != "" ){
            //zmenil uzivatel název
            if( isset($_POST['clanek']) && ($_POST['clanek'] != "")  ){
                if(!$this->db->updateClanekNazev($_POST['clanek'],$_POST['clanekU'])){
                    echo "<script>alert('ERROR: nazev se nepodarilo upravit');</script>";
                }

            }
            //zmenil uzivatel cestu k souboru
            $fileName = $_FILES['soubor']['name'];
            $fileType = $_FILES['soubor']['type'];
            $fileExt = explode('.',$fileName);
            $fileSize = $_FILES['soubor']['size'];
            //Pro případ že přípona je velkými převedeme na malé
            $fileActualExt = strtolower(end($fileExt));
            if($fileActualExt === 'pdf' && $fileType === 'application/pdf'){//test typu souboru
                if($fileSize < 1000000){//test velikosti souboru
                    $cesta = 'soubory/' .basename($_FILES["soubor"]["name"]);
                    if (move_uploaded_file($_FILES["soubor"]["tmp_name"], $cesta)) {
                        echo "<script>alert('OK: soubor byl nahran');</script>";
                        if(!$this->db->updateClanekSoubor($cesta,$_POST['clanekU'])){
                            echo "<script>alert('ERROR: cestu se nepodarilo upravit');</script>";
                        }
                    }else{
                        echo "<script>alert('ERORR: nahravani souboru selhalo');</script>";
                    }
                }else{
                    echo "<script>alert('ERROR: Soubor je moc velký!!!');</script>";
                }
            }

            //zmenil uzivatel abstrakt
            if (isset($_POST['abstrakt']) && ($_POST['abstrakt'] != "")){
                if(!$this->db->updateClanekAbstrakt($_POST['abstrakt'],$_POST['clanekU'])){
                    echo "<script>alert('ERROR: abstrakt se nepodarilo upravit');</script>";
                }
            }
        }


        //ulozeni stavu prihlaseni abychom mohli v sablone rozlisovat hlavicky
        /*$tplData['prihlasen'] = $this->db->isUserLogged();
        if($tplData['prihlasen']) {
            $tplData['uzivatele'] = $this->db->getAllUsers();
            $tplData['id_pravo'] = $this->db->getLoggedUserData()['id_pravo'];
            $tplData['prihlasenyID'] = $this->db->getLoggedUserData()['id_uzivatel'];
            $tplData['prihlasenyJmeno'] = $this->db->getLoggedUserData()['jmeno_prijmeni'];
            $tplData['clankyAutoru'] = $this->db->getAllClankyAutoru();
            $tplData['clanky'] = $this->db->getAllClanky();
            $idUzivatelovoClanku = $this->db->getAllAutoroviClankyID($tplData['prihlasenyID'],$tplData['clankyAutoru']);
            $tplData['autoroviClanky'] = $this->db->getAllAutoroviClanky($idUzivatelovoClanku, $tplData['clanky']);
            $tplData['idAutoruClanku'] = $this->db->getAllUzivIdClanku($idUzivatelovoClanku, $tplData['clankyAutoru']);
            $tplData['autoriClanku'] = $this->db->getAllUzivClanku($tplData['idAutoruClanku'],$this->db->getAllUsers());
        }
        //Pokud je uzivatel prihlasen zobrazime sablonu ktera je urcena prihlasenym uzivatelum
        if($this->db->isUserLogged()){
            //// vypsani prislusne sablony
            // zapnu output buffer pro odchyceni vypisu sablony
            ob_start();
            // pripojim sablonu, cimz ji i vykonam
            require("app/Views/mojeClankyTemplate.tpl.php");
            // ziskam obsah output bufferu, tj. vypsanou sablonu
            return ob_get_clean();
        } else{
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

        $obsah =  $this->rozpoznejPrihlasenehoOdhlaseneho('app/Views/mojeClankyTemplate.tpl.php','app/Views/IntroductionTemplate.tpl.php');


        return $obsah;
    }
}