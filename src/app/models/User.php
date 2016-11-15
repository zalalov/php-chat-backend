<?php

class User extends AbstractModel {
    public $id = 5;

    /**
     * Get user by token
     * @param $token
     * @return array
     */
    public static function getByToken($token) {
        $db = new DbConnection();
        $user = $db->fetchRow("users", sprintf("token = '%s'", $token));

        return $user;
    }
}