<?php

/**
 * Trida spravujici databazi.
 */
class DatabaseModel{
    /** @var PDO $pdo  PDO objekt pro praci s databazi. */
    private PDO $pdo;

    /** @var MySession $mySession  Vlastni objekt pro spravu session. */
    private MySession $mySession;

    /** @var string $userSessionKey  Klic pro data uzivatele, ktera jsou ulozena v session. */
    private string $userSessionKey = "current_user_id";

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
     * Jednoduche cteni z prislusne DB tabulky.
     *
     * @param string $tableName         Nazev tabulky.
     * @param string $whereStatement    Pripadne omezeni na ziskani radek tabulky. Default "".
     * @param string $orderByStatement  Pripadne razeni ziskanych radek tabulky. Default "".
     * @return array                    Vraci pole ziskanych radek tabulky.
     */
    public function selectFromTable(string $tableName, string $whereStatement, string $orderByStatement, array $poleKlicu, array $poleHodnot):array {
        $tableName = htmlspecialchars($tableName);
        $whereStatement = htmlspecialchars($whereStatement);
        $orderByStatement = htmlspecialchars($orderByStatement);

        $poleHodnot = array_map('htmlspecialchars', $poleHodnot);
        $poleKlicu = array_map('htmlspecialchars', $poleKlicu);

        // slozim dotaz
        $q = "SELECT * FROM ".$tableName
            .(($whereStatement == "") ? "" : " WHERE $whereStatement")
            .(($orderByStatement == "") ? "" : " ORDER BY $orderByStatement");

        $vystup = $this->pdo->prepare($q);


        if($poleKlicu != [] && $poleHodnot != []){
            //projdeme pres vsechny hodnoty podle kterych mame urcenou podminku kde
            for ($i = 0; $i < count($poleHodnot); $i++){
                //pripravime klic
                $klic = $poleKlicu[$i];
                //pripravime hodnotu
                $hodnota = $poleHodnot[$i];
                //nabindujeme klic s hodnotou
                $vystup->bindValue($klic,$hodnota);
            }
       }


        if($vystup->execute()){
            //dotaz probehl v poradku
            //vsechny radky do pole a vratime
            return $vystup->fetchAll();
        }else{
            return [];
        }
    }

    /**
     * Jednoduche vlozeni do prislusne tabulky.
     *
     * @param string $tableName         Nazev tabulky.
     * @param string $insertStatement   Text s nazvy sloupcu pro insert.
     * @param string $insertValues      Text s hodnotami pro prislusne sloupce.
     * @return bool                     Vlozeno v poradku?
     */
    public function insertIntoTable(string $tableName, string $insertStatement, string $insertValues, array $poleKlicu, array $poleHodnot):bool {

        // slozim dotaz
        $q = "INSERT INTO $tableName($insertStatement) VALUES ($insertValues)";
        $vystup = $this->pdo->prepare($q);

        if($poleKlicu != [] && $poleHodnot != []){
            //projdeme pres vsechny hodnoty podle kterych mame urcenou podminku kde
            for ($i = 0; $i < count($poleHodnot); $i++){
                //pripravime klic
                $klic = $poleKlicu[$i];
                //pripravime hodnotu
                $hodnota = $poleHodnot[$i];
                //nabindujeme klic s hodnotou
                $vystup->bindValue($klic,$hodnota);
            }
        }

        if($vystup->execute()){
            return true;
        }else{
            return false;
        }
        /*// provedu ho a vratim uspesnost vlozeni
        $obj = $this->executeQuery($q);
        // pokud ($obj == null), tak vratim false
        return ($obj != null);*/
    }

    /**
     * Jednoducha uprava radku databazove tabulky.
     *
     * @param string $tableName                     Nazev tabulky.
     * @param string $updateStatementWithValues     Cela cast updatu s hodnotami.
     * @param string $whereStatement                Cela cast pro WHERE.
     * @return bool                                 Upraveno v poradku?
     */
    public function updateInTable(string $tableName, string $updateStatementWithValues, string $whereStatement, $poleKlicu, $poleHodnot):bool {
        $poleHodnot = array_map('htmlspecialchars', $poleHodnot);
        $poleKlicu = array_map('htmlspecialchars', $poleKlicu);

        // slozim dotaz
        $q = "UPDATE $tableName SET $updateStatementWithValues WHERE $whereStatement";

        $vystup = $this->pdo->prepare($q);


        if($poleKlicu != [] && $poleHodnot != []){
            //projdeme pres vsechny hodnoty podle kterych mame urcenou podminku kde
            for ($i = 0; $i < count($poleHodnot); $i++){
                //pripravime klic
                $klic = $poleKlicu[$i];
                //pripravime hodnotu
                $hodnota = $poleHodnot[$i];
                //nabindujeme klic s hodnotou
                $vystup->bindValue($klic,$hodnota);
            }
        }


        if($vystup->execute()){
            //dotaz probehl v poradku
            //vsechny radky do pole a vratime
            return true;
        }else{
            return false;
        }
    }

