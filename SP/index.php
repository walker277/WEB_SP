<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// nactu vlastni nastaveni webu
require_once("settings.inc.php");

// nactu tridu spoustejici aplikaci
require_once("app/ApplicationStart.class.php");

// spustim aplikaci
$app = new ApplicationStart();
$app->appStart();

