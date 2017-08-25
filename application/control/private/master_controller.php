<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 19/03/2015
 * Time: 12:45
 */

/*Helpers*/

include_once(__DIR__ . "/../../helpers/string_helper.php");

/*Models*/
include_once(__DIR__ . "/../../model/defaultModel.php");

class Master_controller {
    protected $root;
    protected $base_url;
    protected $site;
    protected $page;

    protected $defaultModel;

    protected $data_view; //  This date stores EVERY  output on the views of the site;

    public function __construct() {
        if(!$this->check_session()){
            session_unset();
            session_destroy();
            redirect("signin");

        } else {
            /*prepare DB and connection to DB tables */
            $this->defaultModel = new DefaultModel();

            $this->data_view["menu_options"] = $this->menu_options_model->select_menu_options($_SESSION["access_level"]);
            $this->data_view["all_sites"] = $this->site_info_model->select_all();

            $this->root = determine_base();
            $this->base_url = $this->root;
        }
    }

    public function index(){
        $this->load_view(null);
    }

    protected function load_view($page_name = null){
        $data_view = $this->data_view;
        $base_url = $this->base_url;

        if (!is_null($page_name)){
            $data_view["page_template"] = $page_name. "_template.php";
        }

        include_once(__DIR__ . "/../../views/private/master_template.php");
    }
}