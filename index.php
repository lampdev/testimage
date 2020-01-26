<?php

error_reporting(-1);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

require_once(__DIR__ . DIRECTORY_SEPARATOR . 'app/app.php');

App::handle(
    empty($_GET['action'])
        ? App::ACTION_PREFIX . App::DEFAULT_ACTION
        : App::ACTION_PREFIX . $_GET['action']
);
