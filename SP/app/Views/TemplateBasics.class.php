<?php

/**
 * Trida vypisujici HTML hlavicku a paticku stranky.
 */
class TemplateBasics {
    /**
     * Hlavicka urcena prihlasenym uzivatelum
     * @param string $pageTitle Nazev stranky.
     * @return void
     */
    public function getHTMLHeaderPrihlasen(string $pageTitle, int $id_pravo) {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="description" content="Uvodní stránka konference">
            <meta name="author" content="Filip Valtr">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title><?php echo $pageTitle; ?></title>
            <link rel="stylesheet" href="styl.css">
            <!-- bootstrap -->

            <link rel="stylesheet" href="composer-ukazka/vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
            <link rel="stylesheet" href="composer-ukazka/vendor/components/font-awesome/css/font-awesome.min.css">
        </head>
        <body>
        <div class="container-fluid text-center text-white bg-dark font-weight-bold">
            <h1 id="nadpis">Konference: Příroda v následujících letech</h1>
        </div>
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
                            <?php
                            $key = "domaci";
                            $pInfo = WEB_PAGES["domaci"]["title"];
                            echo "<a class='nav-link' href='index.php?page=$key'>$pInfo</a>";
                            ?>
                        </li>
                        <li class="nav-item">
                            <?php
                            $key = "aktuality";
                            $pInfo = WEB_PAGES["aktuality"]["title"];
                            echo "<a class='nav-link' href='index.php?page=$key'>$pInfo</a>";
                            ?>
                        </li>
                        <li class="nav-item">
                            <?php
                            $key = "osobniudaje";
                            $pInfo = WEB_PAGES["osobniudaje"]["title"];
                            echo "<a class='nav-link' href='index.php?page=$key'>$pInfo</a>";
                            ?>
                        </li>
                        <?php
                        echo  "<li class='nav-item'>";
                        if($id_pravo == 4) { //ta
                            $key = "mojeClanky";
                            $pInfo = WEB_PAGES["mojeClanky"]["title"];
                            echo "<a class='nav-link' href='index.php?page=$key'>$pInfo</a>";
                        }
                        echo "</li>";
                        ?>
                        <?php
                        echo  "<li class='nav-item'>";
                        if($id_pravo < 3) { //ta
                            $key = "spravaUzivatelu";
                            $pInfo = WEB_PAGES["spravaUzivatelu"]["title"];
                            echo "<a class='nav-link' href='index.php?page=$key'>$pInfo</a>";
                        }
                        echo "</li>";
                        ?>
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



    /**
     *  Vrati vrsek stranky az po oblast, ve ktere se vypisuje obsah stranky.
     *  @param string $pageTitle    Nazev stranky.
     */
    public function getHTMLHeader(string $pageTitle) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="description" content="Uvodní stránka konference">
        <meta name="author" content="Filip Valtr">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $pageTitle; ?></title>
        <link rel="stylesheet" href="styl.css">
        <!-- bootstrap -->

        <link rel="stylesheet" href="composer-ukazka/vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="composer-ukazka/vendor/components/font-awesome/css/font-awesome.min.css">
    </head>
    <body>

    <div class="container-fluid text-center text-white bg-dark font-weight-bold">
        <h1 id="nadpis">Konference: Příroda v následujících letech</h1>
    </div>
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
                        <?php
                        $key = "uvod";
                        $pInfo = WEB_PAGES["uvod"]["title"];
                        echo "<a class='nav-link' href='index.php?page=$key'>$pInfo</a>";
                        ?>
                    </li>
                    <li>
                        <?php
                        $key = "aktuality";
                        $pInfo = WEB_PAGES["aktuality"]["title"];
                        echo "<a class='nav-link' href='index.php?page=$key'>$pInfo</a>";
                        ?>
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
                        <?php
                        $key = "registrace";
                        $pInfo = WEB_PAGES["registrace"]["title"];
                        echo "<a class='nav-link' href='index.php?page=$key'>$pInfo</a>";
                        ?>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
    <?php
    }

    /**
     *  Vrati paticku stranky.
     */
    public function getHTMLFooter(){
    ?>
    <footer class="container-fluid bg-dark text-white text-center font-weight-bold py-3">
        <div class="container-fluid text-justify">
            <form action="" method="POST"
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




    <?php
    }

}

?>