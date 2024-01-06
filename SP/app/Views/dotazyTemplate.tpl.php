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
    </div>
<div>

<?php
// paticka
$tplHeaders->getHTMLFooter()

?>
