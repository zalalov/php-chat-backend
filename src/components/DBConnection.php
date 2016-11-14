<?php

/**
 * Database connection singleton
 */
class DBConnection {
    /**
     * Exclude many creation of singleton by private contructor
     */
    private function __construct() {
        try {

        } catch (Exception $e) {
            throw new Exception("Cannot connect to database", 500);
        }
    }

    public function connect() {
        return true;
    }

    public function query($sql) {
        return [];
    }
}