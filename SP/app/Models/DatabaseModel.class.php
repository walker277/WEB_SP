<?php

/**
 * Trida spravujici databazi.
 */
class DatabaseModel{
    /** @var PDO $pdo  PDO objekt pro praci s databazi. */
    private $pdo;

    /** @var MySession $mySession  Vlastni objekt pro spravu session. */
    private $mySession;
    /** @var string $userSessionKey  Klic pro data uzivatele, ktera jsou ulozena v session. */
    private $userSessionKey = "current_user_id";

    /**
     * MyDatabase constructor.
     * Inicializace pripojeni k databazi a pokud ma byt spravovano prihlaseni uzivatele,
     * tak i vlastni objekt pro spravu session.
     * Pozn.: v samostatne praci by sprava prihlaseni uzivatele mela byt v samostatne tride.
     * Pozn.2: take je mozne do samostatne tridy vytahnout konkretni funkce pro praci s databazi.
     */
    public function __construct(){
        require_once("settings.inc.php");
        // inicialilzuju pripojeni k databazi - informace beru ze settings
        $this->pdo = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME, DB_USER, DB_PASS);
        $this->pdo->exec("set names utf8");
        // nastavení PDO error módu na výjimku, tj. každá chyba při práci s PDO bude výjimkou
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // inicializuju objekt pro praci se session - pouzito pro spravu prihlaseni uzivatele
        // pozn.: v samostatne praci vytvorte pro spravu prihlaseni uzivatele samostatnou tridu.
        require_once("MySession.class.php");
        $this->mySession = new MySession();
    }
    ///////////////////  Obecne funkce  ////////////////////////////////////////////

    /**
     *  Provede dotaz a bud vrati ziskana data, nebo pri chybe ji vypise a vrati null.
     *  Varianta, pokud NENI pouzit PDO::ERRMODE_EXCEPTION
     *
     *  @param string $dotaz        SQL dotaz.
     *  @return PDOStatement|null    Vysledek dotazu.
     */
    private function executeQueryWithoutException(string $dotaz){
        // vykonam dotaz
        $res = $this->pdo->query($dotaz);
        // pokud neni false, tak vratim vysledek, jinak null
        if ($res != false) {
            // neni false
            return $res;
        } else {
            // je false - vypisu prislusnou chybu a vratim null
            $error = $this->pdo->errorInfo();
            echo $error[2];
            return null;
        }
    }

    /**
     *  Provede dotaz a bud vrati ziskana data, nebo pri chybe ji vypise a vrati null.
     *  Varianta, pokud je pouzit PDO::ERRMODE_EXCEPTION
     *
     *  @param string $dotaz        SQL dotaz.
     *  @return PDOStatement|null    Vysledek dotazu.
     */
    private function executeQuery(string $dotaz){
        // vykonam dotaz
        try {
            $res = $this->pdo->query($dotaz);
            return $res;
        } catch (PDOException $ex){
            echo "Nastala výjimka: ". $ex->getCode() ."<br>"
                ."Text: ". $ex->getMessage();
            return null;
        }
    }

    /**
     * Jednoduche cteni z prislusne DB tabulky.
     *
     * @param string $tableName         Nazev tabulky.
     * @param string $whereStatement    Pripadne omezeni na ziskani radek tabulky. Default "".
     * @param string $orderByStatement  Pripadne razeni ziskanych radek tabulky. Default "".
     * @return array                    Vraci pole ziskanych radek tabulky.
     */
    public function selectFromTable(string $tableName, string $whereStatement = "", string $orderByStatement = ""):array {
        // slozim dotaz
        $q = "SELECT * FROM ".$tableName
            .(($whereStatement == "") ? "" : " WHERE $whereStatement")
            .(($orderByStatement == "") ? "" : " ORDER BY $orderByStatement");
        // provedu ho a vratim vysledek
        $obj = $this->executeQuery($q);
        // pokud je null, tak vratim prazdne pole
        if($obj == null){
            return [];
        }
        // projdu jednotlive ziskane radky tabulky
        /*while($row = $vystup->fetch(PDO::FETCH_ASSOC)){
            $pole[] = $row['login'].'<br>';
        }*/
        // prevedu vsechny ziskane radky tabulky na pole
        return $obj->fetchAll();
    }

    /**
     * Jednoduche vlozeni do prislusne tabulky.
     *
     * @param string $tableName         Nazev tabulky.
     * @param string $insertStatement   Text s nazvy sloupcu pro insert.
     * @param string $insertValues      Text s hodnotami pro prislusne sloupce.
     * @return bool                     Vlozeno v poradku?
     */
    public function insertIntoTable(string $tableName, string $insertStatement, string $insertValues):bool {
        // slozim dotaz
        $q = "INSERT INTO $tableName($insertStatement) VALUES ($insertValues)";
        // provedu ho a vratim uspesnost vlozeni
        $obj = $this->executeQuery($q);
        // pokud ($obj == null), tak vratim false
        return ($obj != null);
    }

    /**
     * Jednoducha uprava radku databazove tabulky.
     *
     * @param string $tableName                     Nazev tabulky.
     * @param string $updateStatementWithValues     Cela cast updatu s hodnotami.
     * @param string $whereStatement                Cela cast pro WHERE.
     * @return bool                                 Upraveno v poradku?
     */
    public function updateInTable(string $tableName, string $updateStatementWithValues, string $whereStatement):bool {
        // slozim dotaz
        $q = "UPDATE $tableName SET $updateStatementWithValues WHERE $whereStatement";
        // provedu ho a vratim vysledek
        $obj = $this->executeQuery($q);
        // pokud ($obj == null), tak vratim false
        return ($obj != null);
    }

    /**
     * Dle zadane podminky maze radky v prislusne tabulce.
     *
     * @param string $tableName         Nazev tabulky.
     * @param string $whereStatement    Podminka mazani.
     * @return bool
     */
    public function deleteFromTable(string $tableName, string $whereStatement):bool {
        // slozim dotaz
        $q = "DELETE FROM $tableName WHERE $whereStatement";
        // provedu ho a vratim vysledek
        $obj = $this->executeQuery($q);
        // pokud ($obj == null), tak vratim false
        return ($obj != null);
    }

    ///////////////////  KONEC: Obecne funkce  ////////////////////////////////////////////

    ///////////////////  Konkretni funkce  ////////////////////////////////////////////

    /**
     * Ziskani zaznamu vsech uzivatelu aplikace.
     *
     * @return array    Pole se vsemi uzivateli.
     */
    public function getAllUsers(){
        // ziskam vsechny uzivatele z DB razene dle ID a vratim je
        $users = $this->selectFromTable(TABLE_UZIVATEL, "", "id_uzivatel");
        return $users;
    }

    /**
     * Ziskani zaznamu vsech prav uzivatelu.
     *
     * @return array    Pole se vsemi pravy.
     */
    public function getAllRights(){
        // ziskam vsechna prava z DB razena dle ID a vratim je
        $rights = $this->selectFromTable(TABLE_PRAVO, "", "vaha ASC, nazev ASC");
        return $rights;
    }

    /**
     * Ziskani zaznamu vsech clanku aplikace.
     *
     * @return array    Pole se vsemi uzivateli.
     */
    public function getAllClanky(){
        // ziskam vsechny uzivatele z DB razene dle ID a vratim je
        $clanky = $this->selectFromTable(TABLE_CLANEK, "", "idCLANEK");
        if($clanky != null){
            return $clanky;
        }else{
            return null;
        }
    }

    public function getAllClankyAutoru(){
        // ziskam vsechny uzivatele z DB razene dle ID a vratim je
        $clanky = $this->selectFromTable(TABLE_CLANKY_AUTORA, "", "idClankyAutora");
        if($clanky != null){
            return $clanky;
        }else{
            return null;
        }
    }

    /**
     * Ziskani zaznamu vsech id clanku aplikace uzivatele.
     *
     * @return array    Pole se vsemi uzivateli.
     */
    public function getAllAutoroviClankyID($uzivatelID, $clankyAutoru){
        // ziskam vsechny uzivatele z DB razene dle ID a vratim je

        $IDclankuUzivatele = [];
        if($clankyAutoru != null){
            foreach ($clankyAutoru as $cA){
                //pokud uzivatel clanek vytvoril
                if($cA['id_uzivatel'] == $uzivatelID){
                    //priradime id clanku do pole
                    $IDclankuUzivatele[] = $cA['idCLANEK'];
                }
            }
            if($IDclankuUzivatele != null){
                //vratime pole s ID clankama
                return $IDclankuUzivatele;
            }else{
                return null;
            }
        }else{
            return null;
        }

    }

    /**
     * Ziskani zaznamu vsech clanku aplikace uzivatele.
     *
     * @return array    Pole se vsemi uzivateli.
     */
    public function getAllAutoroviClanky($IDclankuUzivatele, $clanky){
        $autoroviClanky = [];
        //jdeme postupne pres vsechny ID clanku ktere uzivatel vytvoril
        if($clanky != null && $IDclankuUzivatele != null){
            foreach ($IDclankuUzivatele as $idClanku){
                // a jdeme pres clanky
                foreach ($clanky as $c){
                    //var_dump($c);
                    //pokud se id clanku rovnaji pak clanek priradime do pole
                    if($idClanku == $c['idCLANEK']){
                        $autoroviClanky[] = $c;
                    }
                }
            }
        }
        if($autoroviClanky!= null){
            return $autoroviClanky;
        }else{
            return null;
        }
    }

    /**
     * Metoda vrati id uzivatelu kteří clanky vytvareli
     * @param $IDclankuAutora
     * @param $clankyAutoru
     * @return array|mixed
     */
    public function getAllUzivIdClanku($IDclankuAutora, $clankyAutoru){
        $idUzivatelu = [];
        $autoriVClanku = [];
        if($IDclankuAutora != null && $clankyAutoru != null){
            //jdeme pres vsechny id clanku autora
            foreach ($IDclankuAutora as $idA){
                //jedeme pres vsechny clankyAutoru
                foreach ($clankyAutoru as $cA){
                    //pokud se id clanku autora shoduje s id clanku jineho autora
                    if($idA == $cA['idCLANEK']){
                        $idUzivatelu[] = $cA['id_uzivatel'];
                    }
                }
                //pole se vsema id uzivatelu konkretniho clanku si ulozime do pole
                $autoriVClanku[] = $idUzivatelu;
                //vynullujeme a pro dalsi pruchod
                $idUzivatelu = null;
            }
        }
        //var_dump($idUzivatelu);
        //var_dump($autoriVClanku);
        if($autoriVClanku != null){
            return $autoriVClanku;
        }else{
            return null;
        }
    }

    /**
     * Metoda vrati jmena uzivatelu kteri clanky vytvareli
     * @param $IDAutoru
     * @param $uzivatele
     * @return array|mixed
     */
    public function getAllUzivClanku($IDAutoruClanku, $uzivatele){
        $jmenaUziv = [];
        $poleAutoruClanku = [];
        if($IDAutoruClanku != null){
            //jdeme pres vsechny clanky
            foreach ($IDAutoruClanku as $idAC){
                ///jdeme pres vsechny id uzivatelu konkretniho clanku
                foreach ($idAC as $idU){
                    //var_dump($idU);
                    //jedeme pres vsechny clankyAutoru
                    foreach ($uzivatele as $u){
                        //pokud se id autora clanku shoduje s id uzivatele
                        if($idU == $u['id_uzivatel']){
                            //pridame jmeno uzivatele do pole
                            $jmenaUziv[] = $u['jmeno_prijmeni'];
                        }
                    }
                    $autori = '';
                    // spojíme vsechny jmena do jednoho stringu
                    foreach ($jmenaUziv as $autor){
                        if($autori === ''){
                            $autori = $autor;
                        }else{
                            $autori = $autori . ', ' . $autor;
                        }
                    }
                    //pridame string autoru jednoho clanku do pole
                    $poleAutoruClanku[] = $autori;
                    //z resetujeme pro dalsi pruchod
                    $jmenaUziv = null;
                }
            }
        }
        if($poleAutoruClanku != null){
            return $poleAutoruClanku;
        }else{
            return null;
        }
    }

    /**
     * Ziskani konkretniho prava uzivatele dle ID prava.
     *
     * @param int $id       ID prava.
     * @return array        Data nalezeneho prava.
     */
    public function getRightById(int $id){
        // ziskam pravo dle ID
        $rights = $this->selectFromTable(TABLE_PRAVO, "id_pravo=$id");
        if(empty($rights)){
            return null;
        } else {
            // vracim prvni nalezene pravo
            return $rights[0];
        }
    }

    /**
     * Ziskani konkretniho prava uzivatele dle ID uzivatele.
     *
     * @param int $id       ID prava.
     * @return array        Data nalezeneho uzivatele.
     */
    public function getUserById(int $id){
        // ziskam pravo dle ID
        $users = $this->selectFromTable(TABLE_UZIVATEL, "id_uzivatel=$id");
        if(empty($users)){
            return null;
        } else {
            // vracim prvni nalezene pravo
            return $users[0];
        }
    }

    /**
     * Vytvoreni noveho uzivatele v databazi.
     *
     * @param string $login     Login.
     * @param string $jmeno     Jmeno.
     * @param string $email     E-mail.
     * @param int $idPravo      Je cizim klicem do tabulky s pravy.
     * @return bool             Vlozen v poradku?
     */
    public function addNewUser(string $login, string $heslo, string $jmeno, string $email, int $idPravo, string $pohlavi, string $datum){
        $idPravo = 4;
        $zablokovany = 0;
        // hlavicka pro vlozeni do tabulky uzivatelu
        $insertStatement = "id_pravo, jmeno_prijmeni, username, password, email, pohlavi, datum_narozeni, Zablokovany";
        // hodnoty pro vlozeni do tabulky uzivatelu
        //$insertValues = "'$login', '$heslo', '$jmeno', '$email', $idPravo";
        $insertValues = "'$idPravo', '$jmeno', '$login', '$heslo', '$email', '$pohlavi', '$datum', '$zablokovany'";
        // provedu dotaz a vratim jeho vysledek
        return $this->insertIntoTable(TABLE_UZIVATEL, $insertStatement, $insertValues);
    }

    /**
     * Uprava konkretniho uzivatele v databazi.
     *
     * @param int $idUzivatel   ID upravovaneho uzivatele.
     * @param string $login     Login.
     * @param string $heslo     Heslo.
     * @param string $jmeno     Jmeno.
     * @param string $email     E-mail.
     * @param int $idPravo      ID prava.
     * @return bool             Bylo upraveno?
     */
    public function updateUser(int $idUzivatel, string $login, string $heslo, string $jmeno, string $email, int $idPravo){
        // slozim cast s hodnotami
        $updateStatementWithValues = "login='$login', heslo='$heslo', jmeno='$jmeno', email='$email', id_pravo='$idPravo'";
        // podminka
        $whereStatement = "id_uzivatel=$idUzivatel";
        // provedu update
        return $this->updateInTable(TABLE_UZIVATEL, $updateStatementWithValues, $whereStatement);
    }

    /**
     * Uprava konkretniho uzivatele v databazi.
     * @param int $idUzivatel   ID upravovaneho uzivatele.
     * @return bool             Bylo upraveno?
     */
    public function updateUserEmail(int $idUzivatel,  string $email){
        // slozim cast s hodnotami
        $updateStatementWithValues = " email='$email'";
        // podminka
        $whereStatement = "id_uzivatel=$idUzivatel";
        // provedu update
        return $this->updateInTable(TABLE_UZIVATEL, $updateStatementWithValues, $whereStatement);
    }

    /**
     * Uprava konkretniho uzivatele v databazi.
     *
     * @param int $idUzivatel   ID upravovaneho uzivatele.
     * @param string $login     Login.
     * @return bool             Bylo upraveno?
     */
    public function updateUsername(int $idUzivatel, string $login){
        // slozim cast s hodnotami
        $updateStatementWithValues = " username='$login'";
        // podminka
        $whereStatement = "id_uzivatel=$idUzivatel";
        // provedu update
        return $this->updateInTable(TABLE_UZIVATEL, $updateStatementWithValues, $whereStatement);
    }

    /**
     * Uprava konkretniho uzivatele v databazi.
     *
     * @param int $idUzivatel   ID upravovaneho uzivatele.
     * @return bool             Bylo upraveno?
     */
    public function updateUserJmeno(int $idUzivatel, string $jmeno){
        // slozim cast s hodnotami
        $updateStatementWithValues = " jmeno_prijmeni='$jmeno'";
        // podminka
        $whereStatement = "id_uzivatel=$idUzivatel";
        // provedu update
        return $this->updateInTable(TABLE_UZIVATEL, $updateStatementWithValues, $whereStatement);
    }

    /**
     * Uprava konkretniho uzivatele v databazi.
     *
     * @param int $idUzivatel   ID upravovaneho uzivatele.
     * @param string $heslo     Heslo.
     * @return bool             Bylo upraveno?
     */
    public function updateUserPass(int $idUzivatel, string $heslo){
        //zahashovani hesla
        $hash = password_hash($heslo, PASSWORD_BCRYPT);
        // slozim cast s hodnotami
        $updateStatementWithValues = "password='$hash'";
        // podminka
        $whereStatement = "id_uzivatel=$idUzivatel";
        // provedu update
        return $this->updateInTable(TABLE_UZIVATEL, $updateStatementWithValues, $whereStatement);
    }
    /**
     * Uprava konkretniho uzivatele v databazi.
     *
     * @param int $idUzivatel   ID upravovaneho uzivatele.
     * @param int $idPravo      ID prava.
     * @return bool             Bylo upraveno?
     */
    public function updateUserRight(int $idUzivatel, int $idPravo){
        $updateStatementWithValues = "id_pravo='$idPravo'";
        // podminka
        $whereStatement = "id_uzivatel=$idUzivatel";
        // provedu update
        return $this->updateInTable(TABLE_UZIVATEL, $updateStatementWithValues, $whereStatement);
    }

    /**
     * Uprava konkretniho uzivatele v databazi.
     * @param string $pohlavi   pohlavi
     * @param int $idUzivatel   ID upravovaneho uzivatele.
     * @return bool             Bylo upraveno?
     */
    public function updateUserGender(int $idUzivatel, string $pohlavi){
        $updateStatementWithValues = "pohlavi='$pohlavi'";
        // podminka
        $whereStatement = "id_uzivatel=$idUzivatel";
        // provedu update
        return $this->updateInTable(TABLE_UZIVATEL, $updateStatementWithValues, $whereStatement);
    }

    /**
     * Uprava konkretniho uzivatele v databazi.
     * @param string $date      datum narozeni
     * @param int $idUzivatel   ID upravovaneho uzivatele.
     * @return bool             Bylo upraveno?
     */
    public function updateUserDate(int $idUzivatel, string $date){
        $updateStatementWithValues = "datum_narozeni='$date'";
        // podminka
        $whereStatement = "id_uzivatel=$idUzivatel";
        // provedu update
        return $this->updateInTable(TABLE_UZIVATEL, $updateStatementWithValues, $whereStatement);
    }
    /**
     * Uprava konkretniho uzivatele v databazi.
     * @param int $povol     int signalizujici povoleni
     * @param int $idUzivatel   ID upravovaneho uzivatele.
     * @return bool             Bylo upraveno?
     */
    public function updateZablokovaniUzivatele(int $idUzivatel, int $povol){
        $updateStatementWithValues = "Zablokovany='$povol'";
        // podminka
        $whereStatement = "id_uzivatel=$idUzivatel";
        // provedu update
        return $this->updateInTable(TABLE_UZIVATEL, $updateStatementWithValues, $whereStatement);
    }
    ///////////////////  KONEC: Konkretni funkce  ////////////////////////////////////////////

    ///////////////////  Sprava prihlaseni uzivatele  ////////////////////////////////////////

    /**
     * Overi, zda muse byt uzivatel prihlasen a pripadne ho prihlasi.
     *
     * @param string $login     Login uzivatele.
     * @param string $heslo     Heslo uzivatele.
     * @return bool             Byl prihlasen?
     */
    public function userLogin(string $login, string $heslo):bool {
        // ziskam uzivatele z DB - primo overuju login i heslo
        $where = "username='$login'";
        $user = $this->selectFromTable(TABLE_UZIVATEL, $where);

        // ziskal jsem uzivatele
        if(count($user)){
            if(password_verify($heslo, $user[0]['password'])){//test jestli uzivatelovo heslo v databazi se shoduje s heslem zadanym
                // ziskal - ulozim ID prvniho nalezeneho uzivatele do session
                $_SESSION[$this->userSessionKey] = $user[0]['id_uzivatel']; // beru prvniho nalezeneho a ukladam jen jeho ID
                return true;
            }else{
                return false;
            }
        }else{//neziskal jsem uzivatele
            return false;
        }

    }

    /**
     * Odhlasi soucasneho uzivatele.
     */
    public function userLogout(){
        unset($_SESSION[$this->userSessionKey]);
    }

    /**
     * Test, zda je nyni uzivatel prihlasen.
     *
     * @return bool     Je prihlasen?
     */
    public function isUserLogged():bool {
        return isset($_SESSION[$this->userSessionKey]);
    }

    /**
     * Pokud je uzivatel prihlasen, tak vrati jeho data,
     * ale pokud nebyla v session nalezena, tak vypise chybu.
     *
     * @return mixed|null   Data uzivatele nebo null.
     */
    public function getLoggedUserData(){
        if($this->isUserLogged()){
            // ziskam data uzivatele ze session
            $userId = $_SESSION[$this->userSessionKey];
            // pokud nemam data uzivatele, tak vypisu chybu a vynutim odhlaseni uzivatele
            if($userId == null) {
                // nemam data uzivatele ze session - vypisu jen chybu, uzivatele odhlasim a vratim null
                echo "SEVER ERROR: Data přihlášeného uživatele nebyla nalezena, a proto byl uživatel odhlášen.";
                $this->userLogout();
                // vracim null
                return null;
            }else{
                // nactu data uzivatele z databaze
                $userData = $this->selectFromTable(TABLE_UZIVATEL, "id_uzivatel=$userId");
                // mam data uzivatele?
                if(empty($userData)){
                    // nemam - vypisu jen chybu, uzivatele odhlasim a vratim null
                    echo "ERROR: Data přihlášeného uživatele se nenachází v databázi (mohl být smazán), a proto byl uživatel odhlášen.";
                    $this->userLogout();
                    return null;
                }else{
                    // protoze DB vraci pole uzivatelu, tak vyjmu jeho prvni polozku a vratim ziskana data uzivatele
                    return $userData[0];
                }
            }
        }else{
            // uzivatel neni prihlasen - vracim null
            return null;
        }
    }

    public function getLoggedUserID(){
        if($this->isUserLogged()){
            // ziskam data uzivatele ze session
            $userId = $_SESSION[$this->userSessionKey];
            // pokud nemam data uzivatele, tak vypisu chybu a vynutim odhlaseni uzivatele
            if($userId == null) {
                // nemam data uzivatele ze session - vypisu jen chybu, uzivatele odhlasim a vratim null
                echo "SEVER ERROR: Data přihlášeného uživatele nebyla nalezena, a proto byl uživatel odhlášen.";
                $this->userLogout();
                // vracim null
                return null;
            }else{
                return $_SESSION[$this->userSessionKey];
            }
        }else{
            // uzivatel neni prihlasen - vracim null
            return null;
        }
    }

    /**
     * Vrati true pokud je volne false pokud neni
     * @param $usernameR string obsahujici uzivatelske jmeno
     * @param $users
     * @return boolean
     */
    public function jeUsernameVolne($usernameR, $users):bool{
        $volne = true;
        foreach ($users as $u){
            if ($u['username'] == $usernameR){
                $volne = false;
                break;
            }
        }
        return $volne;
    }

    public function addNewClanek(string $clanek, string $cestaKsouboru, string $abstrakt, string $autori):bool{
        $nula = 0;
        $null = NULL;
        //vlozeni noveho clanku do tabulky
        $insertStatement = "schvalen, recenzent_1, recenzent_2, recenzent_3, hodnoceni_1, hodnoceni_2, hodnoceni_3, nazev, abstrakt, cesta, autori";
        // hodnoty pro vlozeni do tabulky uzivatelu
        $insertValues = "'0', '0', '0', '0', '0', '0', '0', '$clanek', '$abstrakt', '$cestaKsouboru', '$autori'";
        // provedu dotaz a vratim jeho vysledek
        return $this->insertIntoTable(TABLE_CLANEK, $insertStatement, $insertValues);
    }
    public function getPosledniClanek(){
        $clanek = $this->selectFromTable(TABLE_CLANEK, "idCLANEK = (SELECT MAX(idCLANEK) FROM CLANEK)");
        // vracim prvni nalezene pravo
        return $clanek[0];

    }
    public function addNewClankyAutora($idClanku, $id_prihlasenehoU){
        $insertStatement = "id_uzivatel, idCLANEK";
        $insertValues = "'$id_prihlasenehoU', '$idClanku'";
        return $this->insertIntoTable(TABLE_CLANKY_AUTORA, $insertStatement, $insertValues);
    }

    /**
     * Metoda vrati pocet autoru
     * @param $uzivatele
     * @return int
     */
    public function getPocetAutoru($uzivatele){
        $pocet = 0;
        foreach ($uzivatele as $u){
            if($u['id_pravo']==4 && $u['Zablokovany'] == 0){
                $pocet++;
            }
        }
        return $pocet;
    }
    ///////////////////  KONEC: Sprava prihlaseni uzivatele  ////////////////////////////////////////

}

?>



