<?php

define("BASE_DIR", __DIR__);

require_once(BASE_DIR .  "/src/Autoloader.php");
$config = require_once(BASE_DIR . "/config.php");

$db = new DbConnection();
print_r($db->fetchRow("users"));