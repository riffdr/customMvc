<?php
/* routes functions/helper */
function get_http(){
    return 'http://'.$_SERVER['HTTP_HOST'];
}
function get_http_dynamic(){ //
    if ($_SERVER['REQUEST_URI'] == "/") {
        return 'http://'.$_SERVER['HTTP_HOST'];
    } else {
        return 'http://'.$_SERVER['HTTP_HOST']."".$_SERVER['REQUEST_URI'];
    }
}

function get_document_root(){ // To be used in completing PHP includes
    return $_SERVER['DOCUMENT_ROOT'];
}

function get_last_route(){ // To be used for dynamically  completing URL linkage
    if ($_SERVER['REQUEST_URI'] == "/") {
        return "";
    }
    else {
        return $_SERVER['REQUEST_URI'];
    }
}

function redirect_url($newURL){
    header('Location: '.$newURL);
}

?>