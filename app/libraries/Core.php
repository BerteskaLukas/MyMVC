<?php
    /*
    * App Core Class
    * Creates URL and loads core controller
    * URL FORMAT - /controller/method/params
    */
    class Core{
        protected $currentController = 'Pages';
        protected $currentMethod = 'index';
        protected $params = [];

        public function __construct(){

            // print_r($this->getUrl());
            $url=$this->getUrl();

            // Look in controllers for firs value
            if(file_exists('../app/controllers/'.ucwords($url[0]).'.php')){//ucwords funkcija pirma raide zodzio padaro dydziaja raide

            // if exists, set as controller
            $this->currentController = ucwords($url[0]);

            //unset 0 index
            unset($url[0]);
            }

            // Require the controller
            require_once '../app/controllers/'.$this->currentController.'.php';

            //Instantiate controller class
            $this->currentController = new $this->currentController;

            // Check for second part of url
            if(isset($url[1])){

                //Check to see if method exists in controller
                if(method_exists($this->currentController,$url[1])){
                    $this->currentMethod = $url[1];

                //unset 1 index
                unset($url[1]);
                }
            }
            //Get params
            $this->params = $url ? array_values($url):[]; //array_values() returns all the values from the array and indexes the array numerically.

            // Call a callback with array of params
            call_user_func_array([$this->currentController,$this->currentMethod],$this->params);// praleidazia url masyvo pirmus du narius ir visus likusius sudeda i masvya params


        }

        public function getUrl(){
            if(isset($_GET['url'])){
                $url = rtrim($_GET['url'], '/'); //rtrim panakinta tuscia vieta url po texto ir uz deda  gale /
                $url = filter_var($url, FILTER_SANITIZE_URL);// paziuri kad nebutu zenklu kurie negali buti url
                $url = explode('/',$url);// sudauzo url pagal / ir sudeda i url masyva 
                return $url;
            }
        }
    }