<?php
require_once("MyDatabase.class.php");
$db = new MyDatabase();
$rights = $db->getAllRights();
// nacteni souboru s funkcemi loginu (pracuje se session)
require_once("Login.class.php");

$login = new Login;
// zpracovani odeslanych formularu - mam akci?
if(isset($_POST["action"])){
    // mam pozadavek na login ?
    if($_POST["action"] == "login") {
        // mam co ulozit?
        if (isset($_POST["username"]) && $_POST["username"] != "") {
            // prihlasim uzivatele
            $login->login($_POST["username"]);
        } else {
            echo "<script>alert('Nebylo zadáno uživatelské jméno.');</script>";
        }
    }// mam pozadavek na logout?
    else if(isset($_POST['action'])){
        if($_POST["action"] == "logout"){
            // odhlasim uzivatele
            $login->logout();
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
    <meta name="description" content="Uvodní stránka konference">
    <meta name="author" content="Filip Valtr">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Konference</title>
    <link rel="stylesheet" href="styl.css">
    <!-- bootstrap -->

    <link rel="stylesheet" href="composer-ukazka/vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="composer-ukazka/vendor/components/font-awesome/css/font-awesome.min.css">
</head>
<body>

<div class="container-fluid text-center text-white bg-dark font-weight-bold">
    <h1 id="nadpis">Konference: Příroda v následujících letech</h1>
</div>
<?php

///////////// PRO NEPRIHLASENE UZIVATELE ///////////////
if(!$login->isUserLogged()){
?>
    <div id="navbar" class="sticky-top">
        <!-- Grey with black text -->
        <nav class="navbar navbar-expand-sm bg-light navbar-light sticky-top fa-star">MENU
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
                                                <input type="password" class="form-control" placeholder="heslo" id="password" name="heslo1" minlength="13" required>
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
    <div id="div_bg">
    </div>
    <div class="lingrad">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <img class="w-75 mx-auto d-block" src="images/priroda6.png">
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <h3 class="text-center font-weight-bold"> Maecenas eget</h3>
                <p class="text-center text-light">Sed blandit elementum sem, hendrerit ornare lorem dapibus id. Nam ut ultrices mi. Cras et dolor at libero luctus accumsan. Vivamus quis purus nunc. Pellentesque sit amet lacus quis augue facilisis volutpat eu quis mi. Sed vitae posuere quam. Ut egestas laoreet dui eu bibendum. Nam malesuada ligula lectus, sit amet commodo massa pellentesque at. </p>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 ">
                <img class="w-75 mx-auto d-block" src="images/priroda1.png">
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <h3 class="text-center font-weight-bold">Curabitur libero</h3>
                <p class="text-center text-light">Sed auctor tincidunt lacinia. Nunc magna ante, aliquet et congue vitae, commodo in augue. Nullam imperdiet ut tortor a bibendum. Suspendisse efficitur venenatis eleifend. Sed sit amet aliquam elit. Vestibulum sed venenatis massa. Donec ac pretium tellus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Etiam nec dolor quis ligula tincidunt congue. </p>
            </div>
        </div>
    </div>
    <div class="jumbotron bg-dark">
        <h1 class="display-4 font-weight-bolder text-primary">Vítejte!</h1>
        <p class="lead text-justify text-light">Maecenas eget sapien massa. Sed eget risus non dui tristique tincidunt vel tincidunt urna. Nunc eu mauris et purus consectetur finibus. Ut vitae dignissim purus.</p>
        <hr class=" border border-primary">
        <p class="text-justify font-weight-light text-light">Phasellus placerat enim at feugiat blandit. Vivamus sed enim maximus, pellentesque ligula sit amet, vehicula risus.</p>
    </div>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <img class="w-75 mx-auto d-block" src="images/priroda7.png">
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <h3 class="text-center font-weight-bold">Ut convallis</h3>
                <p class="text-center text-light">Mauris elementum quam elit, ac tristique augue auctor id. In hac habitasse platea dictumst. Cras efficitur, sem eu viverra volutpat, sapien magna iaculis nunc, ut malesuada leo odio ut velit. Nunc placerat lectus vel venenatis aliquam. Aenean ut sem ut nisi condimentum ullamcorper. Donec pellentesque, justo quis posuere aliquam, enim massa posuere magna, sit amet luctus nisl eros eget lectus. </p>
            </div>
        </div>
    </div>
    </div>
<?php
///////////// KONEC: PRO NEPRIHLASENE UZIVATELE ///////////////
} else {
    ///////////// PRO PRIHLASENE UZIVATELE ///////////////
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
    <div class="container-fluid">
        <h2 class="py-3 font-weight-bold text-primary"> Domovská stránka</h2>

        <?php
            if(isset($_POST['id_uzivatel'])){
                $res = $db->deleteFromTable(TABLE_UZIVATEL, "id_uzivatel='$_POST[id_uzivatel]'");
            }
            if($res){
                echo "<script>alert('Ok: Uživatel byl smazán z databáze');</script>";

            }else{
                echo "<script>alert('ERRO: Smazaní uživatle se nezdařilo');</script>";
            }
            $users = $db->getAllUsers();
        ?>

            <h3>Seznam uživatelů</h3>
            <div class="table-responsive">
                <table class="table table-bordered table-primary">
                    <tr><th>ID</th><th>Uživatelské Jméno</th><th>Jméno</th><th>E-mail</th><th>Právo</th><th>Smazat</th><th>Změnit právo</th></tr>
                    <?php
                    // projdu uzivatele a vypisu je
                    foreach ($users as $u) {
                        echo "<tr><td>$u[id_uzivatel]</td><td>$u[username]</td><td>$u[jmeno_prijmeni]</td><td>$u[email]</td><td>$u[id_pravo]</td><td>"
                            ."<form action='' method='POST'>
                                  <input type='hidden' name='id_uzivatel' value='$u[id_uzivatel]'>
                                  <input type='submit' name='potvrzeni' value='Smazat'>
                              </form>"
                            ."</td>";
                        echo "<td>";
                            echo "<select name='pravo'>";
                                    foreach ($rights as $r){
                                        echo "<option value='$r[id_pravo]'>$r[nazev]</option>";
                                    }
                                echo "<input type='hidden' name='id_pravo' value='$r[id_pravo]'>";
                                echo "<input type='submit' name='potvrzeni' value='Zmenit'>";
                            echo"</select>";
                        echo "</td></tr>";
                    }
                    ?>
                </table>
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


<!-- lokalni alternativa -->

<script src="composer-ukazka/vendor/components/jquery/jquery.min.js"></script>
<script src="composer-ukazka/vendor/alexandermatveev/popper-bundle/AlexanderMatveev/PopperBundle/Resources/public/popper.min.js"></script>
<script src="composer-ukazka/vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>

</body>
</html>