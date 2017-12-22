<?php

class core {

    function __construct() {
        
    }

    public function start() {
        $this->invokeControllerFunction();
    }

    public function invokeControllerFunction() {
        $uris = $this->getURIs();
        $this->import("app/config/AppConfig.php");
        $this->import("app/controllers/core/CoreController.php");
        $this->import("app/models/core/CoreModel.php");

        $appConfig = new AppConfig();

        $controller = strtolower(isset($uris[0]) && $uris[0] != "" ? $uris[0] : $appConfig->getDefaultController());
        $function = isset($uris[1]) && $uris[1] != "" ? $uris[1] : "index";
        $data = array_slice($uris, 2);

        $controllerPath = "app/controllers/$controller.php";
        if (!$this->isFileExist($controllerPath))
            $this->show_404();

        $this->import($controllerPath);

        if (!class_exists($controller, false)) {
            $this->show_404();
        }

        $controller = ucfirst($controller);
        $controllerObject = new $controller;
        
        $controllerObject->config =& $appConfig;

        if (!method_exists($controllerObject, $function)) {
            $this->show_404();
        }

        $libs = $appConfig->getLibraries();
        foreach ($libs as $lib) {
            $libPath = "app/libs/$lib.php";
            $this->import($libPath);
            $controllerObject->$lib = new $lib();
            if ($lib=="db") {
                $controllerObject->$lib->config = $controllerObject->config->getDBConfig();
                $controllerObject->$lib->init();
            }
        }

        $models = $appConfig->getModels(strtolower($controller));    
        foreach ($models as $model) {
            $modelPath = "app/models/$model.php";
            $this->import($modelPath);    
            $controllerObject->$model = new $model();
        }

        $controllerObject->$function();
    }

    public function getURIs() {
        $uris = $_SERVER['REQUEST_URI'];
        if ($uris[0] == "/")
            $uris[0] = "";
        $lastStringIndex = strlen($uris) - 1;
//        if ($uris[$lastStringIndex] == "/")
//            $uris[$lastStringIndex] = "";

        $uriSet = explode("/", $uris);
        array_shift($uriSet);
        return $uriSet;
    }

    public function import($path = "") {
        require_once WORKING_DIRECTORY . $path;
    }

    public function isFileExist($path = "") {
        $filePath = strval(str_replace("\0", "", WORKING_DIRECTORY . $path));
        return file_exists($filePath);
    }

    public function show_404() {
        die("404 page not found!");
    }

}
