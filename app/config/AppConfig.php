<?php

/*
 * App configuration
 */

class AppConfig {

    function __construct() {}
    
    private $defaultController = "default";
    
    public function getDefaultController() {
        return $this->defaultController;
    }

}
