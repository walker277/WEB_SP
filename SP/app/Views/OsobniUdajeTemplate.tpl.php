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

if($tplData['prihlasen']){
    $tplHeaders->getHTMLHeaderPrihlasen($tplData['title'],$tplData['id_pravo']);
}else{
    $tplHeaders->getHTMLHeader($tplData['title']);
}
?>

<div class="lingrad">
    <div class="container py-5" id="kartaR" >
        <div class="ohraniceniKarty">
            <div class="card" id="kartaPozadi">
                <div class="card-header text-center">
                    <h3 class="text-primary">Osobní údaje</h3>
                </div>
                <div class="card-body text-justify " >
                    <div class="row" >
                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <label for="emailO">Email:
                                <span class="input-group">
                                    <span class="input-group-text fa-envelope"></span>
                                    <input type="email" class="form-control"  id="emailO" name="email" value="<?php echo $tplData['uzivatel']['email']; ?>" readonly>
                                </span>
                                <button class="btn btn-link font-weight-bold" data-toggle="collapse" data-target="#form">Změnit email</button>
                                <div class="collapse" id="form">
                                    <form action="" method="post">
                                        <span class="input-group">
                                            <span class="input-group-text fa-envelope"></span>
                                            <input type="email" class="form-control" placeholder="zadejte nový email" id="emailZ" name="email" required >
                                        </span>
                                        <button type="submit" class="btn btn-primary " name="potvrzeni" value="zmenit">Ulozit zmenu</button>
                                    </form>
                                </div>
                            </label>
                        </div>
                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <label for="usernameO">Uživatelské jméno:
                                <span class="input-group">
                                    <span class="input-group-text fa-user"></span>
                                    <input type="text" class="form-control"  id="usernameO" name="username" value="<?php echo $tplData['uzivatel']['username']; ?>" readonly>
                                </span>
                                <button class="btn btn-link font-weight-bold" data-toggle="collapse" data-target="#form2">Změnit username</button>
                                <div class="collapse" id="form2">
                                    <form action="" method="post">
                                        <span class="input-group">
                                            <span class="input-group-text fa-envelope"></span>
                                            <input type="text" class="form-control" placeholder="nové uživatelské jméno" id="usernameZ" name="username" required >
                                        </span>
                                        <button type="submit" class="btn btn-primary " name="potvrzeni" value="zmenit">Ulozit zmenu</button>
                                    </form>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-12 col-xl-6 col-lg-6 col-md-6">
                            <label for="Jmeno_PrijmeniO">Jméno a Příjmení:
                                <span class="input-group">
                                    <span class="input-group-text fa-user"></span>
                                    <input type="text" class="form-control" id="Jmeno_PrijmeniO" name="jmeno" value="<?php echo $tplData['uzivatel']['jmeno_prijmeni']; ?>" readonly >
                                </span>
                                <button class="btn btn-link font-weight-bold" data-toggle="collapse" data-target="#form3">Změnit jméno</button>
                                <div class="collapse" id="form3">
                                    <form action="" method="post">
                                        <span class="input-group">
                                            <span class="input-group-text fa-envelope"></span>
                                            <input type="text" class="form-control" placeholder="zadejte Jméno a Přijmení" id="Jmeno_Prijmeniz" name="jmeno_prijmeni" required >
                                        </span>
                                        <button type="submit" class="btn btn-primary " name="potvrzeni" value="zmenit">Ulozit zmenu</button>
                                    </form>
                                </div>
                            </label>
                        </div>
                        <div class="form-group col-sm-12 col-xl-6 col-lg-6 col-md-6">
                            <label for="PohlaviO">Pohlaví:
                                <span class="input-group">
                                    <span class="input-group-text fa-calendar"></span>
                                    <input type="text" class="form-control" id="PohlaviO" name="pohlavi" value="<?php echo $tplData['uzivatel']['pohlavi']; ?>" readonly>
                                </span>
                            </label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-12 col-xl-6 col-lg-6 col-md-6" >
                            <label for="NarozeniO">Datum narození:
                                <span class="input-group">
                                    <span class="input-group-text fa-calendar"></span>
                                    <input type="date" class="form-control"  id="NarozeniO" name="narozeni" value="<?php echo $tplData['uzivatel']['datum_narozeni']; ?>" readonly>
                                </span>
                            </label>
                        </div>
                        <div class="form-group col-sm-12 col-xl-6 col-lg-6 col-md-6" >
                            <label for="PravoO">Právo:
                                <span class="input-group">
                                    <span class="input-group-text fa-calendar"></span>
                                    <input type="text" class="form-control" id="PravoO" name="pravo" value="<?php echo $tplData['nazevUzivatelovaPrava'] ?>" readonly>
                                </span>
                            </label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 text-center">
                            <button class="btn btn-link font-weight-bold" data-toggle="collapse" data-target="#form4">Změnit heslo</button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="container">
                            <div class="collapse" id="form4">
                                <form action="" method="post">
                                    <div class="form-group col-12 text-center">
                                        <label for="passwordZ1">Původní heslo:
                                            <span class="input-group input-group">
                                                <span class="fa-lock input-group-text"></span>
                                                <input type="password" class="form-control" placeholder="zadejte puvodni heslo" id="passwordZ1" name="heslo1" minlength="1" required>
                                            </span>
                                        </label>
                                    </div>
                                    <div class="form-group col-12 text-center">
                                        <label for="passwordZ2">Nové heslo:
                                            <span class="input-group input-group">
                                                <span class="fa-lock input-group-text"></span>
                                                <input type="password" class="form-control" placeholder="zadejte nové heslo" id="passwordZ2" name="heslo2" minlength="1" required>
                                            </span>
                                        </label>
                                    </div>
                                    <div class="form-group col-12 text-center">
                                        <label for="passwordZ3">Kontrola hesla:
                                            <span class="input-group input-group">
                                                <span class="fa-lock input-group-text"></span>
                                                <input type="password" class="form-control" placeholder="zopakujte heslo" id="passwordZ3" name="heslo3" minlength="1" oninput="validate_pw2(this,'passwordZ2')" required>
                                            </span>
                                        </label>
                                    </div>
                                    <div class="form-group col-12 text-center">
                                        <button type="submit" class="btn btn-primary " name="potvrzeni" value="zmenit">Ulozit zmenu</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// paticka
$tplHeaders->getHTMLFooter()

?>