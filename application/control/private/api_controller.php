<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 25/12/2016
 * Time: 12:45
 */

class Api_controller   {
    private $lang;
    private $api_url;
    private $api_data;
    private $cache_time;

    public function __construct( ) {
        error_reporting(0);
        $this->_setLanguage();
        $this->api_url = "http://www.betvictor.com/live/". $this->lang."/live/list.json";
        $this->cache_time = "+15 minutes";
        $this->api_data = $this->_getCachedApiData();
    }

    public function index(){
    }

    private function _setLanguage(){
        // Uses the $_SESSION variable to customize the API url depending on the language
        session_start();
        $this->lang = (isset($_SESSION["session_lang"]) && !empty($_SESSION["session_lang"])) ? $_SESSION["session_lang"] : "en";
    }

    public function getSportsData($sportId = null, $eventId = null){
        // Processes the API Data. When its parameters are not null, it will deepen into the API's  structure:  sports->events->outcomes
        $dataApiCall = array();
        if(is_null($sportId) && is_null($eventId)){ // For API call /sports. Returns all sports
            $dataApiCall = $this->api_data["sports"];
        }else if(!is_null($sportId) && is_null($eventId)){ // For API call /sports/SPORT-ID/  Returns all events given a sport ID
            $dataApiCall = $this->_getEventsBySport($this->api_data["sports"], $sportId);
        }else  if(!is_null($sportId) && !is_null($eventId)){ // For API call /sports/SPORT-ID/events/EVENT-ID  Returns all outcomes given a event ID and its sport ID
            $dataApiCall = $this->_getOutcomesBySportEvent($this->_getEventsBySport($this->api_data["sports"], $sportId), $eventId);
        }
        echo json_encode($dataApiCall);
    }



}