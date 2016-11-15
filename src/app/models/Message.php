<?php

/**
 * Class Message
 */
class Message {
    /**
     * Find all messages
     * @param $to
     * @param null $from
     * @param null $read
     * @return mixed
     */
    public static function findAll($to, $from = null, $read = null) {
        $to = DbConnection::sanitize($to);
        $conditions = [
            sprintf("to_user_id = %d", $to)
        ];

        if ($from) {
            $from = DbConnection::sanitize($from);

            $conditions[] = sprintf("from_user_id = %d", $from);
        }

        if ($read !== null) {
            $read = DbConnection::sanitize($read);

            $conditions[] = sprintf("read IS %s", $read ? 1 : 0);
        }

        $db = new DbConnection();

        return $db->fetchRows("messages", implode(" AND ", $conditions));
    }

    /**
     * Create new message
     * @param $to
     * @param $from
     * @param $text
     */
    public static function create($to, $from, $text) {
        $toUser = User::getById($to);
        $fromUser = User::getById($from);
        $text = DbConnection::sanitize($text);

        $db = new DbConnection();
        return $db->insert("messages", [
            "from_user_id" => $fromUser["id"],
            "to_user_id" => $toUser["id"],
            "text" => $text
        ]);
    }
}