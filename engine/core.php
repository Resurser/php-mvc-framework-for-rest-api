<?php

class core {

    function __construct() {
        
    }

    public function start() {
        $this->invokeControllerFunction();
    }

    public function invokeControllerFunction() {
        $uris = $this->getURIs();
        var_dump($uris);
        $this->import("app/config/AppConfig.php");
        $this->import("app/controllers/core/CoreController.php");

        $appConfig = new AppConfig();

        $controller = (isset($uris[0]) && $uris[0] != "" ? $uris[0] : $appConfig->getDefaultController());
        $function = isset($uris[1]) && $uris[1] != "" ? $uris[1] : "index";
        $data = array_slice($uris, 2);

        $controllerPath = 'app/controllers/' . $controller . '.php';
        
        if (!$this->isFileExist($controllerPath))
            $this->show_404();

        $this->import($controllerPath);
        
        p("controller " . $controller);
        p("function " . $function);
        p($data);

        if (!class_exists($controller, false)) { echo "controller exist";
          //  $this->show_404();
        }
        
        $controller = ucfirst($controller);
        
        $controllerObject = new $controller;

        if (!method_exists($controllerObject, $function)) { echo "function exist";
            $this->show_404();
        }
    }

    public function getURIs() {
        $uris = $_SERVER['REQUEST_URI'];
        if ($uris[0] == "/")
            $uris[0] = "";
        $lastStringIndex = strlen($uris) - 1;
        if ($uris[$lastStringIndex] == "/")
            $uris[$lastStringIndex] = "";

        $uriSet = explode("/", $uris);
        array_shift($uriSet);
        return $uriSet;
    }

    public function import($path = "") {
        require_once WORKING_DIRECTORY . $path;
    }

    public function isFileExist($path = "") {
        $filePath = strval(str_replace("\0", "", WORKING_DIRECTORY . $path));
        echo $filePath;
        return file_exists($filePath);
    }

    public function show_404() {
        die("404 page not found!");
    }

}
