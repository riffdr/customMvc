<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 19/03/2015
 * Time: 12:45
 */

/*Helpers*/

include_once(__DIR__ . "../helpers/string_helper.php");



class Master_controller {
    protected $root;
    protected $base_url;
    protected $site;
    protected $page;

    protected $data_view; //  This date stores EVERY  output on the views of the site;

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
        include_once(__DIR__ . "../libraries/translations/".$this->lang."_lang_library.php");  // Load translation library for the selected language
        // Method to load the views from any controller that extends the Main_controller class. If no page is defined, the main_view template will be loaded
        if(!$page){
            include_once(__DIR__ . "/../../views/public/main_view.php");
        }else{
            include_once(__DIR__ . "/../../views/".$page."_view.php");
        }
    }

}