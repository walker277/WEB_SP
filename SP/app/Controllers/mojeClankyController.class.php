
<?php
// nactu rozhrani kontroleru
require_once("app/Controllers/IController.interface.php");
// pripojim objekt pro spolecny kod
require("app/Controllers/BaseController.class.php");
/**
 * Ovladac zajistujici vypsani stranky se spravou uzivatelu.
 */
class mojeClankyController extends BaseController implements IController {
    /**
     * Inicializace pripojeni k databazi.
     */
    public function __construct() {
        //konstruktor predka
        parent::__construct();
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
        //naplnime vstupni data jako je titulek stranky a zjisteni jestli je uzivatel prihlasen atd.
        $this->naplnVstupniData($pageTitle);
        //podivame se jestli nekdo zaslal dotaz a pokud ano tak ho vlozime do tabulky DOTAZ
        $this->priselDotaz();
        //testujeme jestli se nekdo chce prihlasit a zda se jedna opravdu o uzivatele ktery se prihlasuje
        // a kdyz ano tak ho prihlasime. zahrnuje testovani i pro odhlaseni
        $obsah = $this->prihalsOdhlasUzivatele('app/Views/DomaciStrankaTemplate.tpl.php', 'app/Views/IntroductionTemplate.tpl.php');
        //obnoveni tplData
        $tplData = $this->tplData;
        //pokud je obsah ruzny od null tak se prihlasujeme nebo odhlasujeme
        if($obsah != null ) {
            return $obsah;
        }
        //pokud uzivatel zada tak si ulozime pocet spoluAutoru a vypiseme
        $this->tplData['pocetSpoluAutoru'] = 0;
        if(isset($_POST['zadano'])){
            if(isset($_POST['pocetAutoru']) && $_POST['pocetAutoru'] != ""){
                $pocetUzivatelu = $this->db->getPocetAutoru($this->db->getAllUsers());
                if( $_POST['pocetAutoru']<= ($pocetUzivatelu-1)){
                    $tplData['pocetSpoluAutoru'] = $_POST['pocetAutoru'];
                    $this->tplData['pocetSpoluAutoru'] = $_POST['pocetAutoru'];
                }else{
                    echo "<script>alert('ERORR: zadal jste vice autoru nez je v databazi');</script>";
                }
            }
        }

        if($this->db->isUserLogged()){
            $tplData['autori'][] = $this->db->getLoggedUserData()['jmeno_prijmeni'];
            $this->tplData['autori'][] = $this->db->getLoggedUserData()['jmeno_prijmeni'];
            $tplData['autoriU'][] = $this->db->getLoggedUserData();
            $this->tplData['autoriU'][] = $this->db->getLoggedUserData();
        }

        //zpracovani formularu
        if(isset($_POST['pridat'])){
            if(isset($_POST['autor']) && $_POST['autor'] != ""){
                //jdeme pres vsechny autory
                foreach ($_POST['autor'] as $aI) {
                    $tplData['autoriU'][] = $this->db->getUserById($aI);
                    $this->tplData['autoriU'][] = $this->db->getUserById($aI);
                    $tplData['autori'][] = $this->db->getUserById($aI)['jmeno_prijmeni'];
                    $this->tplData['autori'][] = $this->db->getUserById($aI)['jmeno_prijmeni'];
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
                    $this->tplData['autori'] = null;
                    $tplData['autoriU'] = null;
                    $this->tplData['autoriU'] = null;
                    $tplData['autori'][] = $this->db->getLoggedUserData()['jmeno_prijmeni'];
                    $this->tplData['autori'][] = $this->db->getLoggedUserData()['jmeno_prijmeni'];
                    $tplData['autoriU'][] = $this->db->getLoggedUserData();
                    $this->tplData['autoriU'][] = $this->db->getLoggedUserData();
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
                            //pridame clanek
                            $res = $this->db->addNewClanek($_POST['clanek'], $cesta, $_POST['abstrakt'], $_POST['autoriJm']);
                            //byl vlozen?
                            if($res){
                                $clanekID = $this->db->getPosledniClanek()['idCLANEK'];
                                //musime vytvorit vztahy mezi uzivateli clanky pro vsechny autory
                                foreach ($_POST['uziv'] as $uI){
                                    //string je potreba prevest na int abychom ziskali uzivatele
                                    $u = $this->db->getUserById(intval($uI));
                                    //vytvorime vztah mezi clankem a autorem
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
        $tplData = $this->tplData;
        //rozlisujeme jaky controller bude vyuzit kdyz je uzivatel prihlasen nebo odhlasen
        $obsah =  $this->rozpoznejPrihlasenehoOdhlaseneho('app/Views/mojeClankyTemplate.tpl.php','app/Views/IntroductionTemplate.tpl.php');
        $tplData = $this->tplData;
        return $obsah;
    }
}