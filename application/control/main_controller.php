<?php include_once(__DIR__ . '/master_controller.php');

/*Models*/
include_once(__DIR__ . "../model/default_model.php");


class Main_controller extends master_controller {


    protected $defaultModel;

    public function __construct() {
        $this->default_model = new DefaultModel();
        parent::__construct();
        $this->data_view["all_users"] =  $this->default_model->select_all();
    }

    public function index(){
        $this->data_view["page_heading"] =  "Main page";
        $this->load_view("main");

    }

}