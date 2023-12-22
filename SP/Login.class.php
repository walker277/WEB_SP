<?php
/**
 *  Trida pro spravu prihlaseni uzivatele.
 *  @author Filip Valtr
 */
class Login {
    /** @var MySession $ses  Objekt pro praci se session. */
    private $ses;
    /** @var string SESSION_KEY  Klic pro ulozeni uzivatele do session */
    private const SESSION_KEY = "usr";

    /** @var string KEY_NAME  Klic pro ulozeni jmena do pole.  */
    private const KEY_NAME = "jm";
    /** @var string KEY_DATE  Klic pro ulozeni datumu do pole. */
    private const KEY_DATE = "dt";

    /**
     *  Pri vytvoreni objektu zahajim session.
     */
    public function __construct(){
        require_once("MySession.class.php");
        // vytvorim instanci vlastni tridy pro praci se session (objekt)
        $this->ses = new MySession;
    }

    /**
     *  Otestuje, zda je uzivatel prihlasen.
     *  @return bool
     */
    public function isUserLogged():bool {
        return $this->ses->isSessionSet(self::SESSION_KEY);
    }

    /**
     *  Nastavi do session jmeno uzivatele a datum prihlaseni.
     *  @param string $userName Jmeno uzivatele.
     */
    public function login(string $userName){
        $data = [ self::KEY_NAME => $userName,
            self::KEY_DATE => date("d. m. Y, G:i:s")];
        $this->ses->setSession(self::SESSION_KEY, $data);
    }

    /**
     *  Odhlasi uzivatele.
     */
    public function logout(){
        $this->ses->removeSession(self::SESSION_KEY);
    }

    /**
     *  Vrati informace o uzivateli.
     *  @return string|null  Informace o uzivateli.
     */
    public function getUserInfo() {
        if(!$this->isUserLogged()) {
            return null;
        }
        $d = $this->ses->readSession(self::SESSION_KEY);
        return "Jméno: " . $d[self::KEY_NAME] . "<br>"
            . "Datum: " . $d[self::KEY_DATE] . "<br>";
    }

}
?>