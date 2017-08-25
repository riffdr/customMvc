<?php include_once(__DIR__ . '/master_controller.php');

class Users_controller extends master_controller {

    public function __construct() {
        parent::__construct();
        $this->data_view["all_users"] =  $this->users_model->select_all();
    }

    public function index(){
        $this->data_view["page_heading"] =  "BackOffice Users";
        $this->load_view("users");

    }





}