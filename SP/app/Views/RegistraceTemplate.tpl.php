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
<div class="lingrad">
    <!--Formular-->
    <div class="container py-5" id="kartaR" >
        <div class="ohraniceniKarty">
            <div class="card" id="kartaPozadi">
                <div class="card-header text-center">
                    <h3 class="text-primary">Registrace</h3>
                </div>
                <div class="card-body text-justify " >
                    <form action="" method="POST" accept-charset="UTF-8">
                        <div class="row" >
                            <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <label for="emailR">Email:
                                    <span class="input-group">
                                        <span class="input-group-text fa-envelope"></span>
                                        <input type="email" class="form-control" placeholder="zadejte email" id="emailR" name="email" required >
                                    </span>
                                </label>
                            </div>
                            <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <label for="usernameR">Uživatelské jméno:
                                    <span class="input-group">
                                        <span class="input-group-text fa-user"></span>
                                        <input type="text" class="form-control" placeholder="zadejte uživatelské jméno" id="usernameR" name="username" required>
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-sm-12 col-xl-6 col-lg-6 col-md-6">
                                <label for="Jmeno_PrijmeniR">Jméno a Příjmení:
                                    <span class="input-group">
                                        <span class="input-group-text fa-user"></span>
                                        <input type="text" class="form-control" placeholder="zadejte celé jméno" id="Jmeno_PrijmeniR" name="jmeno" required >
                                    </span>
                                </label>
                            </div>
                            <div class="form-group col-sm-12 col-xl-6 col-lg-6 col-md-6">
                                <label for="passwordR">Heslo:
                                    <span class="input-group input-group">
                                        <span class="fa-lock input-group-text"></span>
                                        <input type="password" class="form-control" placeholder="zadejte heslo" id="passwordR" name="heslo1" minlength="1" required>
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-sm-12 col-xl-6 col-lg-6 col-md-6" >
                                <label for="NarozeniR">Datum narození:
                                    <span class="input-group">
                                        <span class="input-group-text fa-calendar"></span>
                                         <input type="date" class="form-control"  id="NarozeniR" name="narozeni" value="2023-01-01" required >
                                    </span>
                                </label>
                            </div>
                            <div class="form-group col-sm-12 col-xl-6 col-lg-6 col-md-6">
                                <label for="passwordR2">Kontrola (Zopakujte heslo):
                                    <span class="input-group">
                                        <span class="input-group-text fa-lock"></span>
                                        <input type="password" class="form-control" placeholder="zopakujte zadání hesla" name="heslo2" id="passwordR2" minLength=1 oninput="validate_pw2(this,'passwordR')" required>
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-sm-12 col-xl-6 col-lg-6 col-md-6">Pohlaví:
                                <div class="form-check form-check-inline">
                                    <label for="MuzR">
                                        <span class="input-group">
                                            <span class="input-group-text fa-male"></span>
                                            <input class="form-check-input" type="radio"  checked value="muz" name="pohlavi" id="MuzR">
                                        </span>
                                    </label>
                                    <label for="ZenaR">
                                        <span class="input-group">
                                            <span class="input-group-text fa-female"></span>
                                            <input class="form-check-input" type="radio" value="zena" name="pohlavi" id="ZenaR">
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-sm-12 col-xl-6 col-lg-6 col-md-6">
                                <div class="btn-group">
                                    <button type="submit" class="btn btn-primary " name="potvrzeni" value="Registrovat">Registrovat se</button>
                                    <button type="reset" class="btn btn-outline-primary " name="resetovano">Reset</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5 text-light font-italic" >
        *Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam sagittis consectetur risus, sed ullamcorper turpis imperdiet sed. Vestibulum nisi neque, feugiat vel arcu nec, convallis porttitor dui. Integer nec pharetra diam.
    </div>
</div>

<?php
// paticka
$tplHeaders->getHTMLFooter()

?>