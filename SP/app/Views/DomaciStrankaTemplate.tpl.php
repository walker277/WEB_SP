<?php
///////////////////////////////////////////////////////////////////////////
/////////// Sablona pro zobrazeni stranky se spravou uzivatelu  ///////////
///////////////////////////////////////////////////////////////////////////

//// pozn.: sablona je samostatna a provadi primy vypis do vystupu:
// -> lze testovat bez zbytku aplikace.
// -> pri vyuziti Twigu se sablona obejde bez PHP.

/*
////// Po zakomponovani do zbytku aplikace bude tato cast odstranena/zakomentovana  //////
//// UKAZKA DAT: Uvod bude vypisovat informace z tabulky, ktera ma nasledujici sloupce:
// id, date, author, title, text
$tplData['title'] = "Sprava uživatelů (TPL)";
$tplData['users'] = [
    array("id_user" => 1, "first_name" => "František", "last_name" => "Noha",
            "login" => "frnoha", "password" => "Tajne*Heslo", "email" => "fr.noha@ukazka.zcu.cz", "web" => "www.zcu.cz")
];
$tplData['delete'] = "Úspěšné mazání.";
define("DIRECTORY_VIEWS", "../Views");
const WEB_PAGES = array(
    "uvod" => array("title" => "Sprava uživatelů (TPL)")
);
////// KONEC: Po zakomponovani do zbytku aplikace bude tato cast odstranena/zakomentovana  //////
*/


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

/*// mam vypsat hlasku?
if(isset($tplData['delete'])){
    echo "<div class='alert'>$tplData[delete]</div>";
}

$res = "<table border><tr><th>ID</th><th>Jméno</th><th>Příjmení</th><th>Login</th><th>E-mail</th><th>Web</th><th>Akce</th></tr>";
// projdu data a vypisu radky tabulky
foreach($tplData['users'] as $u){
    $res .= "<tr><td>$u[id_user]</td><td>$u[first_name]</td><td>$u[last_name]</td><td>$u[login]</td><td>$u[email]</td><td>$u[web]</td>"
        ."<td><form method='post'>"
        ."<input type='hidden' name='id_user' value='$u[id_user]'>"
        ."<button type='submit' name='action' value='delete'>Smazat</button>"
        ."</form></td></tr>";
}

$res .= "</table>";
echo $res;*/

// paticka
$tplHeaders->getHTMLFooter()

?>