<?php

/**
 * Class User
 */
class User {
    /**
     * Get user by token
     * @param $token
     * @return array
     */
    public static function getByToken($token) {
        $token = DbConnection::sanitize($token);

        $db = new DbConnection();
        $user = $db->fetchRow("users", sprintf("token = '%s'", $token));

        return $user;
    }

    /**
     * Get messages history of the user
     * @param $id
     * @return mixed
     */
    public static function getMessagesHistory($id) {
        return Message::findAll($id);
    }

    /**
     * Get new messages
     * @param $id
     * @return mixed
     */
    public static function getNewMessages($id) {
        return Message::findAll($id, null, false);
    }

    /**
     * Get user by id
     * @param $id
     * @return array
     */
    public static function getById($id) {
        $id = DbConnection::sanitize($id);

        $db = new DbConnection();
        $user = $db->fetchRow("users", sprintf("id = %d", $id));

        return $user;
    }
}