    /**
     * Dle zadane podminky maze radky v prislusne tabulce.
     *
     * @param string $tableName         Nazev tabulky.
     * @param string $whereStatement    Podminka mazani.
     * @return bool
     */
    public function deleteFromTable(string $tableName, string $whereStatement, array $poleKlicu, array $poleHodnot):bool {
        $poleHodnot = array_map('htmlspecialchars', $poleHodnot);
        $poleKlicu = array_map('htmlspecialchars', $poleKlicu);

        // slozim dotaz
        $q = "DELETE FROM $tableName WHERE $whereStatement";

        $vystup = $this->pdo->prepare($q);


        if($poleKlicu != [] && $poleHodnot != []){
            //projdeme pres vsechny hodnoty podle kterych mame urcenou podminku kde
            for ($i = 0; $i < count($poleHodnot); $i++){
                //pripravime klic
                $klic = $poleKlicu[$i];
                //pripravime hodnotu
                $hodnota = $poleHodnot[$i];
                //nabindujeme klic s hodnotou
                $vystup->bindValue($klic,$hodnota);
            }
        }


        if($vystup->execute()){
            //dotaz probehl v poradku
            //vsechny radky do pole a vratime
            return true;
        }else{
            return false;
        }

    }

    ///////////////////  KONEC: Obecne funkce  ////////////////////////////////////////////

    ///////////////////  Konkretni funkce  ////////////////////////////////////////////

    /**
     * Ziskani zaznamu vsech uzivatelu aplikace.
     *
     * @return array    Pole se vsemi uzivateli.
     */
    public function getAllUsers(): array
    {
        // ziskam vsechny uzivatele z DB razene dle ID a vratim je
        return $this->selectFromTable(TABLE_UZIVATEL, "", "id_uzivatel", [], []);
    }

    /**
     * Ziskani zaznamu vsech prav uzivatelu.
     *
     * @return array    Pole se vsemi pravy.
     */
    public function getAllRights(): array {
        // ziskam vsechna prava z DB razena dle ID a vratim je
        return $this->selectFromTable(TABLE_PRAVO, "", "vaha ASC, nazev ASC", [], []);
    }

    /**
     * Ziskani zaznamu vsech clanku aplikace.
     *
     * @return ?array    Pole se vsemi uzivateli.
     */
    public function getAllClanky(): ?array {
        // ziskam vsechny uzivatele z DB razene dle ID a vratim je
        $clanky = $this->selectFromTable(TABLE_CLANEK, "", "idCLANEK", [],[]);
        if($clanky != null){
            return $clanky;
        }else{
            return null;
        }
    }

    public function getAllDotazy(): ?array {
        // ziskam vsechny uzivatele z DB razene dle ID a vratim je
        $dotazy = $this->selectFromTable(TABLE_DOTAZ, "", "id_dotaz",[],[]);
        if($dotazy != null){
            return $dotazy;
        }else{
            return null;
        }
    }

    public function getAllClankyAutoru(): ?array {
        // ziskam vsechny uzivatele z DB razene dle ID a vratim je
        $clanky = $this->selectFromTable(TABLE_CLANKY_AUTORA, "", "idClankyAutora",[],[]);
        if($clanky != null){
            return $clanky;
        }else{
            return null;
        }
    }

