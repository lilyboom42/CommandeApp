<?php

require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/ClientManager.php';
require_once __DIR__ . '/classes/CommandeManager.php';
require_once __DIR__ . '/App/CommandesApp.php';

$app = new CommandesApp();
$app->run();
