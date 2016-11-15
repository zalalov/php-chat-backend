<?php

class AbstractController {
    /**
     * Authenticate filter
     */
    const TYPE_FILTER_AUTH = "auth";

    /**
     * Authenticated user object
     * @var null
     */
    protected $_authUser = null;

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
            foreach ($this->filters as $filter => $methods) {
                switch ($filter) {
                    case self::TYPE_FILTER_AUTH:
                        $user = User::getByToken($_POST["token"]);

                        if (!$user) {
                            throw new Exception("Permission denied.", 403);
                        }

                        $this->_authUser = $user;

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

    /**
     * Execute action
     * @param $action
     * @return mixed
     * @throws Exception
     */
    public function execute($action) {
        $method = "action" . ucwords($action);

        if (!method_exists($this, $method)) {
            throw new Exception("Method not exists.");
        }

        return $this->{$method}();
    }
}