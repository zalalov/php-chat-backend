<?php

/**
 * Class Application
 */
class Application {
    /**
     * Controller object
     * @var
     */
    private $_controller;

    /**
     * Action name
     * @var
     */
    private $_action;

    /**
     * Application response
     * @var
     */
    private $_response = [];

    /**
     * Application constructor.
     */
    function __construct() {
        $this->_parseRoute($_SERVER["REQUEST_URI"]);
        $this->_response = $this->_controller->execute($this->_action);
    }

    /**
     * Start the application
     */
    public static function start() {
        try {
            $app = new self();
            $response = $app->getResponse();
        } catch (Exception $e) {
            $response = [
                "error" => $e->getMessage(),
                "status" => $e->getCode()
            ];
        }

        header("Content-Type: application/json");
        echo json_encode($response);
    }

    /**
     * Parse route
     * @param $route
     * @throws Exception
     */
    private function _parseRoute($route) {
        global $config;
        $parts = explode("/", $route);

        try {
            $routes = $config["routes"];

            if (!in_array($_SERVER["REQUEST_URI"], $routes)) {
                throw new Exception("Page not found.", 404);
            }

            $controllerClass = ucwords($parts[2]) . "Controller";

            if (!class_exists($controllerClass)) {
                throw new Exception("Internal server error", 500);
            }

            $this->_controller = new $controllerClass();
            $this->_action = $parts[3];
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get response
     * @return mixed
     */
    public function getResponse() {
        return json_encode($this->_response);
    }

    /**
     * Add to response
     * @param $data
     */
    public function addToResponse($data) {
        $this->_response[] = $data;
    }
}