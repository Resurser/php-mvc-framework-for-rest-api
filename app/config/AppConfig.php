<?php

/*
 * App configuration
 */

class AppConfig {

    function __construct() {
        
    }

    private $defaultController = "default";
    private $libraries = ['console', 'output', 'db'];
    private $models = [
        'test' => ['user']
    ];
    private $dbConfig = [
        "host" => "localhost",
        "username" => "root",
        "password" => "",
        "database" => "cx_pulse",
    ];

    public function getModels($controller = "") {
        if (!array_key_exists($controller, $this->models))
            die("No models are defined for '$controller' controller");
        return $this->models[$controller];
    }

    public function getDefaultController() {
        return $this->defaultController;
    }

    public function getLibraries() {
        return $this->libraries;
    }

    public function getDBConfig() {
        return $this->dbConfig;
    }

}
