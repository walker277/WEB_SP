<?php
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
// muze se hodit:
//<form method='post'>
//    <input type='hidden' name='id_user' value=''>
//    <button type='submit' name='action' value='delete'>Smazat</button>
//</form>

// hlavicka
if($tplData['prihlasen']){
    $tplHeaders->getHTMLHeaderPrihlasen($tplData['title'],$tplData['id_pravo']);
}else{
    $tplHeaders->getHTMLHeader($tplData['title']);
}
?>
<!-- bootstrap -->

<link rel="stylesheet" href="../../composer-ukazka/vendor/twbs/bootstrap/dist/css/bootstrap.min.css"
      xmlns="http://www.w3.org/1999/html">
<link rel="stylesheet" href="../../composer-ukazka/vendor/components/font-awesome/css/font-awesome.min.css">
<div class="lingrad">
    <div class="container-fluid">
        <h2 class="py-3 font-weight-bold text-primary">Správa Článků</h2>
           <?php
           foreach ($tplData['clanky'] as $aC){
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
                               <div class="col-xl-6 col-lg-6  col-md-6 text-right mt-3">

                                   <form method="post" action="">
                                       <?php
                                       echo "<input type='hidden' name='idClanek' value='{$aC['idCLANEK']}'>";
                                       if ($aC['schvalen'] == 0){
                                           echo "<button type='submit' class='btn btn-success' name='stavC' value='povolitC'>povolit</button>
                                              <button type='submit' class='btn btn-danger' name='stavC' value='zamitnoutC'>zamítnout</button>";
                                       }elseif($aC['schvalen'] == 1){
                                           echo "<button type='submit' class='btn btn-success' name='stavC' value='povolitC'>povolit</button>";
                                       }else{
                                           echo "<button type='submit' class='btn btn-danger' name='stavC' value='zamitnoutC'>zamítnout</button>";
                                       }
                                       ?>
                                   </form>
                               </div>
                           </div>
                           <div class="row text-left mt-3">
                               <div class="col-12">
                                   <?php
                                   echo "<label class='font-weight-bold'>$aC[autori]</label>";
                                   ?>
                               </div>
                           </div>
                            <form method="post" action="">
                                <div class="row text-left mt-3">
                                    <div class="col-xl-6 col-lg-6 col-md-6 ">
                                        <?php
                                        if($aC['recenzent_1'] != null && $aC['recenzent_2'] != null && $aC['recenzent_3'] != null){
                                            echo "Byl přidělen maximální možný počet recenzentů";
                                        } else {
                                        ?>
                                        <label>Vyberte recenzenty
                                            <?php
                                            echo "<input type='hidden' name='idClanek' value='{$aC['idCLANEK']}'>";
                                            ?>
                                            <select name="recenzent">
                                                <?php
                                                if($tplData['recenzenti'] != null){
                                                    $pruchod = 0;
                                                    foreach ($tplData['recenzenti'] as $r){
                                                        $pruchod++;
                                                        //první recenzent není nastaven
                                                        if($aC['recenzent_1'] == null){
                                                            $selected = ($pruchod == count($tplData['recenzenti'])) ? "selected" : "";
                                                            echo "<option value='$r[id_uzivatel]' selected=$selected>$r[jmeno_prijmeni]</option>";
                                                        }else{//první je nastaven
                                                            //druhý recenzent není nastaven
                                                            if($aC['recenzent_2']== null){
                                                                //musíme porovnat jestli je rozdílný od prvního
                                                                if($r['id_uzivatel'] != $aC['recenzent_1'] ){
                                                                    $selected = ($pruchod == count($tplData['recenzenti'])) ? "selected" : "";
                                                                    echo "<option value='$r[id_uzivatel]' selected=$selected>$r[jmeno_prijmeni]</option>";
                                                                }
                                                            }else{//druhý je nastaven
                                                                //třetí není nastaven
                                                                if($aC['recenzent_3']== null){
                                                                    //musíme porovnat jestli je rozdílný od prvního a druhého
                                                                    if($r['id_uzivatel'] != $aC['recenzent_1'] && $r['id_uzivatel'] != $aC['recenzent_2']){
                                                                        $selected = ($pruchod == count($tplData['recenzenti'])) ? "selected" : "";
                                                                        echo "<option value='$r[id_uzivatel]' selected=$selected>$r[jmeno_prijmeni]</option>";
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </label>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                    <?php if($aC['recenzent_1'] == null || $aC['recenzent_2'] == null || $aC['recenzent_3'] == null){ ?>
                                    <div class="col-xl-6 col-lg-6 col-md-6 text-right">
                                        <button class='btn btn-primary' type='submit' name='pridat' value='pridat'>Potvrdit recenzenta</button>
                                    </div>
                                    <?php } ?>
                                </div>
                            </form>
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
                                    <form method="post" action="">
                                        <?php
                                        echo "<input hidden name='idClanek' value=$aC[idCLANEK]>";
                                    if ($aC['schvalen'] == 0){
                                        echo "<button type='submit' class='btn btn-success' name='stavC' value='povolitC'>povolit</button>
                                              <button type='submit' class='btn btn-danger' name='stavC' value='zamitnoutC'>zamítnout</button>";
                                    }elseif($aC['schvalen'] == 1){
                                        echo "<button type='submit' class='btn btn-success' name='stavC' value='povolitC'>povolit</button>";
                                    }else{
                                        echo "<button type='submit' class='btn btn-danger' name='stavC' value='zamitnoutC'>zamítnout</button>";
                                    }
                                        ?>
                                    </form>
                                </div>
                            </div>
                            <div class="row text-center mt-3">
                                <div class="col-12">
                                    <?php
                                    echo "<label class='font-weight-bold'>$aC[autori]</label>";
                                    ?>
                                </div>
                            </div>
                            <form method="post" action="">
                                <div class="row text-center mt-3">
                                    <div class="col-12 ">
                                        <?php
                                        if($aC['recenzent_1'] != null && $aC['recenzent_2'] != null && $aC['recenzent_3'] != null){
                                            echo "Byl přidělen maximální možný počet recenzentů";
                                        } else {
                                            ?>
                                            <label>Vyberte recenzenty
                                                <?php
                                                echo "<input type='hidden' name='idClanek' value='{$aC['idCLANEK']}'>";
                                                ?>
                                                <select name="recenzent">
                                                    <?php
                                                    if($tplData['recenzenti'] != null){
                                                        $pruchod = 0;
                                                        foreach ($tplData['recenzenti'] as $r){
                                                            $pruchod++;
                                                            //první recenzent není nastaven
                                                            if($aC['recenzent_1'] == null){
                                                                $selected = ($pruchod == count($tplData['recenzenti'])) ? "selected" : "";
                                                                echo "<option value='$r[id_uzivatel]' selected=$selected>$r[jmeno_prijmeni]</option>";
                                                            }else{//první je nastaven
                                                                //druhý recenzent není nastaven
                                                                if($aC['recenzent_2']== null){
                                                                    //musíme porovnat jestli je rozdílný od prvního
                                                                    if($r['id_uzivatel'] != $aC['recenzent_1'] ){
                                                                        $selected = ($pruchod == count($tplData['recenzenti'])) ? "selected" : "";
                                                                        echo "<option value='$r[id_uzivatel]' selected=$selected>$r[jmeno_prijmeni]</option>";
                                                                    }
                                                                }else{//druhý je nastaven
                                                                    //třetí není nastaven
                                                                    if($aC['recenzent_3']== null){
                                                                        //musíme porovnat jestli je rozdílný od prvního a druhého
                                                                        if($r['id_uzivatel'] != $aC['recenzent_1'] && $r['id_uzivatel'] != $aC['recenzent_2']){
                                                                            $selected = ($pruchod == count($tplData['recenzenti'])) ? "selected" : "";
                                                                            echo "<option value='$r[id_uzivatel]' selected=$selected>$r[jmeno_prijmeni]</option>";
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </label>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <?php if($aC['recenzent_1'] == null || $aC['recenzent_2'] == null || $aC['recenzent_3'] == null){ ?>
                                <div class="row text-center mt-3">
                                    <div class="col-12">
                                        <button class='btn btn-primary' type='submit' name='pridat' value='pridat'>Potvrdit recenzenta</button>
                                    </div>
                                </div>
                                <?php } ?>
                            </form>
                        </div>

                        <hr>
                        <div class="row text-left mt-3">
                            <div class="container">
                                <div class="col-12 table-responsive">
                                    <table class="table table-bordered table-striped table-primary table-hover">
                                        <thead class="thead-light text-center">
                                        <tr><th>Recenzent</th><th>Udělené hodnocení</th><th>Odebrat autora</th></tr>
                                        </thead>

                                        <?php
                                        if($aC['recenzent_1'] != null){//1. recenzemt je nastaven
                                            foreach ($tplData['uzivatele'] as $u){
                                                if($u['id_uzivatel'] == $aC['recenzent_1']){
                                                    echo"<tr><td class='text-center'>$u[jmeno_prijmeni] </td>";
                                                }
                                            }
                                            //recenzent už ohodnotil
                                            if($aC['hodnoceni_1'] != 0){
                                                if($aC['hodnoceni_1'] >= 8){//pokud je clanek hodnocen 8-10
                                                    echo"<td class='text-success text-center font-weight-bold'>$aC[hodnoceni_1]</td>";
                                                }elseif($aC['hodnoceni_1'] >= 5){//pokud je clanek hodnocen 5-8
                                                    echo"<td class='text-warning text-center font-weight-bold'>$aC[hodnoceni_1]</td>";
                                                }else{//pro mensí jak 5
                                                    echo"<td class='text-danger text-center font-weight-bold'>$aC[hodnoceni_1]</td>";
                                                }
                                            }else{//jestli ještě recenzent nehodnotil
                                                echo"<td class='text-center font-weight-bold text-info'>čeká se na ohodnecení uživatelem</td>";
                                            }
                                            echo "<form action='' method='post'>";
                                                echo "<input type='hidden' name='idClanek' value='{$aC['idCLANEK']}'>";
                                                echo "<td class='text-center'><button class='btn btn-dark' type='submit' name='smazatA' value='recenzent_1'>odebrat recenzenta</button></td></tr>
                                              </form>";
                                        }else{
                                            echo"<tr><td colspan='3' class='bg-danger text-light text-center'>1. recenzent není přidělen</td></tr>";
                                        }

                                        if($aC['recenzent_2'] != null){//2. recenzemt je nastaven
                                            foreach ($tplData['uzivatele'] as $u){
                                                if($u['id_uzivatel'] == $aC['recenzent_2']){
                                                    echo"<tr><td class='text-center'>$u[jmeno_prijmeni] </td>";
                                                }
                                            }
                                            //recenzent už ohodnotil
                                            if($aC['hodnoceni_2'] != 0){
                                                if($aC['hodnoceni_2'] >= 8){//pokud je clanek hodnocen 8-10
                                                    echo"<td class='text-success text-center font-weight-bold'>$aC[hodnoceni_2]</td>";
                                                }elseif($aC['hodnoceni_2'] >= 5){//pokud je clanek hodnocen 5-8
                                                    echo"<td class='text-warning text-center font-weight-bold'>$aC[hodnoceni_2]</td>";
                                                }else{//pro mensí jak 5
                                                    echo"<td class='text-danger text-center font-weight-bold'>$aC[hodnoceni_2]</td>";
                                                }
                                            }else{//jestli ještě recenzent nehodnotil
                                                echo"<td class='text-center font-weight-bold text-info'>čeká se na ohodnecení uživatelem</td>";
                                            }
                                            echo "<form action='' method='post'>";
                                                echo "<input type='hidden' name='idClanek' value='{$aC['idCLANEK']}'>
                                                <td class='text-center'><button class='btn btn-dark' type='submit' name='smazatA' value='recenzent_2'>odebrat recenzenta</button></td></tr>
                                              </form>";
                                        }else{
                                            echo"<tr><td colspan='3' class='bg-danger text-light text-center'>2. recenzent není přidělen</td></tr>";
                                        }

                                        if($aC['recenzent_3'] != null){//3. recenzemt je nastaven
                                            foreach ($tplData['uzivatele'] as $u){
                                                if($u['id_uzivatel'] == $aC['recenzent_3']){
                                                    echo"<tr><td class='text-center'>$u[jmeno_prijmeni] </td>";
                                                }
                                            }
                                            //recenzent už ohodnotil
                                            if($aC['hodnoceni_3'] != 0){
                                                if($aC['hodnoceni_3'] >= 8){//pokud je clanek hodnocen 8-10
                                                    echo"<td class='text-success text-center font-weight-bold'>$aC[hodnoceni_3]</td>";
                                                }elseif($aC['hodnoceni_3'] >= 5){//pokud je clanek hodnocen 5-8
                                                    echo"<td class='text-warning text-center font-weight-bold'>$aC[hodnoceni_3]</td>";
                                                }else{//pro mensí jak 5
                                                    echo"<td class='text-danger text-center font-weight-bold'>$aC[hodnoceni_3]</td>";
                                                }
                                            }else{//jestli ještě recenzent nehodnotil
                                                echo"<td class='text-center font-weight-bold text-info'>čeká se na ohodnecení uživatelem</td>";
                                            }
                                            echo "<form action='' method='post'>";
                                                echo "<input type='hidden' name='idClanek' value='{$aC['idCLANEK']}'>
                                                <td class='text-center'><button class='btn btn-dark' type='submit' name='smazatA' value='recenzent_3'>odebrat recenzenta</button></td></tr>
                                              </form>";
                                        }else{
                                            echo"<tr><td colspan='3' class='bg-danger text-center text-light'>3. recenzent není přidělen</td></tr>";
                                        }
                                        ?>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        <?php
           }
        ?>
    </div>
</div>
<!-- lokalni alternativa -->

<script src="../../composer-ukazka/vendor/components/jquery/jquery.min.js"></script>
<script src="../../composer-ukazka/vendor/alexandermatveev/popper-bundle/AlexanderMatveev/PopperBundle/Resources/public/popper.min.js"></script>
<script src="../../composer-ukazka/vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>

<?php
// paticka
$tplHeaders->getHTMLFooter()

?>
