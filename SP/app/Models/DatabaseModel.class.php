<?php
/**
 * Trida spravujici databazi.
 */
#[AllowDynamicProperties] class DatabaseModel{
    /** @var PDO $pdo  PDO objekt pro praci s databazi. */
    private PDO $pdo;

    /** @var string $userSessionKey  Klic pro data uzivatele, ktera jsou ulozena v session. */
    private string $userSessionKey = "current_user_id";

    /**
     * MyDatabase constructor.
     * Inicializace pripojeni k databazi a pokud ma byt spravovano prihlaseni uzivatele,
     * tak i vlastni objekt pro spravu session.
     */
    public function __construct(){
        require_once("settings.inc.php");
        // inicialilzuju pripojeni k databazi - informace beru ze settings
        $this->pdo = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME, DB_USER, DB_PASS);
        $this->pdo->exec("set names utf8");
        // nastavení PDO error módu na výjimku, tj. každá chyba při práci s PDO bude výjimkou
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // inicializuju objekt pro praci se session - pouzito pro spravu prihlaseni uzivatele
        require_once("MySession.class.php");
        $this->mySession = new MySession();
    }
    ///////////////////  Obecne funkce  (updatovani, vybirani, vkladani, mazani z databaze////////////////////////////////////////////
    /**
     * Jednoduche cteni z prislusne DB tabulky.
     *
     * @param string $tableName         Nazev tabulky.
     * @param string $whereStatement    Pripadne omezeni na ziskani radek tabulky. Default "".
     * @param string $orderByStatement  Pripadne razeni ziskanych radek tabulky. Default "".
     * @param array $poleKlicu          Pole obsahujici klice ktere jsou nasledne bindovany k hodnotam pro predpripravene dotazy.
     * @param array $poleHodnot         Pole obsahujici hodnoty ktere jsou nasledne bindovany k hodnotam pro predpripravene dotazy.
     * @return array                    Vraci pole ziskanych radek tabulky.
     */
    public function selectFromTable(string $tableName, string $whereStatement, string $orderByStatement, array $poleKlicu, array $poleHodnot):array {
        //escapovani znaku
        $tableName = htmlspecialchars($tableName);
        $whereStatement = htmlspecialchars($whereStatement);
        $orderByStatement = htmlspecialchars($orderByStatement);
        $poleHodnot = array_map('htmlspecialchars', $poleHodnot);
        $poleKlicu = array_map('htmlspecialchars', $poleKlicu);

        // slozim dotaz
        $q = "SELECT * FROM ".$tableName
            .(($whereStatement == "") ? "" : " WHERE $whereStatement")
            .(($orderByStatement == "") ? "" : " ORDER BY $orderByStatement");
        //priprava dotazu
        $vystup = $this->pdo->prepare($q);

        //bindovani hodnot a klicu
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
        //vykonnani dotazu
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
     * @param string $insertValues      Text s klicema hodnot pro prislusne sloupce.
     * @param array $poleKlicu Pole obsahujici klice ktere jsou nasledne bindovany k hodnotam pro predpripravene dotazy.
     * @param array $poleHodnot Pole obsahujici hodnoty ktere jsou nasledne bindovany k hodnotam pro predpripravene dotazy.
     * @return bool                     Vlozeno v poradku?
     */
    public function insertIntoTable(string $tableName, string $insertStatement, string $insertValues, array $poleKlicu, array $poleHodnot):bool {
        //escapovani hodnot
        $poleHodnot = array_map('htmlspecialchars',$poleHodnot);
        // slozim dotaz
        $q = "INSERT INTO $tableName($insertStatement) VALUES ($insertValues)";
        //pripravime dotaz
        $vystup = $this->pdo->prepare($q);
        //nabindujeme klice s hodnotami
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
        //provedem dotaz
        if($vystup->execute()){
            return true;
        }else{
            return false;
        }
    }
    /**
     * Jednoducha uprava radku databazove tabulky.
     * @param string $tableName                     Nazev tabulky.
     * @param string $updateStatementWithValues     Cela cast updatu s hodnotami.
     * @param string $whereStatement                Cela cast pro WHERE.
     * @param array $poleKlicu Pole obsahujici klice ktere jsou nasledne bindovany k hodnotam pro predpripravene dotazy.
     * @param array $poleHodnot Pole obsahujici hodnoty ktere jsou nasledne bindovany k hodnotam pro predpripravene dotazy.
     * @return bool                                 Upraveno v poradku?
     */
    public function updateInTable(string $tableName, string $updateStatementWithValues, string $whereStatement, array $poleKlicu, array $poleHodnot):bool {
        //escapovani hodnot
        $poleHodnot = array_map('htmlspecialchars', $poleHodnot);
        $poleKlicu = array_map('htmlspecialchars', $poleKlicu);
        // slozim dotaz
        $q = "UPDATE $tableName SET $updateStatementWithValues WHERE $whereStatement";
        //predpripravime dotaz
        $vystup = $this->pdo->prepare($q);
        //nabindujeme klice s hodnotami
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
        //provedeme dotaz
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
     * @param string $tableName         Nazev tabulky.
     * @param string $whereStatement    Podminka mazani.
     * @param array $poleKlicu Pole obsahujici klice ktere jsou nasledne bindovany k hodnotam pro predpripravene dotazy.
     * @param array $poleHodnot Pole obsahujici hodnoty ktere jsou nasledne bindovany k hodnotam pro predpripravene dotazy.
     * @return bool
     */
    public function deleteFromTable(string $tableName, string $whereStatement, array $poleKlicu, array $poleHodnot):bool {
        //escapovani znaku
        $poleHodnot = array_map('htmlspecialchars', $poleHodnot);
        $poleKlicu = array_map('htmlspecialchars', $poleKlicu);
        // slozim dotaz
        $q = "DELETE FROM $tableName WHERE $whereStatement";
        //predpripravie dotaz
        $vystup = $this->pdo->prepare($q);
        //nabindujeme hodnoty a klice
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
        //provedeme dotaz
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
     * @return array    Pole se vsemi uzivateli.
     */
    public function getAllUsers(): array
    {
        // ziskam vsechny uzivatele z DB razene dle ID a vratim je
        return $this->selectFromTable(TABLE_UZIVATEL, "", "id_uzivatel", [], []);
    }

    /**
     * Ziskani zaznamu vsech prav uzivatelu.
     * @return array    Pole se vsemi pravy.
     */
    public function getAllRights(): array {
        // ziskam vsechna prava z DB razena dle ID a vratim je
        return $this->selectFromTable(TABLE_PRAVO, "", "vaha ASC, nazev ASC", [], []);
    }

    /**
     * Ziskani zaznamu vsech clanku aplikace.
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

    /**
     * Ziskame vsechny dotazy.
     * @return array|null
     */
    public function getAllDotazy(): ?array {
        // ziskam vsechny uzivatele z DB razene dle ID a vratim je
        $dotazy = $this->selectFromTable(TABLE_DOTAZ, "", "id_dotaz",[],[]);
        if($dotazy != null){
            return $dotazy;
        }else{
            return null;
        }
    }

    /**
     * Ziskani vsech clanku vsech autoru.
     * @return array|null
     */
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
     * Ziskani zaznamu vsech id clanku konkretniho autora.
     * @param  string $uzivatelID Id uzivatele.
     * @param  ?array $clankyAutoru pole obsahujici clanky vsech autoru.
     * @return ?array    Pole se vsemi uzivateli.
     */
    public function getAllAutoroviClankyID(string $uzivatelID, ?array $clankyAutoru): ?array {
        // ziskam vsechny uzivatele z DB razene dle ID a vratim je
        if($uzivatelID != null){
            $uzivatelID = htmlspecialchars($uzivatelID);
        }
        if($clankyAutoru != null){
            $clankyAutoru = $this->escapujPolePoli($clankyAutoru);
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
     * Metoda escapuje polePoli.
     * @param array $PolePoli
     * @return array
     */
    private function escapujPolePoli(array $PolePoli): array{
        $escapovanePolePoli = [];
        foreach ($PolePoli as $pole) {
            $novePole = [];
            foreach ($pole as $klic => $hodnota) {
                // Escapovat pouze hodnoty, ktere nejsou null
                if ($hodnota !== null) {
                    // Pokud je hodnota pole, escapovat vsechny jeho hodnoty
                    if (is_array($hodnota)) {
                        $novePole[$klic] = array_map('htmlspecialchars', $hodnota);
                    } else {
                        $novePole[$klic] = htmlspecialchars($hodnota);
                    }
                } else {
                    $novePole[$klic] = $hodnota; // Ponechat null hodnoty nezmenene
                }
            }
            $escapovanePolePoli[] = $novePole;
        }
        return $escapovanePolePoli;
    }

    /**
     * Metoda escapuje pole
     * @param array $Pole
     * @return array
     */
    private function escapujPole(array $Pole): array {
        $escapovaneNovePole = [];
        foreach ($Pole as $klic => $hodnota){
            // Escapovat pouze hodnoty, ktere nejsou null
            if ($hodnota !== null) {
                // Pokud je hodnota pole, escapovat vsechny jeho hodnoty
                if (is_array($hodnota)) {
                    $escapovaneNovePole[$klic] = array_map('htmlspecialchars', $hodnota);
                } else {
                    $escapovaneNovePole[$klic] = htmlspecialchars($hodnota);
                }
            } else {
                $escapovaneNovePole[$klic] = $hodnota; // Ponechat null hodnoty nezmenene
            }
        }
        return $escapovaneNovePole ;
    }

    /**
     * Ziskani zaznamu vsech clanku uzivatele.
     * @param  ?array $IDclankuUzivatele Pole s id clanku autora.
     * @param  ?array $clanky Pole s clankama.
     * @return ?array    Pole se vsemi uzivateli.
     */
    public function getAllAutoroviClanky(?array $IDclankuUzivatele, ?array $clanky): ?array {
        if($clanky != null){
            $clanky = $this->escapujPolePoli($clanky);
        }
        if($IDclankuUzivatele != null){
            $IDclankuUzivatele = $this->escapujPole($IDclankuUzivatele);
        }else{
            return null;
        }
        $autoroviClanky = [];
        //jdeme postupne pres vsechny ID clanku ktere uzivatel vytvoril
        if($clanky != null && $IDclankuUzivatele != null){
            foreach ($IDclankuUzivatele as $idClanku){
                // a jdeme pres clanky
                foreach ($clanky as $c) {
                        if ($idClanku == $c['idCLANEK']) {
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
     * Metoda vrati id uzivatelu kteří clanky vytvareli.
     * @param  ?array $IDclankuAutora   Pole id clanku autoru.
     * @param ?array $clankyAutoru Pole vsech clanku autoru.
     * @return ?array
     */
    public function getAllUzivIdClanku(?array $IDclankuAutora, ?array $clankyAutoru): ?array
    {
        if($IDclankuAutora != null){
            $IDclankuAutora = $this->escapujPole($IDclankuAutora);
        }
        if($clankyAutoru  != null){
            $clankyAutoru = $this->escapujPolePoli($clankyAutoru);
        }
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
     * Metoda vrati jmena uzivatelu kteri clanky vytvareli.
     * @param ?array $IDAutoruClanku Pole id autoru vsech clanku.
     * @param ?array $uzivatele Pole uzivatelu.
     * @return ?array
     */
    public function getAllUzivClanku(?array $IDAutoruClanku, ?array $uzivatele): ?array {
        if($IDAutoruClanku!= null){
            $IDAutoruClanku = $this->escapujPolePoli($IDAutoruClanku);
        }
        if($uzivatele != null){
            $uzivatele = $this->escapujPolePoli($uzivatele);
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
     * @param int $id       ID prava.
     * @return ?array       Data nalezeneho prava.
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
     * Ziskani konkretniho uzivatele dle ID uzivatele.
     * @param int $id       ID uzivatele.
     * @return ?array       Data nalezeneho uzivatele.
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

    /**
     * Ziska clanek dle id clanku
     * @param int $id Id clanku
     * @return mixed|null
     */
    public function getClanekById(int $id): mixed{
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
     * @param string $login     Login.
     * @param string $heslo     Heslo.
     * @param string $jmeno     Jmeno.
     * @param string $email     E-mail.
     * @param string $pohlavi   Pohlavi.
     * @param string $datum     Datum.
     * @return bool             Vlozen v poradku?
     */
    public function addNewUser(string $login, string $heslo, string $jmeno, string $email, string $pohlavi, string $datum): bool {
        //escapovani znaku
        $login = htmlspecialchars($login);
        $heslo = htmlspecialchars($heslo);
        $jmeno = htmlspecialchars($jmeno);
        $email = htmlspecialchars($email);
        $pohlavi = htmlspecialchars($pohlavi);
        $datum = htmlspecialchars($datum);
        $idPravo = 4;
        $zablokovany = 0;
        //ulozeni klicu a hodnot
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
        $insertValues = ":pravo, :jmeno, :login, :heslo, :email, :pohlavi, :datum, :zablokovany";
        // provedu dotaz a vratim jeho vysledek
        return $this->insertIntoTable(TABLE_UZIVATEL, $insertStatement, $insertValues, $poleKlicu, $poleHodnot);
    }

    /**
     * Uprava konkretniho uzivatele v databazi.
     * @param int $idUzivatel   ID upravovaneho uzivatele.
     * @param string $email     Email.
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
     * @param int $idUzivatel   ID upravovaneho uzivatele.
     * @param string $login     Login.
     * @return bool             Bylo upraveno?
     */
    public function updateUsername(int $idUzivatel, string $login): bool{
        //escapovani
        $idUzivatel = htmlspecialchars($idUzivatel);
        $login = htmlspecialchars($login);
        // slozim cast s hodnotami
        $updateStatementWithValues = " username=:kLogin";
        //ulozeni klicu a hodnot
        $poleKlicu[0] = ':kLogin';
        $poleHodnot[0] = $login;
        // podminka
        $whereStatement = "id_uzivatel=:kIdUzivatel";
        //uloozeni klicu a hodnot
        $poleKlicu[1] = ':kIdUzivatel';
        $poleHodnot[1] = $idUzivatel;
        // provedu update
        return $this->updateInTable(TABLE_UZIVATEL, $updateStatementWithValues, $whereStatement, $poleKlicu, $poleHodnot);
    }
    /**
     * Uprava konkretniho uzivatele v databazi.
     * @param int $idUzivatel   ID upravovaneho uzivatele.
     * @param string $jmeno     Jmeno uzivatele
     * @return bool             Bylo upraveno?
     */
    public function updateUserJmeno(int $idUzivatel, string $jmeno): bool{
        //excapovani hodnot
        $idUzivatel = htmlspecialchars($idUzivatel);
        $jmeno = htmlspecialchars($jmeno);
        // slozim cast s hodnotami
        $updateStatementWithValues = " jmeno_prijmeni=:kJmenol";
        //ulozeni klicu a hodnot
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
     * @param int $idUzivatel   ID upravovaneho uzivatele.
     * @param string $heslo     Heslo.
     * @return bool             Bylo upraveno?
     */
    public function updateUserPass(int $idUzivatel, string $heslo): bool{
        //escapovani hodnot
        $idUzivatel = htmlspecialchars($idUzivatel);
        $heslo = htmlspecialchars($heslo);
        ///zahashovani hesla
        $hash = password_hash($heslo, PASSWORD_BCRYPT);
        // slozim cast s hodnotami
        $updateStatementWithValues = "password=:kHash";
        //ulozeni klicu a hodnot
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
     * @param int $idUzivatel   ID upravovaneho uzivatele.
     * @param int $idPravo      ID prava.
     * @return bool             Bylo upraveno?
     */
    public function updateUserRight(int $idUzivatel, int $idPravo): bool {
        //escapovani znaku
        $idUzivatel = htmlspecialchars($idUzivatel);
        $idPravo = htmlspecialchars($idPravo);
        $updateStatementWithValues = "id_pravo=:kIdPravo";
        //ulozeni klicu a hodnot
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
        //escapovani znaku
        $idUzivatel = htmlspecialchars($idUzivatel);
        $pohlavi = htmlspecialchars($pohlavi);
        $updateStatementWithValues = "pohlavi=:kPohlavi";
        //ulozeni klicu a hodnot
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
        //escapovani hodnot
        $idUzivatel = htmlspecialchars($idUzivatel);
        $date = htmlspecialchars($date);
        $updateStatementWithValues = "datum_narozeni=:kDatum";
        //ulozeni klicu a hodnot
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
     * @param int $povol     int signalizujici povoleni.
     * @param int $idUzivatel   ID upravovaneho uzivatele.
     * @return bool             Bylo upraveno?
     */
    public function updateZablokovaniUzivatele(int $idUzivatel, int $povol): bool {
        //escapovani znaku
        $idUzivatel = htmlspecialchars($idUzivatel);
        $povol = htmlspecialchars($povol);
        $updateStatementWithValues = "Zablokovany=:kZablokovany";
        //ulozeni klicu a hodnot
        $poleKlicu[0] = ':kZablokovany';
        $poleHodnot[0] = $povol;
        // podminka
        $whereStatement = "id_uzivatel=:kIdUzivatel";
        $poleKlicu[1] = ':kIdUzivatel';
        $poleHodnot[1] = $idUzivatel;
        // provedu update
        return $this->updateInTable(TABLE_UZIVATEL, $updateStatementWithValues, $whereStatement, $poleKlicu, $poleHodnot);
    }

    /**
     * Updatuje nazev clanku.
     * @param string $nazev Nazev.
     * @param int $idClanku Id.
     * @return bool
     */
    public function updateClanekNazev(string $nazev,int $idClanku): bool {
        //escapovani znaku
        $nazev = htmlspecialchars($nazev);
        $idClanku = htmlspecialchars($idClanku);
        $updateStatementWithValues = "nazev=:kNazev";
        //ulozeni klicu a hodnot
        $poleKlicu[0] = ':kNazev';
        $poleHodnot[0] = $nazev;
        $whereStatement = "idCLANEK=:kIdClanku";
        $poleKlicu[1] = ':kIdClanku';
        $poleHodnot[1] = $idClanku;
        return $this->updateInTable(TABLE_CLANEK, $updateStatementWithValues, $whereStatement, $poleKlicu, $poleHodnot);
    }

    /**
     * Obnoveni abstraktu clanku
     * @param string $abstrakt Abstrakt
     * @param string $idClanku Id
     * @return bool
     */
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

    /**
     * Obnovi cestu k souboru clanku
     * @param string $cesta Cesta.
     * @param int $idClanku Id
     * @return bool
     */
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

    /**
     * Obnovi stav clanku
     * @param string $idClanku Id
     * @param string $stav Stav.
     * @return bool
     */
   public function updateClanekSchvalen(string $idClanku, string $stav): bool {
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

    /**
     * Obnovi recenzenta clanku
     * @param array $clanek   Clanek.
     * @param string $recenzentId Id recenzenta.
     * @param string $poradiRecenzenta
     * @param string $komentar Komentar recenzenta.
     * @param string $hodnoceni    Hodnoceni recenzenta
     * @return bool
     */
   public function updateClanekRecenzent(array $clanek, string $recenzentId, string $poradiRecenzenta, string $komentar, string $hodnoceni): bool {
       $clanek = $this->escapujPole($clanek);

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

    /**
     * Obnoveni hodnoceni.
     * @param $clanek
     * @param $idUziv
     * @param $hodnoceni
     * @return bool|null
     */
    public function updateHodnoceni($clanek, $idUziv, $hodnoceni ): ?bool {
        $clanek = $this->escapujPole($clanek);
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

    /**
     * Obnoveni Komentaru.
     * @param $clanek
     * @param $idUziv
     * @param $hodnoceni
     * @return bool
     */
    public function updateKomentare($clanek, $idUziv, $hodnoceni ): bool {
        $clanek = $this->escapujPole($clanek);
        $idUziv = htmlspecialchars($idUziv);
        $hodnoceni = htmlspecialchars($hodnoceni);
        $idClanku = $clanek['idCLANEK'];
        $whereStatement = "idCLANEK=:kIdClanku";
        $poleKlicu[1] = ':kIdClanku';
        $poleHodnot[1] = $idClanku;
        if($clanek['recenzent_1'] == $idUziv){
            $updateStatementWithValues = "komentar_1=:kHo";
            //('$hodnoceni')
        }elseif($clanek['recenzent_2'] == $idUziv){
            $updateStatementWithValues = "komentar_2=:kHo";
            //('$hodnoceni')
        }else{
            $updateStatementWithValues = "komentar_3=:kHo";
            //('$hodnoceni')
        }
        $poleKlicu[0] = ':kHo';
        $poleHodnot[0] = $hodnoceni;
        return $this->updateInTable(TABLE_CLANEK, $updateStatementWithValues, $whereStatement, $poleKlicu, $poleHodnot);
    }

    /**
     * Vrati true pokud je volne false pokud neni
     * @param $usernameR string obsahujici uzivatelske jmeno
     * @param ?array $users
     * @return boolean
     */
    public function jeUsernameVolne(string $usernameR, ?array $users):bool{
        $usernameR = htmlspecialchars($usernameR);
        if($users != null){
            $this->escapujPolePoli($users);
        }
        $volne = true;
        if($users != null) {
            foreach ($users as $u) {
                if ($u['username'] == $usernameR) {
                    $volne = false;
                    break;
                }
            }
        }
        return $volne;
    }

    /**
     * Prida novy clanek do databaze
     * @param string $clanek Clanek.
     * @param string $cestaKsouboru Cesta k souboru.
     * @param string $abstrakt abstrakt.
     * @param string $autori autori.
     * @return bool
     */
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

    /**
     * Vrati posledni clanek z clanku.
     * @return mixed
     */
    public function getPosledniClanek(): mixed {
        $clanek = $this->selectFromTable(TABLE_CLANEK, "idCLANEK = (SELECT MAX(idCLANEK) FROM CLANEK)", "", [], []);
        // vracim prvni nalezeny clanek
        return $clanek[0];
    }

    /**
     *
     * @param string $idClanku id clanku
     * @param string $id_prihlasenehoU id prihlaseneho
     * @return bool
     */
    public function addNewClankyAutora(string $idClanku, string $id_prihlasenehoU): bool {
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

    /**
     * Prida novy dotaz do databaze
     * @param string $email Email.
     * @param string $jmeno Jmeno.
     * @param string $dotaz Dotaz.
     * @return bool
     */
    public function addNewDotaz(string $email, string $jmeno, string $dotaz): bool {
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
     * @param array $uzivatele Pole uzivatelu
     * @return int
     */
    public function getPocetAutoru(array $uzivatele): int
    {
        if($uzivatele != null){
            $uzivatele = $this->escapujPolePoli($uzivatele);
        }
        $pocet = 0;
        if($uzivatele != null) {
            foreach ($uzivatele as $u) {
                $u['id_pravo'] = htmlspecialchars($u['id_pravo']);
                $u['Zablokovany'] = htmlspecialchars($u['Zablokovany']);
                if ($u['id_pravo'] == 4 && $u['Zablokovany'] == 0) {
                    $pocet++;
                }
            }
        }
        return $pocet;
    }

    /**
     * Odstrani Clanek z databaze
     * @param string $idClanku Id clanku.
     * @return void
     */
    public function odstranClanek(string $idClanku): void {
        $idClanku = htmlspecialchars($idClanku);
        $poleHodnot[0] = $idClanku;
        $poleKlicu[0] = ':kIdClanku';
        $where = 'idCLANEK=:kIdClanku';
        $this->odstranClanekAutor($idClanku);
        $this->deleteFromTable(TABLE_CLANEK, $where, $poleKlicu, $poleHodnot);
    }

    /**
     * Odstrani zaznam z tabulky kde se id predanemu clanku rovna tomu v tabulce
     * @param string $idClanku Id clanku.
     * @return void
     */
    public function odstranClanekAutor(string $idClanku): void {
        $idClanku = htmlspecialchars($idClanku);
        $poleHodnot[0] = $idClanku;
        $poleKlicu[0] = ':kIdClanku';
        $where = 'idCLANEK=:kIdClanku';
        $this->deleteFromTable(TABLE_CLANKY_AUTORA, $where, $poleKlicu, $poleHodnot);
    }

    /**
     * Odstrani dotaz
     * @param string $dotaz Id dotazu.
     * @return void
     */
    public function odstranDotaz(string $dotaz): void {
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

    ///////////////////  KONEC: Konkretni funkce  ////////////////////////////////////////////

    ///////////////////  Sprava prihlaseni uzivatele  ////////////////////////////////////////

    /**
     * Overi, zda muze byt uzivatel prihlasen a pripadne ho prihlasi.
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
     * @return bool     Je prihlasen?
     */
    public function isUserLogged():bool {
        return isset($_SESSION[$this->userSessionKey]);
    }

    /**
     * Pokud je uzivatel prihlasen, tak vrati jeho data,
     * ale pokud nebyla v session nalezena, tak vypise chybu.
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

    /**
     * Ziskame id prihlaseneho uzivatele
     * @return mixed|null
     */
    public function getLoggedUserID(): mixed {
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
    ///////////////////  KONEC: Sprava prihlaseni uzivatele  ////////////////////////////////////////

}





