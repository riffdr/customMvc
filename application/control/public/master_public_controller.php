<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 25/12/2016
 * Time: 12:45
 */

class Master_Public_Controller {

    protected $site_languages;
    public $lang;
    protected $translationLibrary;

    public function __construct($lang = null) {
        $this->site_languages = array("en", "de");
        $this->lang =  !$lang || !in_array($lang, $this->site_languages) ?  "en" : $lang;
        $this->_setSessionLanguage();
    }

    public function index(){
        $this->_loadView();
    }

    private function _setSessionLanguage(){
        session_start();
        $_SESSION['session_lang'] = $this->lang;
        session_write_close();
    }

    private function _loadView($page = null){
        include_once(__DIR__ . "/../libraries/translations/".$this->lang."_lang_library.php");  // Load translation library for the selected language
        // Method to load the views from any controller that extends the Main_controller class. If no page is defined, the main_view template will be loaded
        if(!$page){
            include_once(__DIR__ . "/../views/main_view.php");
        }else{
            include_once(__DIR__ . "/../views/".$page."_view.php");
        }
    }

}