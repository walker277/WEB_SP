<?php
///////////////////////////////////////////////////////////////////////////
/////////// Sablona pro zobrazeni stranky se spravou uzivatelu  //////////


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

if($tplData['prihlasen']){
    $tplHeaders->getHTMLHeaderPrihlasen($tplData['title'],$tplData['id_pravo']);
}else{
    $tplHeaders->getHTMLHeader($tplData['title']);
}
?>
<div class="lingrad">
    <div class="container-fluid">
        <h2 class="py-3 font-weight-bold text-primary">Zveřejněné články</h2>
        <?php

        $counter = 0;
        $navyseni = 0;
        foreach ($tplData['clanky'] as $aC){
           //je článek schvalen
           if($aC['schvalen'] !=  2){
               continue;
           }

            $counter++;
            $navyseni++;

            $idDiv = 'pdfdiv'.$navyseni;
            $divId = '#'.$idDiv;

            $idRecenze = 'recenze' .$navyseni;
            $recenzeId = '#'.$idRecenze;
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
                                                if($u['pohlavi'] === 'zena'){
                                                    $hodnoceni = $aC['hodnoceni_1'];
                                                    $text2 = $text2.': ohodnotila '. $hodnoceni. ' body';
                                                }else{
                                                    $hodnoceni = $aC['hodnoceni_1'];
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
                                                if($u['pohlavi'] === 'zena'){
                                                    $hodnoceni = $aC['hodnoceni_2'];
                                                    $text2 = $text2.': ohodnotila '. $hodnoceni. ' body';
                                                }else{
                                                    $hodnoceni = $aC['hodnoceni_2'];
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
                                                if($u['pohlavi'] === 'zena'){
                                                    $hodnoceni = $aC['hodnoceni_3'];
                                                    $text2 = $text2.': ohodnotila '. $hodnoceni. ' body';
                                                }else{
                                                    $hodnoceni = $aC['hodnoceni_3'];
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

                        <!-- zobrazeni pro vetsi jak md-->
                        <div class="d-none d-md-block">
                            <div class="row text-left mt-3">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <?php
                                    echo "<label class='font-weight-bold'>$aC[autori]</label>";
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
                                    $cesta = $aC['cesta'];
                                    // \ použity aby ' byl součástí řetězce
                                    echo '<button class="btn btn-primary" onclick="window.open(\'' . $cesta . '\', \'_blank\')">Stáhnout PDF článku</button>';
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="row text-left mt-3">
                            <div class="form-group col-12">
                                <label class="font-weight-bold">abstrakt:</label>
                                <?php
                                $abstrakt = $aC['abstrakt'];
                                echo"<p class='text-light'>$abstrakt</p>";
                                ?>
                            </div>
                        </div>
                        <hr>
                        <div class="row text-left mt-3">
                            <div class=" text-center col-12">
                                <?php
                                echo "<button class='btn btn-link text-light font-weight-bold' data-toggle='collapse' data-target=$recenzeId>Zobrazit recenze</button>";
                                ?>
                            </div>
                        </div>
                    <?php
                    echo "<div class='row text-left mt-3 collapse' id=$idRecenze>";
                    ?>
                        <div class="text-justify col-12 mt-2">
                        <?php
                            if ($aC['recenzent_1'] != 0){
                                foreach ($tplData['uzivatele'] as $u){
                                    if($u['id_uzivatel'] == $aC['recenzent_1']){
                                        $jmeno = $u['jmeno_prijmeni'] . ':';
                                    }
                                }
                                echo "<label class='font-weight-bold'>$jmeno</label>
                                 <p class='text-light'>$aC[komentar_1]</p>";
                            }
                            if ($aC['recenzent_2'] != 0){
                                foreach ($tplData['uzivatele'] as $u){
                                    if($u['id_uzivatel'] == $aC['recenzent_2']){
                                        $jmeno = $u['jmeno_prijmeni'] . ':';
                                    }
                                }
                                echo "<label class='font-weight-bold'>$jmeno</label>
                                 <p class='text-light'>$aC[komentar_2]</p>";
                            }
                            if ($aC['recenzent_3'] != 0){
                                foreach ($tplData['uzivatele'] as $u){
                                    if($u['id_uzivatel'] == $aC['recenzent_3']){
                                        $jmeno = $u['jmeno_prijmeni'] . ':';
                                    }
                                }
                                echo "<label class='font-weight-bold'>$jmeno</label>
                                 <p class='text-light'>$aC[komentar_3]</p>";
                            }
                        ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>
<?php
// paticka
$tplHeaders->getHTMLFooter()

?>