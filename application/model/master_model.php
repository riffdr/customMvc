<?php
include_once(__DIR__ . '/db_connection.php');


class Master_model extends Db_connection{
    protected $database;
    protected $table_name; // VAlue will be initialized given a table name . Rule of thumb: name your models according to your DB table names

    public function __construct() {
        parent::__construct();
        $database_connection = new Db_connection();

        $this->database = $database_connection->return_db();
    }

    public function select_all(){ // Return all rows from table
        $query = "SELECT * FROM " .$this->table_name;
        $result = $this->database->query($query);

        return  $this->parse_sql_data($result);
    }

    public function select($string_what){ // Returns all rows and column from table give an column name
        $query = "SELECT ".$string_what." FROM " .$this->table_name;
        $result = $this->database->query($query);

        return  $this->parse_sql_data($result);
    }

    public function select_where($selection, $where, $res_number = null){ // Returns all columns for a $res_number of rows where Array $where('key'=>value) is met
        $array_keys = array_keys($where);
        $array_values = array_values($where);

        $query = "SELECT ".$selection." FROM " .$this->table_name;

        $query .= " WHERE ";
        for($i=0; $i<count($array_keys); $i++){
            $query .= ($i<(count($array_keys)-1))?
                " `".$array_keys[$i]."` = '".$array_values[$i]."' AND ":
                " `".$array_keys[$i]."` = '".$array_values[$i]."' ";
        }

         if ($result = $this->database->query($query)){
            $parsed = $this->parse_sql_data($result);
            return (isset($res_number) && isset($parsed[$res_number]))?  $parsed[$res_number]:     $parsed;
        }else{
            return false;
        }


    }

    public function insert($data){
        $table_fields = array_keys($data);

        $query = "INSERT INTO " .$this->table_name. "(";
            for($i=0; $i<count($table_fields); $i++){
                $query .= ($i<(count($table_fields)-1))? $table_fields[$i].",": $table_fields[$i] ;
            }
        $query .= ") VALUES (";
            for($i=0; $i<count($table_fields); $i++){
                $query .= ($i<(count($table_fields)-1))? "'".$data[$table_fields[$i]]."',": "'".$data[$table_fields[$i]]."'" ;
            }
        $query .= ")";


        return ($this->database->query($query)) ? $this->database->insert_id : false;
    }

    public function update($data, $where){
        $table_fields = array_keys($data);
        $where_fields = array_keys($where);

        $query = "UPDATE `" .$this->table_name. "` SET ";
        for($i=0; $i<count($table_fields); $i++){
            $query .= ($i<(count($table_fields)-1))?
                " `".$table_fields[$i]."` = '".$data[$table_fields[$i]]."' ,":
                " `".$table_fields[$i]."` = '".$data[$table_fields[$i]]."' ";

        }
        $query .= " WHERE ";
        for($i=0; $i<count($where_fields); $i++){

            $query .= ($i<(count($where_fields)-1))?
                " `".$where_fields[$i]."` = '".$where[$where_fields[$i]]."' AND ":
                " `".$where_fields[$i]."` = '".$where[$where_fields[$i]]."' ";
        }

        return ($this->database->query($query)) ? true : false;
    }

    public function delete($where){
        $where_fields = array_keys($where);
        $query = "DELETE FROM " .$this->table_name. " WHERE ";
        for($i=0; $i<count($where_fields); $i++){
            $query .= ($i<(count($where_fields)-1))?
                " `".$where_fields[$i]."` = '".$where[$where_fields[$i]]."' AND ":
                " `".$where_fields[$i]."` = '".$where[$where_fields[$i]]."' ";
        }
        return ($this->database->query($query)) ? true : false;
    }


    public function parse_sql_data($result){
        $data = array();
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            array_push($data, $row);
        }
        return $data;
    }


    public function parse_sql_data_with_encoding($result){ // Encodes everything onto ut8 for non anglo saxon characters
        $data = array();
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            foreach ($row as $key => $value){
                $row[$key] = utf8_encode($value);
            }
            array_push($data, $row);
        }
            return $data;
    }

    public function parse_sql_data_with_decoding($result){ // Encodes everything onto ut8 for non anglo saxon characters
        $data = array();
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            foreach ($row as $key => $value){
                $row[$key] = utf8_decode($value);
            }
            array_push($data, $row);
        }
        return $data;
    }


    public function close_db_connection(){
        mysqli_close($this->database);
    }

    public function safe_query($query){
        if (!$this->database->query($query)) {
            printf("Errormessage: %s\n", $this->database->error);
            return false;
        }else{
            return true;
        }
    }

    public function sanitize($str)    {
        return $this->database->real_escape_string(trim(($str)));
    }

    protected function query_with_sanitation($query){
        $query = $this->sanitize($query);
        if($result = $this->database->query($query)){
            return $result;
        }else{
            return false;
        }
    }


}