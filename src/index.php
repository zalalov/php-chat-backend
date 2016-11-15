<?php

define("BASE_DIR", __DIR__);

require_once(BASE_DIR .  "/app/Autoloader.php");
$config = require_once(BASE_DIR . "/config.php");

Application::start();