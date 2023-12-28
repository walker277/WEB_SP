<?php
require_once("MyDatabase.class.php");
$db = new MyDatabase();

// zpracovani odeslanych formularu - mam akci?
if(isset($_POST["action"])){
    // mam pozadavek na login ?
    if($_POST["action"] == "login") {
        // mam co ulozit?
        if ( (isset($_POST["username"]) && $_POST["username"] != "") && (isset($_POST["heslo1"]) && $_POST["heslo1"] != "") ) {
            // prihlasim uzivatele
            $res = $db->userLogin($_POST['username'], $_POST['heslo1']);
            if($res){
                echo "<script>alert('OK: Uživatel byl přihlášen');</script>";
            }else{
                echo "<script>alert('ERROR: Přihlášení uživatele se nezdařilo');</script>";
            }
        } else {
            echo "<script>alert('Nebylo zadáno uživatelské jméno.');</script>";
        }
    }// mam pozadavek na logout?
    else if(isset($_POST['action'])){
        if($_POST["action"] == "logout"){
            // odhlasim uzivatele
            $db->userLogout();
            echo "<script>alert('OK: Uživatel byl odhlášen');</script>";
        }
    }
    // neznamy pozadavek
    else {
        echo "<script>alert('Chyba: Nebyla rozpoznána požadovaná akce.');</script>";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Stránka pro registrovani uzivatele">
    <meta name="author" content="Filip Valtr">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Konference/Registrace</title>
    <link rel="stylesheet" href="styl.css">
    <!-- bootstrap -->

    <link rel="stylesheet" href="composer-ukazka/vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="composer-ukazka/vendor/components/font-awesome/css/font-awesome.min.css">

</head>
<body>
<div class="container-fluid text-center text-white bg-dark font-weight-bold" >
   <h1 id="nadpis">Konference: Příroda v následujících letech</h1>
</div>

<?php

///////////// PRO NEPRIHLASENE UZIVATELE ///////////////
if(!$db->isUserLogged()){
?>
    <div id="navbar" class="sticky-top" >
        <!-- Grey with black text -->
        <nav class="navbar navbar-expand-sm bg-light navbar-light fa-star">MENU
            <!-- Toggler/collapsibe Button -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="collapsibleNavbar">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="Hlavni_Stranka.php">Domů</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Aktuality.php">Aktuality</a>
                    </li>
                    <!--Dropdown-->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                            Přihlášení
                        </a>
                        <div class="dropdown-menu border-primary">
                            <!-- fromular v dropdown -->
                            <form method="post">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="username">Uživatelské jméno:
                                                    <span class="input-group">
                                                <span class="input-group-text fa-user"></span>
                                                <input type="text" class="form-control" placeholder="uživatel" id="username" name="username" required>
                                            </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="password">Heslo:
                                                    <span class="input-group input-group">
                                                <span class="fa-lock input-group-text"></span>
                                                <input type="password" class="form-control" placeholder="heslo" id="password" name="heslo1" minlength="1" required>
                                            </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" >
                                        <div class="col-12">
                                            <div class="form-group form-check">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="checkbox"> Zapamatovat si mě
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <button type="submit" name="action" value="login" class="btn btn-primary">Přihlásit</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Registrace.php">Registrace</a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>

    <div class="lingrad">

        <?php
            if(isset($_POST['potvrzeni'])){
                //mam vsechny pozadovane hodnoty?
                if( isset($_POST['email']) && isset($_POST['username']) && isset($_POST['jmeno']) && isset($_POST['heslo1'])
                    && isset($_POST['narozeni']) && isset($_POST['heslo2']) && isset($_POST['pohlavi'])
                    && ($_POST['heslo1'] == $_POST['heslo2'])
                    && ($_POST['email'] != "") && ($_POST['username'] != "") && ($_POST['jmeno'] != "") && ($_POST['heslo1'] != "")
                    && ($_POST['narozeni'] != "") && ($_POST['heslo2'] != "") && ($_POST['pohlavi'] != "") ){

                    $password = $_POST['heslo2'];
                    //zahashovani hesla
                    $hash = password_hash($password, PASSWORD_BCRYPT);
                    if($db->jeUsernameVolne($_POST['username'],$db->getAllUsers())){
                        $res = $db->addNewUser($_POST['username'], $hash, $_POST['jmeno'], $_POST['email'], 4, $_POST['pohlavi'], $_POST['narozeni'] );
                    }else{
                        echo "<script>alert('ERROR: Uzivatelske jmeno je zabrane, a proto si zvolte jine');</script>";
                    }
                    //byl vlozen?
                    if($res){
                        echo "<script>alert('OK: Uživatel byl přidán do databáze');</script>";
                    }else{
                        echo "<script>alert('ERROR: Vložení uživatle do databáze se nezdařilo');</script>";
                    }
                }else{ //nemame vsechny atributy
                    echo "<script>alert('ERROR: Nebyly přijaty požadované atributy uživatele');</script>";
                }
            }
        ?>

    <!--Formular-->
    <div class="container py-5" id="kartaR" >
        <div class="ohraniceniKarty">
            <div class="card" id="kartaPozadi">
                <div class="card-header text-center">
                    <h3 class="text-primary">Registrace</h3>
                </div>
                <div class="card-body text-justify " >
                    <form action="" method="POST"
                          accept-charset="UTF-8" autocomplete="off" enctype="multipart/form-data">
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
///////////// KONEC: PRO NEPRIHLASENE UZIVATELE ///////////////
} else {
///////////// PRO PRIHLASENE UZIVATELE ///////////////
    $user = $db->getLoggedUserData();
    $UzivID = $user['id_uzivatel'];
    //zpracovani odeslanych formularu
    if(isset($_POST['potvrzeni'])){
        //mam vsechny pozadovane hodnoty?
        if( (isset($_POST['email']) && ($_POST['email'] != "")) ){
            $res = $db->updateUserEmail($UzivID, $_POST['email']);
            if($res){
                echo "<script>alert('OK: Uživatel byl upraven.');</script>";
            }else{
                echo "<script>alert('ERROR: Upravení uživatele se nezdařilo');</script>";
            }
        }elseif ( (isset($_POST['username']) && ($_POST['username'] != "")) ){
            $res = $db->updateUsername($UzivID, $_POST['username']);
            if($res){
                echo "<script>alert('OK: Uživatel byl upraven.');</script>";
            }else{
                echo "<script>alert('ERROR: Upravení uživatele se nezdařilo');</script>";
            }
        }elseif ( (isset($_POST['jmeno_prijmeni']) && ($_POST['jmeno_prijmeni'] != ""))){
            $res = $db->updateUserJmeno($UzivID, $_POST['jmeno_prijmeni']);
            if($res){
                echo "<script>alert('OK: Uživatel byl upraven.');</script>";
            }else{
                echo "<script>alert('ERROR: Upravení uživatele se nezdařilo');</script>";
            }
        } elseif( (isset($_POST['heslo1']) && $_POST['heslo1'] != "") && (isset($_POST['heslo2']) && $_POST['heslo2'] != "")
                   && (isset($_POST['heslo3']) && $_POST['heslo3'] != "") && ($_POST['heslo2'] == $_POST['heslo3']) ){

            //bylo zadano spravne soucasne heslo
            if(password_verify($_POST['heslo1'],$user['password'])){
                $res = $db->updateUserPass($UzivID, $_POST['heslo2']);
                if($res){
                    echo "<script>alert('OK: Uživatel byl upraven.');</script>";
                }else{
                    echo "<script>alert('ERROR: Upravení uživatele se nezdařilo');</script>";
                }
            }else{
                echo "<script>alert('ERROR: Bylo zadáno špatné současné heslo uživatele');</script>";
            }
        }
    }
?>
    <div id="navbar" class="sticky-top" >
        <!-- Grey with black text -->
        <nav class="navbar navbar-expand-sm bg-light navbar-light fa-star">MENU
            <!-- Toggler/collapsibe Button -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="collapsibleNavbar">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="Hlavni_Stranka.php">Domů</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Aktuality.php">Aktuality</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Registrace.php">Osobní údaje</a>
                    </li>
                    <li class="nav-item">
                        <form method="post">
                            <button class="btn-outline-danger text-dark font-weight-bold border-dark" name="action" value="logout">Odhlásit se</button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>
    </div>

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
                                        <input type="email" class="form-control"  id="emailO" name="email" value="<?php echo $user['email']; ?>" readonly>
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
                                        <input type="text" class="form-control"  id="usernameO" name="username" value="<?php echo $user['username']; ?>" readonly>
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
                                        <input type="text" class="form-control" id="Jmeno_PrijmeniO" name="jmeno" value="<?php echo $user['jmeno_prijmeni']; ?>" readonly >
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
                                        <input type="text" class="form-control" id="PohlaviO" name="pohlavi" value="<?php echo $user['pohlavi']; ?>" readonly>
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-sm-12 col-xl-6 col-lg-6 col-md-6" >
                                <label for="NarozeniO">Datum narození:
                                    <span class="input-group">
                                        <span class="input-group-text fa-calendar"></span>
                                        <input type="date" class="form-control"  id="NarozeniO" name="narozeni" value="<?php echo $user['datum_narozeni']; ?>" readonly>
                                    </span>
                                </label>
                            </div>
                            <div class="form-group col-sm-12 col-xl-6 col-lg-6 col-md-6" >
                                <label for="PravoO">Právo:
                                    <span class="input-group">
                                        <span class="input-group-text fa-calendar"></span>
                                        <input type="text" class="form-control" id="PravoO" name="pravo" value="<?php echo $db->getRightById($user['id_pravo'])['nazev'] ?>" readonly>
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
}
///////////// KONEC: PRO PRIHLASENE UZIVATELE ///////////////
?>
<footer class="container-fluid bg-dark text-white text-center font-weight-bold py-3">
    <div class="container-fluid text-justify">
        <form action="http://students.kiv.zcu.cz/~nyklm/+studenti-kiv-web/formular-zobrazeni.php" method="POST"
              target="_blank" accept-charset="UTF-8" autocomplete="off" enctype="multipart/form-data">
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                    <div>
                        <h4 class="font-weight-bold" id="nadpis2">Máte dotaz? Napište nám:</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <hr class="border border-secondary">
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label class="font-italic" for="emailD">Váš email:
                            <span class="input-group">
                                <span class="input-group-text fa-envelope"></span>
                                <input type="email" class="form-control" placeholder="zadejte email" id="emailD" name="email" required >
                            </span>
                        </label>
                    </div>
                    <div class="form-group">
                        <label class="font-italic" for="JmenoD">Vaše jméno:
                            <span class="input-group">
                                <span class="input-group-text fa-user"></span>
                                <input type="text" class="form-control" placeholder="zadejte jméno" id="JmenoD" name="jmeno" required >
                            </span>
                        </label>
                    </div>
                </div>

                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                    <div>
                        <label class="font-italic" for="textareaD">Dotaz:</label>
                        <span class="input-group">
                                    <span class="input-group-text fa-pencil"></span>
                                    <textarea class="form-control" rows="4" placeholder="zadejte dotaz" id="textareaD" name="dotaz" required></textarea>
                                </span>
                    </div>
                    <div class="btn-group py-2">
                        <button type="submit" class="btn btn-primary " name="odeslano">Odeslat</button>
                    </div>
                </div>
            </div>
        </form>
        <div class="row mt-3">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <h4 class="font-weight-bold" id="nadpis3">Podpora</h4>
            </div>

        </div>
        <div class="row">
            <div class="col-12">
                <hr class="border border-secondary">
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <div>
                    <span class="fa-envelope"><i>e-mail:</i> ipsum@support.cz</span>
                </div>
                <div>
                    <span class="fa-phone"><i>telefon:</i> 00 000 000 000</span>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            (c) 2023
            <span class="fa fa-cog fa-spin"></span>
        </div>
    </div>
</footer>

<script>
    function validate_pw2(pw2, pw1) {
        if (pw2.value !== document.getElementById(pw1).value) {
            pw2.setCustomValidity("Duplicitní heslo bylo chybně zadáno");
        } else {
            pw2.setCustomValidity(""); // je správné
        }
    }
</script>


<!-- lokalni alternativa -->

<script src="composer-ukazka/vendor/components/jquery/jquery.min.js"></script>
<script src="composer-ukazka/vendor/alexandermatveev/popper-bundle/AlexanderMatveev/PopperBundle/Resources/public/popper.min.js"></script>
<script src="composer-ukazka/vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>