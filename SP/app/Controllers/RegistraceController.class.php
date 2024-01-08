<?php
// nactu rozhrani kontroleru
require_once("app/Controllers/IController.interface.php");
// pripojim objekt pro spolecny kod
require("app/Controllers/BaseController.class.php");
/**
 * Ovladac zajistujici vypsani stranky s Registraci uzivatelu
 */
class RegistraceController extends BaseController implements IController {
    /**
     * Inicializace pripojeni k databazi.
     */
    public function __construct() {
        //konstruktor
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
        //naplnime vstupni data jako je titulek stranky a zjisteni jestli je uzivatel prihlasen atd.
        $this->naplnVstupniData($pageTitle);
        //podivame se jestli nekdo zaslal dotaz a pokud ano tak ho vlozime do tabulky DOTAZ
        $this->priselDotaz();
        //registrace
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
        //testujeme jestli se nekdo chce prihlasit a zda se jedna opravdu o uzivatele ktery se prihlasuje
        // a kdyz ano tak ho prihlasime. zahrnuje testovani i pro odhlaseni
        $obsah = $this->prihalsOdhlasUzivatele('app/Views/DomaciStrankaTemplate.tpl.php', 'app/Views/IntroductionTemplate.tpl.php');
        //obnoveni tplData
        $tplData = $this->tplData;
        //pokud je obsah ruzny od null tak se prihlasujeme nebo odhlasujeme
        if($obsah != null ) {
            return $obsah;
        }
        //rozlisujeme jaky controller bude vyuzit kdyz je uzivatel prihlasen nebo odhlasen
        $obsah =  $this->rozpoznejPrihlasenehoOdhlaseneho('app/Views/OsobniUdajeTemplate.tpl.php','app/Views/RegistraceTemplate.tpl.php');
        $tplData = $this->tplData;
        return $obsah;
    }
}
