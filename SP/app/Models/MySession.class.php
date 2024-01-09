<?php

/**
 *  Trida pro praci se Session.
 *  @author Filip Valtr
 */
class MySession {

    /**
     *  Pri vytvoreni objektu je zahajena session.
     */
    public function __construct(){
        session_start(); // zahajim
    }

}