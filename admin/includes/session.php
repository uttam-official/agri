<?php 
    !isset($_SESSION)?session_start():'';
    function set_session($key,$value){
        $_SESSION[$key]=$value;
        return 1;
    }
    function unset_session($key){
        unset($_SESSION[$key]);
        session_destroy();
        return 1;
    }
    function set_flash_session($key,$message){
        $_SESSION[$key]=$message;
        return 1;
    }
    function show_flash($key){
        echo isset($_SESSION[$key])?$_SESSION[$key]:'';
        unset($_SESSION[$key]);
    }

?>