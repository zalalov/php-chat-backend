<?php

class AbstractController {
    /**
     * Authenticate filter
     */
    const TYPE_FILTER_AUTH = "auth";

    /**
     * AbstractController constructor.
     */
    function __construct() {
        $this->checkFilters();
    }

    /**
     * Check controller's filters
     * @return bool
     * @throws Exception
     */
    public function checkFilters() {
        if (!isset($this->filters)) {
            return true;
        }

        try {
            foreach ($this->filters as $filter) {
                switch ($filter) {
                    case self::TYPE_FILTER_AUTH:
                        $user = User::getByToken($_POST["token"]);

                        if (!$user) {
                            throw new Exception("Permission denied.", 403);
                        }

                        break;

                    default:
                        throw new Exception("Unknown filter.");

                        break;
                }
            }
        } catch (Exception $e) {
            throw $e;
        }

        return true;
    }
}