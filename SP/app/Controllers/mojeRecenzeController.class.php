<?php
// nactu rozhrani kontroleru
require_once("app/Controllers/IController.interface.php");
// pripojim objekt pro spolecny kod
require("app/Controllers/BaseController.class.php");
/**
 * Ovladac zajistujici vypsani stranky s pridelenymi recenzemi.
 */
class mojeRecenzeController extends BaseController implements IController {

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
        if(isset($_POST['recenzovat'])){
            //uložíme hodnocení
            if(isset($_POST['hodnoceni']) && $_POST['hodnoceni'] != ""){
                $this->db->updateHodnoceni($this->db->getClanekById($_POST['clanekU']), $this->db->getLoggedUserID(),$_POST['hodnoceni'] );
            }
            //uložíme komentář
            if(isset($_POST['editor1']) && $_POST['editor1'] != ""){
                $this->db->updateKomentare($this->db->getClanekById($_POST['clanekU']), $this->db->getLoggedUserID(),$_POST['editor1'] );
            }
        }
        $tplData = $this->tplData;
        //rozlisujeme jaky controller bude vyuzit kdyz je uzivatel prihlasen nebo odhlasen
        $obsah =  $this->rozpoznejPrihlasenehoOdhlaseneho('app/Views/mojeRecenzeTemplate.tpl.php','app/Views/IntroductionTemplate.tpl.php');
        $tplData = $this->tplData;
        return $obsah;
    }

}