    /**
     * Ziskani zaznamu vsech id clanku aplikace uzivatele.
     *
     * @return ?array    Pole se vsemi uzivateli.
     */
    public function getAllAutoroviClankyID($uzivatelID, $clankyAutoru): ?array {
        // ziskam vsechny uzivatele z DB razene dle ID a vratim je
        $uzivatelID = htmlspecialchars($uzivatelID);
        foreach ($clankyAutoru as $clanek) {
            // Projděte každý článek a escapujte hodnoty
            foreach ($clanek as $hodnota) {
                if($hodnota != null) {
                    $hodnota = htmlspecialchars($hodnota);
                }
            }
        }

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
     * @return ?array    Pole se vsemi uzivateli.
     */
    public function getAllAutoroviClanky($IDclankuUzivatele, $clanky): ?array {
        if($IDclankuUzivatele != null){
            $IDclankuUzivatele = array_map('htmlspecialchars', $IDclankuUzivatele);
        }

        foreach ($clanky as $clanek) {
            // Projděte každý článek a escapujte hodnoty
            foreach ($clanek as $hodnota) {
                if($hodnota != null){
                    $hodnota = htmlspecialchars($hodnota);
                }
            }
        }

        $autoroviClanky = [];
        //jdeme postupne pres vsechny ID clanku ktere uzivatel vytvoril
        if($clanky != null && $IDclankuUzivatele != null){
            foreach ($IDclankuUzivatele as $idClanku){
                // a jdeme pres clanky
                foreach ($clanky as $c){

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
     * @return ?array
     */
    public function getAllUzivIdClanku($IDclankuAutora, $clankyAutoru): ?array
    {
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

        if($autoriVClanku != null){
            return $autoriVClanku;
        }else{
            return null;
        }
    }

    /**
     * Metoda vrati jmena uzivatelu kteri clanky vytvareli
     * @param $IDAutoruClanku
     * @param $uzivatele
     * @return ?array
     */
    public function getAllUzivClanku($IDAutoruClanku, $uzivatele): ?array {
        if($IDAutoruClanku != null){
            foreach ($IDAutoruClanku as $autori) {
                // Projděte každý článek a escapujte hodnoty
                foreach ($autori as $hodnota) {
                    if($hodnota != null){
                        $hodnota = htmlspecialchars($hodnota);
                    }
                }
            }
        }

        foreach ($uzivatele as $u) {
            // Projděte každý článek a escapujte hodnoty
            foreach ($u as $hodnota) {
                if($hodnota != null){
                    $hodnota = htmlspecialchars($hodnota);
                }
            }
        }

        $jmenaUziv = [];
        $poleAutoruClanku = [];
        if($IDAutoruClanku != null){
            //jdeme pres vsechny clanky
            foreach ($IDAutoruClanku as $idAC){
                ///jdeme pres vsechny id uzivatelu konkretniho clanku
                foreach ($idAC as $idU){

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
     * @return ?array        Data nalezeneho prava.
     */
    public function getRightById(int $id): ?array {
        $id = htmlspecialchars($id);
        $poleHodnot[0] = $id;
        $poleKlicu[0] = ':uIdPravo';

        // ziskam pravo dle ID
        $rights = $this->selectFromTable(TABLE_PRAVO, "id_pravo=:uIdPravo", "", $poleKlicu, $poleHodnot);

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
     * @return ?array        Data nalezeneho uzivatele.
     */
    public function getUserById(int $id): ?array {
        $id = htmlspecialchars($id);
        $poleKlicu[0] = ':kIdUzivatel';
        $poleHodnot[0] = $id;

        // ziskam pravo dle ID
        $users = $this->selectFromTable(TABLE_UZIVATEL, "id_uzivatel=:kIdUzivatel", "", $poleKlicu, $poleHodnot);
        if(empty($users)){
            return null;
        } else {
            // vracim prvni nalezene pravo
            return $users[0];
        }
    }

    public function getClanekById(int $id){
        $id = htmlspecialchars($id);
        $poleHodnot[0]=$id;
        $poleKlicu[0] = ':kIdClanek';
        // ziskam pravo dle ID
        $clanek = $this->selectFromTable(TABLE_CLANEK, "idCLANEK=:kIdClanek", "", $poleKlicu, $poleHodnot);
        if(empty($clanek)){
            return null;
        } else {
            // vracim prvni nalezene pravo
            return $clanek[0];
        }
    }

    /**
     * Vytvoreni noveho uzivatele v databazi.
     *
     * @param string $login     Login.
     * @param string $jmeno     Jmeno.
     * @param string $email     E-mail.
     * @return bool             Vlozen v poradku?
     */
    public function addNewUser(string $login, string $heslo, string $jmeno, string $email, string $pohlavi, string $datum): bool {
        $login = htmlspecialchars($login);
        $heslo = htmlspecialchars($heslo);
        $jmeno = htmlspecialchars($jmeno);
        $email = htmlspecialchars($email);
        $pohlavi = htmlspecialchars($pohlavi);
        $datum = htmlspecialchars($datum);
        $idPravo = 4;
        $zablokovany = 0;

        $poleHodnot[0]=$idPravo;
        $poleKlicu[0]= ':pravo';
        $poleHodnot[1]=$jmeno;
        $poleKlicu[1]= ':jmeno';
        $poleHodnot[2]=$login;
        $poleKlicu[2]= ':login';
        $poleHodnot[3]=$heslo;
        $poleKlicu[3]= ':heslo';
        $poleHodnot[4]=$email;
        $poleKlicu[4]= ':email';
        $poleHodnot[5]=$pohlavi;
        $poleKlicu[5]= ':pohlavi';
        $poleHodnot[6]=$datum;
        $poleKlicu[6]= ':datum';
        $poleHodnot[7]=$zablokovany;
        $poleKlicu[7]= ':zablokovany';


        // hlavicka pro vlozeni do tabulky uzivatelu
        $insertStatement = "id_pravo, jmeno_prijmeni, username, password, email, pohlavi, datum_narozeni, Zablokovany";
        // hodnoty pro vlozeni do tabulky uzivatelu
        //$insertValues = "'$login', '$heslo', '$jmeno', '$email', $idPravo";
        $insertValues = ":pravo, :jmeno, :login, :heslo, :email, :pohlavi, :datum, :zablokovany";
        // provedu dotaz a vratim jeho vysledek
        return $this->insertIntoTable(TABLE_UZIVATEL, $insertStatement, $insertValues, $poleKlicu, $poleHodnot);
    }

    /**
     * Uprava konkretniho uzivatele v databazi.
     * @param int $idUzivatel   ID upravovaneho uzivatele.
     * @return bool             Bylo upraveno?
     */
    public function updateUserEmail(int $idUzivatel,  string $email): bool {
        $idUzivatel = htmlspecialchars($idUzivatel);
        $email = htmlspecialchars($email);
        // slozim cast s hodnotami
        $updateStatementWithValues = " email=:kEmail";
        $poleKlicu[0] = ':kEmail';
        $poleHodnot[0] = $email;
        // podminka
        $whereStatement = "id_uzivatel=:kIdUzivatel";
        $poleKlicu[1] = ':kIdUzivatel';
        $poleHodnot[1] = $idUzivatel;

        // provedu update
        return $this->updateInTable(TABLE_UZIVATEL, $updateStatementWithValues, $whereStatement, $poleKlicu, $poleHodnot);
    }

    /**
     * Uprava konkretniho uzivatele v databazi.
     *
     * @param int $idUzivatel   ID upravovaneho uzivatele.
     * @param string $login     Login.
     * @return bool             Bylo upraveno?
     */
    public function updateUsername(int $idUzivatel, string $login): bool{
        $idUzivatel = htmlspecialchars($idUzivatel);
        $login = htmlspecialchars($login);
        // slozim cast s hodnotami
        $updateStatementWithValues = " username=:kLogin";
        $poleKlicu[0] = ':kLogin';
        $poleHodnot[0] = $login;
        // podminka
        $whereStatement = "id_uzivatel=:kIdUzivatel";
        $poleKlicu[1] = ':kIdUzivatel';
        $poleHodnot[1] = $idUzivatel;
        // provedu update
        return $this->updateInTable(TABLE_UZIVATEL, $updateStatementWithValues, $whereStatement, $poleKlicu, $poleHodnot);
    }

    /**
     * Uprava konkretniho uzivatele v databazi.
     *
     * @param int $idUzivatel   ID upravovaneho uzivatele.
     * @return bool             Bylo upraveno?
     */
    public function updateUserJmeno(int $idUzivatel, string $jmeno): bool{
        $idUzivatel = htmlspecialchars($idUzivatel);
        $jmeno = htmlspecialchars($jmeno);
        // slozim cast s hodnotami
        $updateStatementWithValues = " jmeno_prijmeni=:kJmenol";
        $poleKlicu[0] = ':kJmenol';
        $poleHodnot[0] = $jmeno;
        // podminka
        $whereStatement = "id_uzivatel=:kIdUzivatel";
        $poleKlicu[1] = ':kIdUzivatel';
        $poleHodnot[1] = $idUzivatel;
        // provedu update
        return $this->updateInTable(TABLE_UZIVATEL, $updateStatementWithValues, $whereStatement, $poleKlicu, $poleHodnot);
    }

    /**
     * Uprava konkretniho uzivatele v databazi.
     *
     * @param int $idUzivatel   ID upravovaneho uzivatele.
     * @param string $heslo     Heslo.
     * @return bool             Bylo upraveno?
     */
    public function updateUserPass(int $idUzivatel, string $heslo): bool{
        $idUzivatel = htmlspecialchars($idUzivatel);
        $heslo = htmlspecialchars($heslo);
        ///zahashovani hesla
        $hash = password_hash($heslo, PASSWORD_BCRYPT);
        // slozim cast s hodnotami
        $updateStatementWithValues = "password=:kHash";
        $poleKlicu[0] = ':kHash';
        $poleHodnot[0] = $hash;
        // podminka
        $whereStatement = "id_uzivatel=:kIdUzivatel";
        $poleKlicu[1] = ':kIdUzivatel';
        $poleHodnot[1] = $idUzivatel;
        // provedu update
        return $this->updateInTable(TABLE_UZIVATEL, $updateStatementWithValues, $whereStatement, $poleKlicu, $poleHodnot);
    }
    /**
     * Uprava konkretniho uzivatele v databazi.
     *
     * @param int $idUzivatel   ID upravovaneho uzivatele.
     * @param int $idPravo      ID prava.
     * @return bool             Bylo upraveno?
     */
    public function updateUserRight(int $idUzivatel, int $idPravo): bool {
        $idUzivatel = htmlspecialchars($idUzivatel);
        $idPravo = htmlspecialchars($idPravo);
        $updateStatementWithValues = "id_pravo=:kIdPravo";
        $poleKlicu[0] = ':kIdPravo';
        $poleHodnot[0] = $idPravo;
        // podminka
        $whereStatement = "id_uzivatel=:kIdUzivatel";
        $poleKlicu[1] = ':kIdUzivatel';
        $poleHodnot[1] = $idUzivatel;
        // provedu update
        return $this->updateInTable(TABLE_UZIVATEL, $updateStatementWithValues, $whereStatement, $poleKlicu, $poleHodnot);
    }

    /**
     * Uprava konkretniho uzivatele v databazi.
     * @param string $pohlavi   pohlavi
     * @param int $idUzivatel   ID upravovaneho uzivatele.
     * @return bool             Bylo upraveno?
     */
    public function updateUserGender(int $idUzivatel, string $pohlavi): bool{
        $idUzivatel = htmlspecialchars($idUzivatel);
        $pohlavi = htmlspecialchars($pohlavi);
        $updateStatementWithValues = "pohlavi=:kPohlavi";
        $poleKlicu[0] = ':kPohlavi';
        $poleHodnot[0] = $pohlavi;
        // podminka
        $whereStatement = "id_uzivatel=:kIdUzivatel";
        $poleKlicu[1] = ':kIdUzivatel';
        $poleHodnot[1] = $idUzivatel;
        // provedu update
        return $this->updateInTable(TABLE_UZIVATEL, $updateStatementWithValues, $whereStatement, $poleKlicu, $poleHodnot);
    }

    /**
     * Uprava konkretniho uzivatele v databazi.
     * @param string $date      datum narozeni
     * @param int $idUzivatel   ID upravovaneho uzivatele.
     * @return bool             Bylo upraveno?
     */
    public function updateUserDate(int $idUzivatel, string $date): bool {
        $idUzivatel = htmlspecialchars($idUzivatel);
        $date = htmlspecialchars($date);
        $updateStatementWithValues = "datum_narozeni=:kDatum";
        $poleKlicu[0] = ':kDatum';
        $poleHodnot[0] = $date;
        // podminka
        $whereStatement = "id_uzivatel=:kIdUzivatel";
        $poleKlicu[1] = ':kIdUzivatel';
        $poleHodnot[1] = $idUzivatel;
        // provedu update
        return $this->updateInTable(TABLE_UZIVATEL, $updateStatementWithValues, $whereStatement, $poleKlicu, $poleHodnot);
    }
    /**
     * Uprava konkretniho uzivatele v databazi.
     * @param int $povol     int signalizujici povoleni
     * @param int $idUzivatel   ID upravovaneho uzivatele.
     * @return bool             Bylo upraveno?
     */
    public function updateZablokovaniUzivatele(int $idUzivatel, int $povol): bool {
        $idUzivatel = htmlspecialchars($idUzivatel);
        $povol = htmlspecialchars($povol);
        $updateStatementWithValues = "Zablokovany=:kZablokovany";
        $poleKlicu[0] = ':kZablokovany';
        $poleHodnot[0] = $povol;
        // podminka
        $whereStatement = "id_uzivatel=:kIdUzivatel";
        $poleKlicu[1] = ':kIdUzivatel';
        $poleHodnot[1] = $idUzivatel;
        // provedu update
        return $this->updateInTable(TABLE_UZIVATEL, $updateStatementWithValues, $whereStatement, $poleKlicu, $poleHodnot);
    }
    public function updateClanekNazev(string $nazev,int $idClanku): bool {
        $nazev = htmlspecialchars($nazev);
        $idClanku = htmlspecialchars($idClanku);
        $updateStatementWithValues = "nazev=:kNazev";
        $poleKlicu[0] = ':kNazev';
        $poleHodnot[0] = $nazev;
        $whereStatement = "idCLANEK=:kIdClanku";
        $poleKlicu[1] = ':kIdClanku';
        $poleHodnot[1] = $idClanku;
        return $this->updateInTable(TABLE_CLANEK, $updateStatementWithValues, $whereStatement, $poleKlicu, $poleHodnot);
    }

    public function updateClanekAbstrakt(string $abstrakt,string $idClanku): bool {
        $abstrakt = htmlspecialchars($abstrakt);
        $idClanku = htmlspecialchars($idClanku);
        $updateStatementWithValues = "abstrakt=:kAbstrakt";
        $poleKlicu[0] = ':kAbstrakt';
        $poleHodnot[0] = $abstrakt;
        $whereStatement = "idCLANEK=:kIdClanku";
        $poleKlicu[1] = ':kIdClanku';
        $poleHodnot[1] = $idClanku;
        return $this->updateInTable(TABLE_CLANEK, $updateStatementWithValues, $whereStatement, $poleKlicu, $poleHodnot);
    }

    public function updateClanekSoubor(string $cesta,int $idClanku): bool {
        $cesta = htmlspecialchars($cesta);
        $idClanku = htmlspecialchars($idClanku);
        $updateStatementWithValues = "cesta=:kCesta";
        $poleKlicu[0] = ':kCesta';
        $poleHodnot[0] = $cesta;
        $whereStatement = "idCLANEK=:kIdClanku";
        $poleKlicu[1] = ':kIdClanku';
        $poleHodnot[1] = $idClanku;

        return $this->updateInTable(TABLE_CLANEK, $updateStatementWithValues, $whereStatement, $poleKlicu, $poleHodnot);
    }
   public function updateClanekSchvalen($idClanku, $stav): bool {
       $idClanku = htmlspecialchars($idClanku);
       $stav = htmlspecialchars($stav);
       $updateStatementWithValues = "schvalen=:kStav";
       $poleKlicu[0] = ':kStav';
       $poleHodnot[0] = $stav;
       $whereStatement = "idCLANEK=:kIdClanku";
       $poleKlicu[1] = ':kIdClanku';
       $poleHodnot[1] = $idClanku;
       return $this->updateInTable(TABLE_CLANEK, $updateStatementWithValues, $whereStatement, $poleKlicu, $poleHodnot);
   }

   public function updateClanekRecenzent($clanek, $recenzentId, $poradiRecenzenta, $komentar, $hodnoceni): bool {
       // Projděte každý článek a escapujte hodnoty
       foreach ($clanek as $hodnota) {
           if($hodnota != null){
               $hodnota = htmlspecialchars($hodnota);
           }
       }

       $recenzentId = htmlspecialchars($recenzentId);
       $poradiRecenzenta = htmlspecialchars($poradiRecenzenta);
       $komentar = htmlspecialchars($komentar);
       $hodnoceni = htmlspecialchars($hodnoceni);

       $this->updateHodnoceni($clanek,$clanek[$poradiRecenzenta],$hodnoceni);
       $this->updateKomentare($clanek,$clanek[$poradiRecenzenta],$komentar);

       $updateStatementWithValues = "$poradiRecenzenta=:kRid";
       $poleKlicu[0] = ':kRid';
       $poleHodnot[0] = $recenzentId;

       $idClanku = $clanek['idCLANEK'];
       $whereStatement = "idCLANEK=:kIdClanku";
       $poleKlicu[1] = ':kIdClanku';
       $poleHodnot[1] = $idClanku;

       return $this->updateInTable(TABLE_CLANEK, $updateStatementWithValues, $whereStatement, $poleKlicu, $poleHodnot);
   }

    public function updateHodnoceni($clanek, $idUziv, $hodnoceni ): ?bool {
        foreach ($clanek as $c){
            if($c != null){
                $c = htmlspecialchars($c);
            }
        }

        $idUziv = htmlspecialchars($idUziv);
        $hodnoceni = htmlspecialchars($hodnoceni);
        $idClanku = $clanek['idCLANEK'];
        $whereStatement = "idCLANEK=:kIdClanku";
        $poleKlicu[1] = ':kIdClanku';
        $poleHodnot[1] = $idClanku;
        if($clanek['recenzent_1'] == $idUziv){
            $updateStatementWithValues = "hodnoceni_1=:kH";
            $poleKlicu[0] = ':kH';
            $poleHodnot[0] = $hodnoceni;
            return $this->updateInTable(TABLE_CLANEK, $updateStatementWithValues, $whereStatement, $poleKlicu, $poleHodnot);
        }elseif($clanek['recenzent_2'] == $idUziv){
            $updateStatementWithValues = "hodnoceni_2=:kH";
            $poleKlicu[0] = ':kH';
            $poleHodnot[0] = $hodnoceni;
            return $this->updateInTable(TABLE_CLANEK, $updateStatementWithValues, $whereStatement, $poleKlicu, $poleHodnot);
        }else if($clanek['recenzent_3'] == $idUziv){
            $updateStatementWithValues = "hodnoceni_3=:kH";
            $poleKlicu[0] = ':kH';
            $poleHodnot[0] = $hodnoceni;
            return $this->updateInTable(TABLE_CLANEK, $updateStatementWithValues, $whereStatement, $poleKlicu, $poleHodnot);
        }else{
            return null;
        }
    }

    public function updateKomentare($clanek, $idUziv, $hodnoceni ): bool {
        foreach ($clanek as $cA){
            if($cA != null){
                $cA = htmlspecialchars($cA);
            }
        }
        $idUziv = htmlspecialchars($idUziv);
        $hodnoceni = htmlspecialchars($hodnoceni);
        $idClanku = $clanek['idCLANEK'];
        $whereStatement = "idCLANEK=:kIdClanku";
        $poleKlicu[1] = ':kIdClanku';
        $poleHodnot[1] = $idClanku;
        if($clanek['recenzent_1'] == $idUziv){
            $updateStatementWithValues = "komentar_1=:kHo";
            $poleKlicu[0] = ':kHo';
            $poleHodnot[0] = $hodnoceni;
            //('$hodnoceni')
        }elseif($clanek['recenzent_2'] == $idUziv){
            $updateStatementWithValues = "komentar_2=:kHo";
            $poleKlicu[0] = ':kHo';
            $poleHodnot[0] = $hodnoceni;
            //('$hodnoceni')
        }else{
            $updateStatementWithValues = "komentar_3=:kHo";
            $poleKlicu[0] = ':kHo';
            $poleHodnot[0] = $hodnoceni;
            //('$hodnoceni')
        }
        return $this->updateInTable(TABLE_CLANEK, $updateStatementWithValues, $whereStatement, $poleKlicu, $poleHodnot);
    }

    ///////////////////  KONEC: Konkretni funkce  ////////////////////////////////////////////

    ///////////////////  Sprava prihlaseni uzivatele  ////////////////////////////////////////

    /**
     * Overi, zda muze byt uzivatel prihlasen a pripadne ho prihlasi.
     *
     * @param string $login     Login uzivatele.
     * @param string $heslo     Heslo uzivatele.
     * @return bool             Byl prihlasen?
     */
    public function userLogin(string $login, string $heslo):bool {
        $login = htmlspecialchars($login);
        $heslo = htmlspecialchars($heslo);

        $poleHodnot[0] = $login;
        $poleKlicu[0] = ':kUzivatelLogin';
        // ziskam uzivatele z DB - primo overuju login i heslo
        $where = "username=:kUzivatelLogin";
        $user = $this->selectFromTable(TABLE_UZIVATEL, $where,"", $poleKlicu, $poleHodnot);

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
    public function userLogout(): void {
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
    public function getLoggedUserData(): mixed {
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
                $poleHodnot[0] = intval($userId);
                $poleKlicu[0] = ':kIdUzivatel';
                // nactu data uzivatele z databaze
                $userData = $this->selectFromTable(TABLE_UZIVATEL, "id_uzivatel=:kIdUzivatel", "", $poleKlicu, $poleHodnot);
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
    public function jeUsernameVolne(string $usernameR, $users):bool{
        $usernameR = htmlspecialchars($usernameR);

        foreach ($users as $u) {
            // Projděte každý článek a escapujte hodnoty
            foreach ($u as $hodnota) {
                if($hodnota != null){
                    $hodnota = htmlspecialchars($hodnota);
                }
            }
        }

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
        $clanek = htmlspecialchars($clanek);
        $cestaKsouboru = htmlspecialchars($cestaKsouboru);
        $abstrakt = htmlspecialchars($abstrakt);
        $autori = htmlspecialchars($autori);

        $poleHodnot[0]= 0;
        $poleKlicu[0]= ':s';
        $poleHodnot[1]= 0;
        $poleKlicu[1]= ':r1';
        $poleHodnot[2]= 0;
        $poleKlicu[2]= ':r2';
        $poleHodnot[3]= 0;
        $poleKlicu[3]= ':r3';
        $poleHodnot[4]= 0;
        $poleKlicu[4]= ':h1';
        $poleHodnot[5]= 0;
        $poleKlicu[5]= ':h2';
        $poleHodnot[6]= 0;
        $poleKlicu[6]= ':h3';
        $poleHodnot[7]= $clanek;
        $poleKlicu[7]= ':n';
        $poleHodnot[8]= $abstrakt;
        $poleKlicu[8]= ':a';
        $poleHodnot[9]= $cestaKsouboru;
        $poleKlicu[9]= ':c';
        $poleHodnot[10]= $autori;
        $poleKlicu[10]= ':au';

        //vlozeni noveho clanku do tabulky
        $insertStatement = "schvalen, recenzent_1, recenzent_2, recenzent_3, hodnoceni_1, hodnoceni_2, hodnoceni_3, nazev, abstrakt, cesta, autori";
        // hodnoty pro vlozeni do tabulky uzivatelu
        $insertValues = ":s, :r1, :r2, :r3, :h1, :h2, :h3, :n, :a, :c, :au";
        // provedu dotaz a vratim jeho vysledek
        return $this->insertIntoTable(TABLE_CLANEK, $insertStatement, $insertValues, $poleKlicu, $poleHodnot);
    }
    public function getPosledniClanek(){

        $clanek = $this->selectFromTable(TABLE_CLANEK, "idCLANEK = (SELECT MAX(idCLANEK) FROM CLANEK)", "", [], []);
        // vracim prvni nalezeny clanek
        return $clanek[0];

    }
    public function addNewClankyAutora($idClanku, $id_prihlasenehoU): bool {
        $idClanku = htmlspecialchars($idClanku);
        $id_prihlasenehoU = htmlspecialchars($id_prihlasenehoU);
        $insertStatement = "id_uzivatel, idCLANEK";
        $insertValues = ":id_uzivatel, :idCLANEK";

        $poleHodnot[0]= $id_prihlasenehoU;
        $poleKlicu[0]= ':id_uzivatel';
        $poleHodnot[1]= $idClanku;
        $poleKlicu[1]= ':idCLANEK';

        return $this->insertIntoTable(TABLE_CLANKY_AUTORA, $insertStatement, $insertValues,  $poleKlicu, $poleHodnot);
    }

    public function addNewDotaz($email, $jmeno, $dotaz): bool {
        $email = htmlspecialchars($email);
        $jmeno = htmlspecialchars($jmeno);
        $dotaz = htmlspecialchars($dotaz);
        $poleHodnot[0]= $email;
        $poleKlicu[0]= ':e';
        $poleHodnot[1]= $jmeno;
        $poleKlicu[1]= ':j';
        $poleHodnot[2] = $dotaz;
        $poleKlicu[2] = ':d';

        $insertStatement = "e_mail, jmeno, dotaz";
        $insertValues = ":e, :j, :d";
        return $this->insertIntoTable(TABLE_DOTAZ, $insertStatement, $insertValues, $poleKlicu, $poleHodnot);
    }

    /**
     * Metoda vrati pocet autoru
     * @param $uzivatele
     * @return int
     */
    public function getPocetAutoru($uzivatele): int
    {
        foreach ($uzivatele as $u) {
            // Projděte každý článek a escapujte hodnoty
            foreach ($u as $hodnota) {
                if($hodnota != null){
                    $hodnota = htmlspecialchars($hodnota);
                }
            }
        }

        $pocet = 0;
        foreach ($uzivatele as $u){
            if($u['id_pravo']==4 && $u['Zablokovany'] == 0){
                $pocet++;
            }
        }
        return $pocet;
    }

    public function odstranClanek($idClanku): void {
        $idClanku = htmlspecialchars($idClanku);
        $poleHodnot[0] = $idClanku;
        $poleKlicu[0] = ':kIdClanku';
        $where = 'idCLANEK=:kIdClanku';
        $this->odstranClanekAutor($idClanku);
        $this->deleteFromTable(TABLE_CLANEK, $where, $poleKlicu, $poleHodnot);
    }

    public function odstranClanekAutor($idClanku): void {
        $idClanku = htmlspecialchars($idClanku);
        $poleHodnot[0] = $idClanku;
        $poleKlicu[0] = ':kIdClanku';
        $where = 'idCLANEK=:kIdClanku';
        $this->deleteFromTable(TABLE_CLANKY_AUTORA, $where, $poleKlicu, $poleHodnot);
    }

    public function odstranDotaz($dotaz): void {
        $dotaz =htmlspecialchars($dotaz);
        $poleHodnot[0] = $dotaz;
        $poleKlicu[0] = ':kDotaz';
        $where = 'id_dotaz=:kDotaz';
        $this->deleteFromTable(TABLE_DOTAZ, $where, $poleKlicu, $poleHodnot);
    }

    /**
     * vrati vsechny recenzenty
     * @return array
     */
    public function vyberRecenzenty(): array {
        $users = $this->getAllUsers();
        $uzivatele = [];
        foreach ($users as $u){
            if($u['id_pravo'] == 3){
                $uzivatele[] = $u;
            }
        }
        return $uzivatele;
    }
    ///////////////////  KONEC: Sprava prihlaseni uzivatele  ////////////////////////////////////////

}





