<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
/////////////////////////////////////////////////////////////
/////////// Sablona pro zobrazeni uvodni stranky  ///////////
/////////////////////////////////////////////////////////////

//// pozn.: sablona je samostatna a provadi primy vypis do vystupu:
// -> lze testovat bez zbytku aplikace.
// -> pri vyuziti Twigu se sablona obejde bez PHP.

/*
////// Po zakomponovani do zbytku aplikace bude tato cast odstranena/zakomentovana  //////
//// UKAZKA: Uvod bude vypisovat informace z tabulky, ktera ma nasledujici sloupce:
// id, date, author, title, text
$tplData['title'] = "Úvodní stránka (TPL)";
$tplData['stories'] = [
    array("id_introduction" => 1, "date" => "2016-11-01 10:53:00", "author" => "A.B.", "title" => "Nadpis", "text" => "abcd")
];
define("DIRECTORY_VIEWS", "../Views");
const WEB_PAGES = array(
    "uvod" => array("title" => "Úvodní stránka (TPL)")
);*/
////// KONEC: Po zakomponovani do zbytku aplikace bude tato cast odstranena/zakomentovana  //////


//// vypis sablony
// urceni globalnich promennych, se kterymi sablona pracuje
global $tplData;

// pripojim objekt pro vypis hlavicky a paticky HTML
require("TemplateBasics.class.php");
$tplHeaders = new TemplateBasics();

?>
    <!-- ------------------------------------------------------------------------------------------------------- -->

    <!-- Vypis obsahu sablony -->
<?php
// muze se hodit: strtotime($d['date'])

// hlavicka, pokud je prihlasen tak se vypise hlavicka s jinym jmenem
if($tplData['prihlasen']){
    $tplHeaders->getHTMLHeaderPrihlasen($tplData['title'],$tplData['id_pravo']);
}else{
    $tplHeaders->getHTMLHeader($tplData['title']);
}


//Obsah stranky
?>
<div id="div_bg">
</div>
<div class="lingrad">
<div class="container d-xl-none">
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
<div class="container-fluid d-none d-xl-block">
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

// paticka
$tplHeaders->getHTMLFooter()

?>