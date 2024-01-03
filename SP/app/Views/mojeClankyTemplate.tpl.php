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
<!-- bootstrap -->

<link rel="stylesheet" href="../../composer-ukazka/vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="../../composer-ukazka/vendor/components/font-awesome/css/font-awesome.min.css">
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
                                       <input type="text" name="pocetAutoru" required> (zadejte 0 pokud jste autorem pouze vy)
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
                                var_dump($at);
                                foreach($at as $a){
                                    if($a ===  $ato){
                                        $ato = $tplData['prihlasenyJmeno'];
                                    }else{
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
                                        <textarea class="form-control" rows="4" placeholder="zadejte abstrakt" name="abstrakt" required></textarea>
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
        foreach ($tplData['autoroviClanky'] as $aC){
            $autori = $tplData['autoriClanku'][$counter];
            $counter++;
    ?>
        <div class="py-3">
            <div class="card">
                <div class="card-header text-center">
                    <?php
                    $nazev = $aC['nazev'];
                    echo "<h3 class='text-primary font-weight-bold'>$nazev</h3>";
                    ?>
                </div>
                <div class="card-body text-justify bg-secondary" >
                    <form action="" method="post">
                        <div class="row text-center">
                            <div class="form-group col-12">
                                <?php
                                    $text1 = 'Čeká na přidělení recenzentů';
                                    //todo výkoný kód pro informaci recenzí
                                    //$text2 = '';
                                echo "<input type='text' class='form-control text-dark font-weight-bold bg-danger' value='$text1' readonly >";
                                ?>
                            </div>
                        </div>
                        <div class="row text-left">
                            <div class="form-group col-12">
                                <?php

                                    echo "<label class='font-weight-bold'>$aC[autori]</label>";
                                ?>
                            </div>
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
                            <div class="form-group col-12">
                                <label> PDF SOUBOR</label>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php
        }
    }
    ?>

</div>





<!-- lokalni alternativa -->

<script src="../../composer-ukazka/vendor/components/jquery/jquery.min.js"></script>
<script src="../../composer-ukazka/vendor/alexandermatveev/popper-bundle/AlexanderMatveev/PopperBundle/Resources/public/popper.min.js"></script>
<script src="../../composer-ukazka/vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>

<?php
// paticka
$tplHeaders->getHTMLFooter()

?>
