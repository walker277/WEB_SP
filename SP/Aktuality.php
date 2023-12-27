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
    <meta name="description" content="Stránka s aktualitamy ke konferenci">
    <meta name="author" content="Filip Valtr">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Konference/Registrace</title>
    <link rel="stylesheet" href="styl.css">
    <!-- bootstrap -->

    <link rel="stylesheet" href="composer-ukazka/vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="composer-ukazka/vendor/components/font-awesome/css/font-awesome.min.css">
    <title>Konference_Aktuality</title>
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
<?php
}
///////////// KONEC: PRO PRIHLASENE UZIVATELE ///////////////
?>

<div class="container-fluid lingrad">
    <h2 class="py-3 font-weight-bold text-primary">Aktuality konference</h2>

    <div class="row py-3">
           <div class="card-group">
               <!--Dopln hlavu do tela nekam ukaz víc a carousel muzes pridat-->
               <div class="container col-xl-4 col-lg-6 col-md-12 col-sm-12 py-3">
                   <div class="card border-dark">
                       <div class="card-header">
                           <div class="row container">
                               <h4 class="font-weight-bold font-italic">
                                   Integer quis
                                   <sup class="badge badge-danger badge-pill">
                                       <span class="spinner-grow text-light"></span>
                                       Nově
                                   </sup>
                               </h4>
                           </div>
                           <div class="text-right">11.11.2023</div>
                       </div>
                       <div class="card-body bg-success">
                           <div class="col-12 text-justify text-dark">
                               <p>
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ultrices urna at quam condimentum luctus. Sed sagittis viverra ultrices. Integer quis diam massa. Nunc sodales, est eu varius lobortis, nisi dolor feugiat sapien, non posuere erat tortor quis ipsum. Nam leo nibh, semper vel dui vel, auctor molestie leo. Nulla sapien nulla, pharetra in varius eget, imperdiet sit amet sem. Curabitur sagittis id tortor eu mattis. Pellentesque nec maximus mauris, sed aliquam nisl. Quisque lobortis, lorem sed convallis volutpat, sem urna lobortis urna, nec faucibus dolor libero posuere ante. Quisque at magna augue. Quisque velit nisi, scelerisque at eros eu, vulputate ullamcorper diam. Interdum et malesuada fames ac ante ipsum primis in faucibus. Proin vehicula nulla nec molestie vestibulum. Aenean quis mauris rhoncus, pellentesque diam sed, efficitur massa.
                               </p>
                           </div>
                           <div>
                               <button class="btn btn-link font-weight-bold" data-toggle="collapse" data-target="#info">Zjistit více</button>
                               <div class="text-justify col-12 text-light bg-success rounded collapse" id="info" >
                                   Integer efficitur est vitae arcu pellentesque, nec egestas arcu dignissim. In et nulla metus. Vivamus sollicitudin eros ullamcorper turpis dignissim eleifend. Nam iaculis iaculis viverra. Cras velit lectus, pretium eu nisi eu, accumsan lacinia leo. Etiam velit nunc, tempus ac eleifend suscipit, tristique ut mauris.
                               </div>
                           </div>
                       </div>
                   </div>
               </div>
               <div class="container col-xl-4 col-lg-6 col-md-12 col-sm-12 py-3">
                   <div class="card border-dark">
                       <div class="card-header">
                           <h4>
                               Integer eleifend
                           </h4>
                           <div class="text-right">8.9.2023</div>
                       </div>
                       <div class="card-body bg-warning">
                           <div class="col-12 text-justify">
                               <p>
                                   Integer id tortor elementum ante mollis condimentum. In condimentum mi quis dui convallis fermentum. In vestibulum erat vel lacus porttitor aliquet. Morbi at eros massa. Integer eleifend lacinia ex eget feugiat. Ut sem justo, cursus non condimentum nec, blandit sed nisl. Nulla facilisi. Ut viverra arcu ac gravida vulputate. Suspendisse accumsan varius mi, quis efficitur sem ultrices vel. Maecenas non bibendum nunc. Integer nec turpis sit amet dolor pretium tincidunt semper quis nulla. Nam rhoncus mauris vel justo finibus cursus. Nulla tincidunt pellentesque vehicula. Curabitur in purus at nisl lacinia vehicula.
                               </p>
                           </div>
                           <div>
                               <button class="btn btn-link font-weight-bold" data-toggle="collapse" data-target="#info2">Zjistit více</button>
                               <div class="text-justify col-12 text-light bg-warning rounded collapse" id="info2" >
                                   Fusce sit amet dictum massa. Duis posuere ipsum nunc, non pretium lorem vulputate non. Nullam facilisis ac magna in volutpat. In hac habitasse platea dictumst.
                               </div>
                           </div>
                       </div>
                   </div>
               </div>
               <div class="container col-xl-4 col-lg-12 col-md-12 col-sm-12 py-3">
                   <div class="card border-dark">
                       <div class="card-header">
                           <h4>
                               Donec vulputate
                           </h4>
                           <div class="text-right">8.8.2023</div>
                       </div>
                       <div class="card-body bg-info">
                           <div class="col-12 text-justify">
                               <p>
                                   Fusce blandit rhoncus ipsum, ut efficitur orci aliquam eget. Curabitur ante erat, pellentesque sed leo id, hendrerit ullamcorper libero. Donec vulputate eu erat at sollicitudin. Nam et dui lacinia, hendrerit odio at, faucibus massa. Nam eget molestie velit. Suspendisse eu felis elit. Proin at nibh sit amet turpis suscipit fermentum. Nullam sollicitudin sapien sed volutpat tempor. Mauris pellentesque, nibh non iaculis pulvinar, augue purus congue lacus, a feugiat risus enim eget tellus. Duis commodo auctor nunc et iaculis. Pellentesque nisl ante, tincidunt in aliquet sed, convallis in dolor. Nullam ullamcorper orci quis ex ornare porttitor. Cras placerat elit ac est semper, non fringilla dui efficitur. Aliquam erat volutpat. Integer a turpis nulla.
                               </p>
                           </div>
                           <div>
                               <button class="btn btn-link font-weight-bold" data-toggle="collapse" data-target="#info3">Zjistit více</button>
                               <div class="text-justify col-12 text-light bg-info rounded collapse" id="info3" >
                                   Nulla vehicula lacinia diam nec bibendum. Integer et purus maximus, placerat justo nec, pellentesque ipsum. Morbi est purus, tincidunt id suscipit nec, consequat at ipsum. Vestibulum mollis, elit ac maximus tincidunt, nibh augue viverra augue, eu mollis mi neque a sem.
                               </div>
                           </div>
                       </div>
                   </div>
               </div>
           </div>
    </div>
    <div class="row py-3">
        <div class="col-xl-6 cols-lg-6 col-md-12 col-sm-12">
            <p class="odstavecPrvniPismeno">
                Fusce ac bibendum nulla. Aliquam malesuada odio ut diam aliquam, ut fermentum est faucibus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Quisque ultricies porta nisl quis varius. Nunc condimentum nisl sit amet consectetur vestibulum. Curabitur lectus libero, lacinia et rhoncus nec, hendrerit a mauris. Ut et vehicula mi. Mauris nec leo elementum, auctor enim id, tempor urna. Curabitur ut placerat tortor. Suspendisse mauris arcu, ullamcorper nec viverra eu, rutrum mattis orci. Suspendisse potenti. Integer at aliquam nibh. Donec efficitur luctus odio, vitae varius quam volutpat vel. Suspendisse vitae massa hendrerit, pellentesque dui in, gravida diam. Duis dolor mi, condimentum eget lacinia sit amet, facilisis sed eros.
            </p>
        </div>
        <div class="col-xl-6 cols-lg-6 col-md-12 col-sm-12">
           <p>
               Morbi fermentum cursus nisi, non luctus leo mollis at. Mauris sit amet nibh bibendum, gravida nibh sed, ultricies ipsum. Nullam nec sem vel sem bibendum vehicula. Morbi mi lectus, finibus eget justo a, mollis facilisis augue. Vivamus lobortis lacus sit amet ante congue condimentum. Duis tristique ex lobortis tortor hendrerit rhoncus. Phasellus tristique vel quam et ultrices. Integer suscipit finibus odio et laoreet. Cras lectus sapien, porttitor luctus lacus congue, condimentum feugiat dui. Aenean mollis imperdiet orci vel vestibulum. Quisque tempor quis lacus id ornare. Nam vulputate feugiat tincidunt. Proin eu egestas risus, eget sagittis dolor. Integer non hendrerit augue. Pellentesque nec scelerisque libero.
           </p>
        </div>
    </div>

    <!-- Carousel-->
    <div class="container">
        <div id="carousel" class="carousel slide" data-ride="carousel">
            <!-- Indicators -->
            <ul class="carousel-indicators">
                <li data-target="#carousel" data-slide-to="0" class="active"></li>
                <li data-target="#carousel" data-slide-to="1"></li>
                <li data-target="#carousel" data-slide-to="2"></li>
            </ul>

            <!-- The slideshow -->
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="images/forest.jpg">
                </div>
                <div class="carousel-item">
                    <img src="images/mountain2.jpg">
                </div>
                <div class="carousel-item">
                    <img src="images/udoli2.jpg">
                </div>
            </div>

            <!-- Left and right controls -->
            <a class="carousel-control-prev" href="#carousel" data-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </a>
            <a class="carousel-control-next" href="#carousel" data-slide="next">
                <span class="carousel-control-next-icon"></span>
            </a>
        </div>
    </div>


    <div class="row py-3">
        <div class="col-12 text-light font-italic text-center">
            *Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi finibus finibus finibus. Duis at nunc commodo, mollis turpis eu, commodo massa. Aliquam velit mi, laoreet et viverra eget, suscipit sed massa.
        </div>
    </div>
</div>

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