<?php
//// vypis sablony
// urceni globalnich promennych, se kterymi sablona pracuje
global $tplData;
// pripojim objekt pro vypis hlavicky a paticky HTML
require("app/Views/TemplateBasics.class.php");
$tplHeaders = new TemplateBasics();

?>
<!-- ------------------------------------------------------------------------------------------------------- -->

<!-- Vypis obsahu sablony -->
<?php
// hlavicka
if($tplData['prihlasen']){
    $tplHeaders->getHTMLHeaderPrihlasen($tplData['title'],$tplData['id_pravo']);
}else{
    $tplHeaders->getHTMLHeader($tplData['title']);
}
?>
<div class="lingrad">
    <div>
    <div class="container-fluid">
        <h2 class="py-3 font-weight-bold text-primary">Přidělené recenze</h2>
        <?php

        $counter = 0;
        $navyseni = 0;
        foreach ($tplData['clanky'] as $aC){
           //postupne projedeme pres vsechny recenzenty a otestujeme jestli prihlaseny recenzuje clanek


           if($aC['recenzent_1'] != 0 && $tplData['uzivatel']['id_uzivatel'] == $aC['recenzent_1']){
               $jeRecenzentemClanku = true;
           }else if($aC['recenzent_2'] != 0 && $tplData['uzivatel']['id_uzivatel'] == $aC['recenzent_2']) {
               $jeRecenzentemClanku = true;
           } else if($aC['recenzent_3'] != 0 && $tplData['uzivatel']['id_uzivatel'] == $aC['recenzent_3']) {
               $jeRecenzentemClanku = true;
           }else{
               $jeRecenzentemClanku = false;
           }

           if(!$jeRecenzentemClanku){
               continue;
           }

            $counter++;
            $navyseni++;
            $idForm = 'form'.$navyseni;
            $Form = '#'.$idForm;
            $idDiv = 'pdfdiv'.$navyseni;
            $divId = '#'.$idDiv
    ?>
            <div class="py-3">
                <div class="card">
                    <div class="card-header text-center bg-warning">
                        <?php
                        $nazev = $aC['nazev'];
                        echo "<h3 class='text-primary font-weight-bold'>$nazev</h3>";
                        ?>
                    </div>
                    <div class="card-body text-justify bg-secondary" >
                        <!-- zobrazeni pro vetsi jak md-->
                        <div class="d-none d-md-block">

                            <div class="row text-left ">
                                <div class="col-xl-6 col-lg-6 col-md-6 text-left mt-3 ">
                                    <?php
                                    if ($aC['schvalen'] == 0){
                                        echo "<input type='text' class='bg-info text-light text-center rounded-pill font-weight-bold border-info' value='Čeká na posouzení' readonly>";
                                    }elseif($aC['schvalen'] == 1){
                                        echo "<input type='text' class='bg-danger text-light rounded-pill text-center border-danger font-weight-bold' value='Článek je zamítnutý' readonly>";
                                    }else{
                                        echo "<input type='text' class='bg-success rounded-pill border-success text-center text-light font-weight-bold' value='Článek je povolený' readonly>";
                                    }
                                    ?>
                                </div>
                            </div>

                            <div class="row text-center mt-3">
                                <div class="form-group col-12 text-left">
                                    <?php
                                    $text1 = 'Čeká na přidělení recenzentů';
                                    $text2 = 'Recenzují: ';
                                    if($aC['recenzent_1'] != 0 ){
                                        foreach ($tplData['uzivatele'] as $u){
                                            if($u['id_uzivatel'] == $aC['recenzent_1'] ){
                                                $text2 = $text2.$u['jmeno_prijmeni'];
                                                if($aC['hodnoceni_1'] == 0){
                                                    if($u['pohlavi'] === 'zena'){
                                                        $text2 = $text2.' (zatím nehodnotila)';
                                                    }else{
                                                        $text2 = $text2.' (zatím nehodnotil)';
                                                    }
                                                }else{
                                                    $hodnoceni = $aC['hodnoceni_1'];
                                                    if($u['pohlavi'] === 'zena'){
                                                        $text2 = $text2.': ohodnotila '. $hodnoceni. ' body';
                                                    }else{
                                                        $text2 = $text2.': ohodnotil '. $hodnoceni. ' body';
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    if($aC['recenzent_2'] != 0 ){
                                        foreach ($tplData['uzivatele'] as $u){
                                            if($u['id_uzivatel'] == $aC['recenzent_2'] ){
                                                $text3 = ', '.$u['jmeno_prijmeni'];
                                                $text2 = $text2.$text3;
                                                if($aC['hodnoceni_2'] == 0){
                                                    if($u['pohlavi'] === 'zena'){
                                                        $text2 = $text2.' (zatím nehodnotila)';
                                                    }else{
                                                        $text2 = $text2.' (zatím nehodnotil)';
                                                    }
                                                }else{
                                                    $hodnoceni = $aC['hodnoceni_2'];
                                                    if($u['pohlavi'] === 'zena'){
                                                        $text2 = $text2.': ohodnotila '. $hodnoceni. ' body';
                                                    }else{
                                                        $text2 = $text2.': ohodnotil '. $hodnoceni. ' body';
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    if($aC['recenzent_3'] != 0 ){
                                        foreach ($tplData['uzivatele'] as $u){
                                            if($u['id_uzivatel'] == $aC['recenzent_3'] ){
                                                $text4 = ', '.$u['jmeno_prijmeni'];
                                                $text2 = $text2.$text4;
                                                if($aC['hodnoceni_3'] != 0){
                                                    $hodnoceni = $aC['hodnoceni_3'];
                                                    if($u['pohlavi'] === 'zena'){
                                                        $text2 = $text2.': ohodnotila '. $hodnoceni. ' body';
                                                    }else{
                                                        $text2 = $text2.': ohodnotil '. $hodnoceni. ' body';
                                                    }
                                                }else{
                                                    if($u['pohlavi'] === 'zena'){
                                                        $text2 = $text2.' (zatím nehodnotila)';
                                                    }else{
                                                        $text2 = $text2.' (zatím nehodnotil)';
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    if($aC['recenzent_1'] == 0 && $aC['recenzent_2'] == 0 && $aC['recenzent_3'] == 0 ){
                                        echo "<p class='border-dark text-center text-dark text-justify font-weight-bold bg-danger'>$text1</p>";
                                    }else{
                                        echo "<p class='border-dark text-dark text-center text-justify font-weight-bold bg-info'> $text2</p>";
                                    }
                                    ?>
                                </div>
                            </div>

                            <div class="row text-left mt-3">
                                <div class="col-12">
                                    <?php
                                    echo "<label class='font-weight-bold'>$aC[autori]</label>";
                                    ?>
                                </div>
                            </div>
                            <div class="row text-left mt-3">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <?php
                                    if( ($aC['recenzent_1'] == $tplData['uzivatel']['id_uzivatel'] && $aC['komentar_1'] == "" && $aC['hodnoceni_1'] == 0) ||
                                        ($aC['recenzent_2'] == $tplData['uzivatel']['id_uzivatel'] && $aC['komentar_2'] == "" && $aC['hodnoceni_2'] == 0) ||
                                        ($aC['recenzent_3'] == $tplData['uzivatel']['id_uzivatel'] && $aC['komentar_3'] == "" && $aC['hodnoceni_3'] == 0)){
                                        $uprava = false;
                                        echo "<button class='btn btn-primary' data-toggle='collapse' data-target=$Form>Recenzovat</button>";
                                    }else{
                                        $uprava = true;
                                        echo "<button class='btn btn-primary' data-toggle='collapse' data-target=$Form>Upravit Recenzi</button>";
                                    }
                                    ?>
                                </div>
                                <div class="text-right col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <?php
                                    $cesta = $aC['cesta'];
                                    // \ použity aby ' byl součástí řetězce
                                    echo '<button class="btn btn-primary" onclick="window.open(\'' . $cesta . '\', \'_blank\')">Stáhnout PDF článku</button>';
                                    ?>
                                </div>
                            </div>
                        </div>


                        <!-- zobrazeni pro mensi jak md-->
                        <div class="d-md-none">
                            <div class="row text-center ">
                                <div class="col-12 text-center mt-3 ">
                                    <?php
                                    if ($aC['schvalen'] == 0){
                                        echo "<input type='text' class='bg-info text-light text-center rounded-pill font-weight-bold border-info' value='Čeká na posouzení' readonly>";
                                    }elseif($aC['schvalen'] == 1){
                                        echo "<input type='text' class='bg-danger text-light rounded-pill text-center border-danger font-weight-bold' value='Článek je zamítnutý' readonly>";
                                    }else{
                                        echo "<input type='text' class='bg-success rounded-pill border-success text-center text-light font-weight-bold' value='Článek je povolený' readonly>";
                                    }
                                    ?>
                                </div>
                                <div class="col-12 mt-3">

                                </div>
                            </div>
                            <div class="row text-center mt-3">
                                <div class="col-12">
                                    <?php
                                    echo "<label class='font-weight-bold'>$aC[autori]</label>";
                                    ?>
                                </div>
                            </div>
                            <div class="row text-center mt-3">
                                <div class="col-12">
                                    <?php
                                    echo "<button class='btn btn-primary' data-toggle='collapse' data-target=$Form>Napsat recenzi</button>";
                                    ?>
                                </div>
                            </div>
                            <div class="row text-center mt-3">
                                <div class="col-12">
                                    <?php
                                    // \ použity aby ' byl součástí řetězce
                                    echo '<button class="btn btn-primary" onclick="window.open(\'' . $aC['cesta'] . '\', \'_blank\')">Stáhnout PDF článku</button>';
                                    ?>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="row text-left">
                            <div class="form-group col-12">
                                <label class="font-weight-bold">abstrakt:</label>
                                <?php
                                $abstrakt = $aC['abstrakt'];
                                echo"<p class='text-light'>$abstrakt</p>";
                                ?>
                            </div>
                        </div>
                       <!-- <div class="row text-center mt-3">-->
                            <?php
                            if($uprava){
                                $required = '';
                            }else{
                                $required = 'required';
                            }
                            echo
                            "
                                <div class=' col-12 collapse bg-secondary' id=$idForm>
                                    <form action='' method='post'>
                                        <div hidden=''>
                                            <input type='number' name='clanekU' value=$aC[idCLANEK]>
                                        </div>
                                        <div class='row'>
                                            <div class='text-center col-12'>
                                            <label for='hodnoceni' class='font-weight-bold'>bodové ohodnocení
                                                <input type='number' min='1' max='10' class='form-control'  name='hodnoceni' $required>
                                            </label>
                                            </div>
                                        </div>
                                        <div class='row'>
                                             <div class='form-group col-12'>
                                                <label for='editor1' class='font-weight-bold'>písemné vyjádření: </label>
                                                <span class='input-group'>
                                                    <span class='input-group-text fa-pencil'></span>
                                                    <textarea class='form-control' rows='4' name='editor1' $required></textarea>
                                                </span>
                                            </div>
                                        </div>
                                        <div class='row'>
                                            <div class='text-center col-12'>
                                                <button type='submit' class='btn btn-primary' name='recenzovat'>potvrdit</button>
                                            </div>
                                        </div>
                                        
                                   </form>
                                </div>";
                        if($uprava){
                           echo"<div class='row text-justify text-center'>
                                    <div class='col-12'>
                                    <label class='font-weight-bold'>recenze:</label>";
                                     if( $aC['recenzent_1'] == $tplData['uzivatel']['id_uzivatel']){
                                            echo "<p class='text-light'>$aC[komentar_1]</p>";
                                     } else if($aC['recenzent_2'] == $tplData['uzivatel']['id_uzivatel']){
                                            echo "<p class='text-light'>$aC[komentar_2]</p>";
                                     }else{
                                            echo "<p class='text-light'>$aC[komentar_3]</p>";
                                     }
                        }
                     echo"   </div>
                    </div>";

                    ?>

                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
    </div>
</div>
<?php
// paticka
$tplHeaders->getHTMLFooter()

?>



