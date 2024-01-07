<?php
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
        <h2 class="py-3 font-weight-bold text-primary">Dotazy</h2>
        <div class="py-3">
            <div class="row text-light">
                <?php
                if($tplData['dotazy'] != null){
                    $navyseni = 0;
                    foreach ($tplData['dotazy'] as $d){
                    $navyseni++;
                    $nadpis = $navyseni.'. Dotaz';
                ?>

                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 py-3">

                        <div class="card border-dark">
                            <div class="card-header text-center bg-dark">
                                <?php
                                echo "<h3 class='text-primary font-weight-bold'>$nadpis</h3>";
                                ?>
                            </div>
                            <div class="card-body text-justify bg-info" >
                                <div class="row">
                                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                        <?php
                                        echo "<label class='font-weight-bold'>Jméno dotazujícího:</label>
                                              <label>$d[jmeno]</label>";
                                        ?>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                        <?php
                                        echo "<label class='font-weight-bold'>email dotazujícího:</label>
                                              <label>$d[e_mail]</label>";
                                        ?>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <?php
                                        echo "<label class='font-weight-bold'>Dotaz:</label>
                                              <p>$d[dotaz]</p>";
                                        ?>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mt-3">
                                    <div class="col-12 text-center">
                                    <?php
                                    echo "<form action='' method='post'>
                                            <input hidden type='number' name='idDotaz' value='$d[id_dotaz]'>
                                            <button class='btn btn-outline-danger' name='odstranD' value='odstranD'>Odstranit dotaz</button>  
                                          </form>";
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
            </div>
        </div>
    </div>
<div>

<?php

// paticka
$tplHeaders->getHTMLFooter()

?>
