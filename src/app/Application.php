<?php

class Application {
    public static function start() {
        print $_SERVER["REQUEST_URI"];

        return true;
    }

    public function parseRoute($route) {}
}