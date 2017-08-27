<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 25/12/2016
 * Time: 12:45
 */
/*
 * Runs the Router Controller, URLS will be broken down into controllers, function and their respective parameters.
 * ADDING NEW ROUTES: Use the array $routesTable to include a new route.  Insert a new value following the rules below:
 * a. CLASSES: , remember that each route must at least include a class
 * b. FUNCTIONS: If no function is stated, method index() will be triggered by default
 * c. PARAMETERS: All Parameters, even if only one, must be inserted into an array.  For each dynamic parameter, include the same reg expression both in the key of the router and its parameter array.
 */

class Router{

    private $routesTable;
    private $request;
    protected $baseUrl;

    public function __construct(){

        $this->routesTable = array(
            "/" => array(
                "class" => "main_controller",
            ),

            /* For language support, i.e www.myDomain.com/en  */
            "/[a-z]{2}/" => array(
                "class" => "main_controller",
                "parameters" => array("[a-z]{2}")
            ),

            /* Example routes  */
            "/sample/" => array(
                "class" => "sampleControllerClass",
                "function" => "sampleMethod"
            ),

            "/sample/([0-9]*)/" => array(
                "class" => "sampleControllerClass",
                "function" => "sampleMethod",
                "parameters" => array("([0-9]*)")
            ),
s
        );
        $this->_parseUrlRequest();
        $this->_runRequest();
    }

    private function _parseUrlRequest(){

        $request = null;
        $uri = urldecode($_SERVER["REQUEST_URI"]);
        $route_array = explode('/', $uri);

        foreach ($this->routesTable as $key => $value) {
            if (preg_match("|^" . $key . "$|", $uri)) {
                $request = $value;
                $final_param = null;

                if (isset($value["parameters"] )){
                    $final_param = array();
                    $parameters_array_url = array_filter($route_array);
                    foreach($parameters_array_url as $array_element){
                        foreach($value["parameters"] as $key => $parameter){
                            if(preg_match("|^" . $parameter . "$|",  $array_element)){
                                array_push($final_param, $array_element);
                                unset($value["parameters"][$key]);
                                break;
                            }
                        }
                    }
                    $request["parameters"] = $final_param;
                }
                break;
            }
        }
        $this->request = $request;

    }

    private function _runRequest(){

        global $baseUrl;

        $controller_file_directory =  __DIR__.'/'.$this->request["class"].'.php';
        include_once($controller_file_directory);

        if(!isset($this->request["function"]) && !isset($this->request["parameters"])){
            $refClass = new ReflectionClass($this->request["class"]);
            $instance = $refClass->newInstanceArgs();
            $instance->index();
        }
        elseif(!isset($this->request["function"]) && isset($this->request["parameters"])){
            $refClass = new ReflectionClass($this->request["class"]);
            $instance = $refClass->newInstanceArgs($this->request["parameters"]);
            $instance->index();
        }
        elseif(isset($this->request["function"]) && !isset($this->request["parameters"])){
            call_user_func(array(new $this->request["class"](), $this->request["function"]));
        }
        elseif(isset($this->request["function"]) && isset($this->request["parameters"])) {
            call_user_func_array(array(new $this->request["class"](), $this->request["function"]), $this->request["parameters"]);
        }
    }
}