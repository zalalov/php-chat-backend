<?php

/**
 * Class ApiController
 */
class MessageController extends AbstractController {
    /**
     * Action filters
     * @var array
     */
    public $filters = [
        "auth" => [
            "history",
            "new",
            "send"
        ]
    ];

    /**
     * Messages history
     * @return mixed
     */
    public function actionHistory() {
        return User::getMessagesHistory($this->_authUser["id"]);
    }

    /**
     * Return new messages
     */
    public function actionNew() {
        return User::getNewMessages($this->_authUser["id"]);
    }

    /**
     * Send message
     * @throws Exception
     */
    public function actionSend() {
        if (!isset($_POST["to_user_id"])) {
            throw new Exception("`to_user_id` param should be specified.");
        }

        if (!isset($_POST["text"]) || isset($_POST["text"]) && !strlen($_POST["text"])) {
            throw new Exception("Message text invalid.");
        }

        $toUser = User::getById($_POST["to_user_id"]);
        $fromUser = User::getById($_POST["from_user_id"]);
        $text = $_POST["text"];

        if (!$toUser || !$fromUser) {
            throw new Exception("User not found.");
        }

        Message::create($toUser["id"], $fromUser["id"], $text);

        return ["success"];
    }
}