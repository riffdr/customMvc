<?php

class Db_connection {
    protected $con;
    protected $mode;

    public function __construct() {
        $this->mode = (isset($_SERVER["SERVER_ADDR"]) && $_SERVER["SERVER_ADDR"]=='127.0.0.1')?'local':'production';
        if(isset($this->mode) && $this->mode=='production') {
            $dbhost = 'localhost';
            $dbuser = 'yourUser';
            $dbpass = 'localPass';
            $dbname = 'localDbName';
        }else{
            $dbhost = 'productionHost';
            $dbuser = 'prodUser';
            $dbpass = 'pass';
            $dbname = 'prodDb';
        }


        $this->con = @mysqli_connect($dbhost,$dbuser,$dbpass, $dbname );
        $this->check_DB_connectivity();
    }

    public function return_db (){
        return $this->con;
    }

    public function close_db(){
        mysqli_close($this->con);
    }

    public function check_DB_connectivity(){

        /* If Error message to display */
        if (mysqli_connect_errno()){
            $this->send_error_email("connection error");
            return false;
        }
    }

    protected function send_error_email($message = null){
        // the message
        $msg = "Database for Admin: ".$message ." at ".date("d-m-Y H:i:s");

        // use wordwrap() if lines are longer than 70 characters
        $msg = wordwrap($msg,70);

        // send email
        mail("yourEmail@mail.com","Database error".$message,$msg);
    }
}

?>
