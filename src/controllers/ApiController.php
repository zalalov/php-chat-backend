<?php

/**
 * Class ApiController
 */
class ApiController extends AbstractController {
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

    public function actionHistory(User $user) {}

    public function actionNew(User $user) {}

    public function actionSend(User $user, $toUserId) {}
}