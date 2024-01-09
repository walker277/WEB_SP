<?php
// nactu rozhrani kontroleru
require_once("app/Controllers/IController.interface.php");
// pripojim objekt pro spolecny kod
require("app/Controllers/BaseController.class.php");
/**
 * Ovladac zajistujici vypsani stranky s Osobnimi udaji uzivatele
 */
class OsobniUdajeController extends BaseController implements IController {

    /**
     * Inicializace pripojeni k databazi.
     */
    public function __construct() {
        //konstruktor predka
        parent::__construct();
    }

    /**
     * Vrati obsah strany s Osobnimi udaji.
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
        if($tplData['prihlasen']){
            $user = $this->db->getLoggedUserData();
            $tplData['nazevUzivatelovaPrava'] = $this->db->getRightById($user['id_pravo'])['nazev'];
            $this->tplData['nazevUzivatelovaPrava'] = $this->db->getRightById($user['id_pravo'])['nazev'];
        }

        //zpracovani odeslanych formularu
        if(isset($_POST['potvrzeni'])){
            $user = $this->db->getLoggedUserData();
            $UzivID = $user['id_uzivatel'];
            //mam vsechny pozadovane hodnoty?
            if( (isset($_POST['email']) && ($_POST['email'] != "")) ){
                $res = $this->db->updateUserEmail($UzivID, $_POST['email']);
                if($res){
                    echo "<script>alert('OK: Uživatel byl upraven.');</script>";
                }else{
                    echo "<script>alert('ERROR: Upravení uživatele se nezdařilo');</script>";
                }
            }elseif ( (isset($_POST['username']) && ($_POST['username'] != "")) ){
                if($this->db->jeUsernameVolne($_POST['username'],$this->db->getAllUsers())) {
                    $res = $this->db->updateUsername($UzivID, $_POST['username']);
                    if ($res) {
                        echo "<script>alert('OK: Uživatel byl upraven.');</script>";
                    } else {
                        echo "<script>alert('ERROR: Upravení uživatele se nezdařilo');</script>";
                    }
                }else{
                    echo "<script>alert('ERROR: Uzivatelske jmeno je zabrane, a proto si zvolte jine');</script>";
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
        $tplData = $this->tplData;
        //rozlisujeme jaky controller bude vyuzit kdyz je uzivatel prihlasen nebo odhlasen
        $obsah =  $this->rozpoznejPrihlasenehoOdhlaseneho('app/Views/OsobniUdajeTemplate.tpl.php','app/Views/IntroductionTemplate.tpl.php');
        $tplData = $this->tplData;
        return $obsah;
    }
}
