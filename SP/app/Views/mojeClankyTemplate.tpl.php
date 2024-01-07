<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
///////////////////////////////////////////////////////////////////////////
/////////// Sablona pro zobrazeni stranky se spravou uzivatelu  ///////////
///////////////////////////////////////////////////////////////////////////

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
<div class="container-fluid lingrad py-3">
    <h2 class="py-3 font-weight-bold text-primary">Vytvořené články</h2>
    <div class="text-center py-3">
        <button class="btn btn-dark font-weight-bold" data-toggle="collapse" data-target="#form">Vytvořit článek</button>
    </div>
    <div id="form" class="collapse py-3">
        <div class="container" >
            <div class="ohraniceniKarty">
                <div class="card" id="kartaPozadi">
                    <div class="card-header text-center">
                        <h3 class="text-primary">Vytvoření článku</h3>
                    </div>
                    <div class="card-body text-justify " >
                       <form action="" method="post">
                           <div class="row text-center">
                               <div class="form-group col-12">
                                   <label for="pocetA">Zadejte pocet spoluautorů
                                       <input type="text" name="pocetAutoru" required> (zadejte 0 nebo přeskočte pokud jste autorem pouze vy)
                                   </label>
                                   <button class='btn btn-outline-primary' type='submit' name='zadano' value='zadat'>potvrdit</button>
                               </div>
                           </div>
                       </form>
                        <form action="" method="post">
                            <?php
                            echo "<div class='row text-center'>";
                            $pocet = $tplData['pocetSpoluAutoru'];
                            while ($tplData['pocetSpoluAutoru'] != 0 ){
                                $tplData['pocetSpoluAutoru']--;
                                echo"<div class='form-group col-12'>
                                        <label for='autor'>Uveďte autory clanku
                                            <select name='autor[]'>";
                                foreach($tplData['uzivatele'] as $u){
                                    if($u['id_pravo'] == 4 && $u['Zablokovany'] == 0){
                                        $selected = ($u['id_pravo'] == $tplData['id_pravo']) ? "selected" : "";
                                        echo "<option value='$u[id_uzivatel]' selected=$selected>$u[jmeno_prijmeni] </option>";
                                    }
                                }
                                    echo"</select>
                                    </label>
                                </div>";
                                if($tplData['pocetSpoluAutoru'] == 0){
                                    echo"<div class='col-12 py-3'>
                                    <button class='btn btn-outline-primary' type='submit' name='pridat' value='pridat'>Potvrdit</button>
                                </div>";
                                }

                            }
                                echo"</div>";

                            ?>

                        </form>
                        <div class="row text-center">
                            <div class="form-group col-12">
                                <label>Autoři článku:</label>
                                <?php
                                $at = $tplData['autori'];
                                $ato = $tplData['prihlasenyJmeno'];
                                $ator = 'Autoři: '.$tplData['prihlasenyJmeno'];
                                foreach($at as $a){
                                    //pokud máme jednoho autora tak upravíme začínající výpis
                                    if(count($at) == 1){
                                        $ato = 'Autor: '.$tplData['prihlasenyJmeno'];
                                    }else{
                                        $ato = 'Autoři: '.$tplData['prihlasenyJmeno'];
                                        $ato = $ato . ", ".  $a;
                                    }
                                }
                                $tplData['autori']=null;
                                $tplData['prihlasenyJmeno'] =null;
                                echo "<p class='font-weight-bold'>$ato</p>";
                                ?>
                            </div>
                        </div>
                        <form action="" method="post" enctype="multipart/form-data">
                            <div hidden>
                                <?php
                                echo "<input type='hidden' value='$ato' name='autoriJm'>";
                                ?>
                            </div>
                            <div class="row text-center">
                                <div class="form-group col-12">
                                    <label for="clanek">Název článku
                                        <span class="input-group">
                                            <span class="input-group-text fa-book"></span>
                                            <input type="text" class="form-control" placeholder="zadejte název článku" name="clanek" required >
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="row text-center">
                                <div class="form-group col-12">
                                    <label>vložte PDF soubor s obsahem článku</label>
                                    <input type="file" name="soubor" accept="application/pdf" required>
                                </div>
                            </div>
                            <div class="row text-center">
                                <div class="form-group col-12">
                                    <label for="abstrakt">abstrakt</label>
                                    <span class="input-group">
                                        <span class="input-group-text fa-pencil"></span>
                                        <textarea class="form-control" rows="4" placeholder="zadejte abstrakt" id="abstrakt" name="abstrakt" required></textarea>
                                    </span>
                                </div>
                            </div>
                            <div hidden class="row text-center">
                               <?php
                                foreach($tplData['autoriU'] as $uziv ){
                                    echo "<input type='hidden' name='uziv[]' value='$uziv[id_uzivatel]'>";
                                }
                                ?>
                            </div>
                            <div class="row text-center">
                                <div class="form-group col-12">
                                    <button class="btn btn-primary" type="submit" name="potvrdit" value="vytvorit">Vytvořit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- karty s clankama-->
    <?php
    if($tplData['autoroviClanky'] != null){
        $counter = 0;
        $navyseni = 0;

        foreach ($tplData['autoroviClanky'] as $aC){
            $autori = $tplData['autoriClanku'][$counter];
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
                    <div class="row text-left">
                        <div class="col">
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
                    <!-- zobrazeni pro vetsi jak md-->
                    <div class="d-none d-md-block">
                        <div class="row text-left">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <?php
                                    echo "<label class='font-weight-bold'>$aC[autori]</label>";
                                ?>
                            </div>
                            <div class="text-right col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <form action="" method="post">
                                    <div hidden="">
                                        <?php
                                        echo "<input type='number' name='clanekS' value=$aC[idCLANEK]>";
                                        ?>
                                    </div>
                                    <button class="btn btn-danger " type="submit" name="odstran" value="odstran">smazat článek</button>
                                </form>
                            </div>
                        </div>
                        <hr>
                        <div class="row text-left mt-3">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <?php
                                echo "<button class='btn btn-primary' data-toggle='collapse' data-target=$Form>Upravit článek</button>";
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
                        <div class="row text-center">
                            <div class="col-12">
                                <?php
                                echo "<label class='font-weight-bold'>$aC[autori]</label>";
                                ?>
                            </div>
                        </div>
                        <div class="row text-center mt-3">
                            <div class="col-12">
                                <form action="" method="post">
                                    <div hidden="">
                                        <?php
                                        echo "<input type='number' name='clanekS' value=$aC[idCLANEK]>";
                                        ?>
                                    </div>
                                    <button class="btn btn-danger " type="submit" name="odstran" value="odstran">smazat článek</button>
                                </form>
                            </div>
                        </div>
                        <hr>
                        <div class="row text-center mt-3">
                            <div class="col-12">
                                <?php
                                echo "<button class='btn btn-primary' data-toggle='collapse' data-target=$Form>Upravit článek</button>";
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

                    <div class="row text-left text-light mt-3">
                        <?php
                        echo"<div class='text-right col-12 collapse bg-secondary' id=$idForm>
                            <form action='' method='post' enctype='multipart/form-data'>
                                <div class='row text-center'>
                                    <div class='form-group col-12'>
                                        <label for='clanek'>Název článku
                                            <span class='input-group'>
                                                <span class='input-group-text fa-book'></span>
                                                <input type='text' class='form-control' placeholder='zadejte název článku' name='clanek'>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class='row text-center'>
                                    <div class='form-group col-12'>
                                        <label>vložte PDF soubor s obsahem článku</label>
                                        <input type='file' name='soubor' accept='application/pdf'>
                                    </div>
                                </div>
                                <div class='row text-center'>
                                    <div class='form-group col-12'>
                                        <label for='abstrakt'>abstrakt</label>
                                        <span class='input-group'>
                                            <span class='input-group-text fa-pencil'></span>
                                            <textarea class='form-control' rows='4' placeholder='zadejte abstrakt' name='abstrakt'></textarea>
                                        </span>
                                    </div>
                                </div>
                                <div hidden=''>
                                    <input type='number' name='clanekU' value=$aC[idCLANEK]>
                                </div>
                                <div class='row text-center'>
                                    <div class='form-group col-12'>
                                        <button class='btn btn-primary' type='submit' name='uprav' value='uprav'>upravit článek</button>
                                    </div>
                                </div>
                            </form>
                        </div>";
                        ?>
                    </div>
                    <div class="row text-left">
                        <div class="form-group col-12">
                            <label class="font-weight-bold">abstrakt:</label>
                            <?php
                            $abstrakt = $aC['abstrakt'];
                            echo"<p class='text-light'>$abstrakt</p>";
                            ?>
                        </div>
                    </div>
                    <div class="row text-center">
                        <div class="col-12">
                            <?php
                            echo "<button class='btn btn-dark font-weight-bold' data-toggle='collapse' data-target=$divId>zobrazit</button>";
                            ?>
                            <?php
                            echo "<div id='$idDiv' class='collapse'>";
                            echo "<embed type='application/pdf' src=$cesta width='100%' height='600px' >";
                            echo "</div>";
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
        }
    }
    ?>

    <!--<div class=" text-center col-12">
        <?php
        //echo "<button class='btn btn-dark font-weight-bold' data-toggle='collapse' data-target='#pdfdivk'>zobrazit</button>";
        ?>
    </div>
    <div id="pdfdivk" class="collapse">
        <?php
        /*$cesta = 'soubory/pdf_SP.pdf';
        echo "<embed type='application/pdf' src=$cesta width='100%' height='600px' >";
        */?>
    </div>-->


</div>

<?php
// paticka
$tplHeaders->getHTMLFooter()

?>
