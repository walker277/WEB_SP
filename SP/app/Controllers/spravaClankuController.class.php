<?php
// nactu rozhrani kontroleru
require_once("app/Controllers/IController.interface.php");
// pripojim objekt pro spolecny kod
require("app/Controllers/BaseController.class.php");
/**
 * Ovladac zajistujici vypsani stranky se spravou uzivatelu.
 */
class spravaClankuController extends BaseController implements IController {
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
        $tplData = $this->tplData;
        //rozlisujeme jaky controller bude vyuzit kdyz je uzivatel prihlasen nebo odhlasen
        $obsah =  $this->rozpoznejPrihlasenehoOdhlaseneho('app/Views/spravaClankuTemplate.tpl.php','app/Views/IntroductionTemplate.tpl.php');
        $tplData = $this->tplData;
        return $obsah;
    }

}
