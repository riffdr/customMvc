<?php
include_once(__DIR__ . '/master_model.php');

class DefaultModel extends master_model {

    public function __construct() {
        parent::__construct();
        $this->table_name  = "emails";
    }
}