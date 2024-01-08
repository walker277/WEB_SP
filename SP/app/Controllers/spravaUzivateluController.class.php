<?php
// nactu rozhrani kontroleru
require_once("app/Controllers/IController.interface.php");
// pripojim objekt pro spolecny kod
require("app/Controllers/BaseController.class.php");
/**
 * Ovladac zajistujici vypsani stranky se spravou uzivatelu.
 */
class spravaUzivatelu extends BaseController implements IController {
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
        //obnovime tplData
        $tplData = $this->tplData;
        //pokud je obsah ruzny od null tak se prihlasujeme nebo odhlasujeme
        if($obsah != null ) {
            return $obsah;
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
        $tplData = $this->tplData;
        //rozlisujeme jaky controller bude vyuzit kdyz je uzivatel prihlasen nebo odhlasen
        $obsah =  $this->rozpoznejPrihlasenehoOdhlaseneho('app/Views/spravaUzivateluTemplate.tpl.php','app/Views/IntroductionTemplate.tpl.php');
        $tplData = $this->tplData;
        return $obsah;
    }

}
