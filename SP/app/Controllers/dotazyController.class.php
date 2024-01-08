<?php
// nactu rozhrani kontroleru
require_once("app/Controllers/IController.interface.php");
// pripojim objekt pro spolecny kod
require("app/Controllers/BaseController.class.php");

/**
 * Ovladac zajistujici vypsaní stranky s dotazy.
 */
class dotazyController extends BaseController implements IController {
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
     * Vrati obsah uvodni stranky.
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
        //pokud prisel dotaz tak ho odstranime
        if(isset($_POST['odstranD'])){
            $this->db->odstranDotaz($_POST['idDotaz']);
        }
        //obnoveni dat
        $tplData['dotazy'] = $this->db->getAllDotazy();
        $this->tplData['dotazy'] = $tplData['dotazy'];
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
        $obsah =  $this->rozpoznejPrihlasenehoOdhlaseneho('app/Views/dotazyTemplate.tpl.php','app/Views/IntroductionTemplate.tpl.php');
        $tplData = $this->tplData;
        return $obsah;
        }

}

