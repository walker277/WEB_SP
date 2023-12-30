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
<div class="lingrad ">
    <div class="container-fluid">
        <?php
//ulozeni prava aktualne prihlaseneho uzivatele
$p = $tplData['id_pravo'];


if($p < 3) { //tabulka neni urcena pro recenzenty nebo autory
    ?>
    <h2 class="py-3 font-weight-bold text-primary">Seznam uživatelů</h2>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-secondary table-hover">
            <thead class="thead-dark text-center">
            <tr><th>ID</th><th>Uživatelské Jméno</th><th>Jméno</th><th>E-mail</th>
                <th>Změnit právo</th><th>Pohlaví</th><th>Datum narození</th><th>Heslo</th><th>Zablokovat/Povolit</th><th>Smazat</th></tr>
            </thead>
            <?php
            // projdu uzivatele a vypisu je
            foreach ($tplData['uzivatele'] as $u) {
                //pokud je pravo uzivatele vetsi nez prihlaseneho nebo je prihlaseny uzivatel autro ci recenzent,
                // tak ho preskocime protoze prihlaseny nemuze uzivatele menit, popripade tato tabulka pro nej neni urcena
                if($u['id_pravo'] <= $p ){
                    continue;
                }
                echo "<tr>
                              <td class='align-middle text-center'>
                                $u[id_uzivatel]
                              </td>
                              <td class='align-middle text-center'>
                                <div>
                                   $u[username] 
                                </div>                        
                                <form action='' method='post'>                                    
                                    <div class='py-3'>
                                        <input type='text' class='form-control text-center' placeholder='Nový username' name='usernameSez' required >
                                    </div>
                                    <div class='py-3'>
                                        <input type='hidden' name='id_uziva' value='$u[id_uzivatel]'>
                                        <button class='btn btn-outline-dark' type='submit' name='potvrzeni' value='zmenit'>zmenit</button>
                                    </div>
                                </form>                           
                              </td>
                              <td class='align-middle text-center'>
                                <div>
                                   $u[jmeno_prijmeni] 
                                </div>
                                <form action='' method='post'>                                    
                                    <div class='py-3'>
                                        <input type='text' class='form-control text-center' placeholder='Nové jméno' name='jmenoSez' required >
                                    </div>
                                    <div class='py-3'>
                                        <input type='hidden' name='id_uzivat' value='$u[id_uzivatel]'>
                                        <button class='btn btn-outline-dark' type='submit' name='potvrzeni' value='zmenit'>zmenit</button>
                                    </div>
                                </form>   
                              </td>
                              <td class='align-middle text-center'>
                                <div>
                                   $u[email] 
                                </div>                                  
                                <form action='' method='post'>                                    
                                    <div class='py-3'>
                                        <input type='text' class='form-control text-center' placeholder='Nový email' name='emailSez' required >
                                    </div>
                                    <div class='py-3'>
                                        <input type='hidden' name='id_uziv' value='$u[id_uzivatel]'>
                                        <button class='btn btn-outline-dark' type='submit' name='potvrzeni' value='zmenit'>zmenit</button>
                                    </div>
                                </form>
                              </td>
                              <td class='align-middle text-center'>"
                    ."<form action='' method='post'>"
                    ."<div class='py-3'>"
                    ."<select name='pravo'>";
                foreach ($tplData['prava'] as $r){
                    $selected = ($r['id_pravo'] == $u['id_pravo']) ? "selected" : "";
                    //pokud je prihlaseny superAdmin nebo se jedna o pravo prihlaseneho
                    if($tplData['uzivatel']['id_pravo'] == 1 || $r['id_pravo'] == $tplData['uzivatel']){
                        //vsechna prava krome superAdmina nebo pokud se jedna o pravo prihlaseneho
                        if($r['id_pravo'] > 1 || $r['id_pravo'] == $u['id_pravo'] ){
                            echo "<option value ='$r[id_pravo]' $selected>$r[nazev]</option>";
                        }
                    }else{//pokud je prihlaseny Admin nebo se jedna o pravo prihlaseneho
                        //vsechna prava krome superAdmina a Admina nebo pokud se jedna o pravo prihlaseneho
                        if($r['id_pravo'] > 2 || $r['id_pravo'] == $u['id_pravo'] ){
                            echo "<option value ='$r[id_pravo]' $selected>$r[nazev]</option>";
                        }
                    }
                }
                echo "</select>"
                    ."</div>"
                    ."<div class='py-3'>"
                    ."<input type='hidden' name='id_u' value='$u[id_uzivatel]'>"
                    ."<button class='btn btn-outline-dark' type='submit' name='potvrzeni' value='zmenit'>zmenit</button>"
                    ."</div>"
                    ."</form>"
                    ."</td>"
                    ."<td class='align-middle text-center'>"
                    ."<form action='' method='post'>"
                    ."<div class='py-3'>"
                    ."<select name='pohlavi'>";
                echo "<option value =$u[pohlavi]>$u[pohlavi]</option>";
                $pohlavi = ($u['pohlavi'] == 'muz') ? 'zena' : 'muz';
                echo "<option value =$pohlavi>$pohlavi</option>"
                    ."</select>"
                    ."</div>"
                    ."<div class='py-3'>"
                    ."<input type='hidden' name='id_uz' value='$u[id_uzivatel]'>"
                    ."<button class='btn btn-outline-dark' type='submit' name='potvrzeni' value='zmenit'>zmenit</button>"
                    ."</div>"
                    ."</form>"
                    ."<td class='align-middle text-center'>"
                    ."<div>"
                    ."$u[datum_narozeni]"
                    ."</div> "
                    ."<form action='' method='post'>"
                    ."<div class='py-3'>"
                    ."<input type='date' class='form-control' value=$u[datum_narozeni] name='datum' required >"
                    ."</div>"
                    ."<div class='py-2'>"
                    ."<input type='hidden' name='id_uzi' value='$u[id_uzivatel]'>"
                    ."<button class='btn btn-outline-dark' type='submit' name='potvrzeni' value='zmenit'>zmenit</button>"
                    ."</div>"
                    ."</form>"
                    ."</td>
                            .<td class='align-middle text-center'>                                                               
                                <form action='' method='post'>                                    
                                    <div class='py-3'>
                                        <input type='text' class='form-control text-center' placeholder='Nové heslo' name='hesloSez' required >
                                    </div>
                                    <div class='py-3'>
                                        <input type='hidden' name='id_uzivate' value='$u[id_uzivatel]'>
                                        <button class='btn btn-outline-dark' type='submit' name='potvrzeni' value='zmenit'>zmenit</button>
                                    </div>
                                </form>
                            .</td>
                    .<td class='align-middle text-center'>"
                    ."<form action='' method='POST'>";
                          echo"<input type='hidden' name='id_uzivatell' value='$u[id_uzivatel]'> ";
                          if($u['Zablokovany'] == 1){
                              echo "<button class='btn btn-outline-success' type='submit' name='potvrzeni' value='0'>Povolit</button>";
                          }else{
                              echo "<button class='btn btn-outline-danger' type='submit' name='potvrzeni' value='1'>Zablokovat</button>";
                          }
                    echo "</form>"
                    ."</td>        
                    .<td class='align-middle text-center'>"
                    ."<form action='' method='POST'>
                                      <input type='hidden' name='id_uzivatel' value='$u[id_uzivatel]'>
                                      <button class='btn btn-dark' type='submit' name='potvrzeni' value='Smazat'>Smazat</button>
                                </form>"
                    ."</td></tr>";
            }
}
            ?>
        </table>
    </div>
    </div>
</div>
<?php
// paticka
$tplHeaders->getHTMLFooter()

?>
