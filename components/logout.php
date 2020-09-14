<?php
    session_start();
    if( isset($_SESSION["LOGGED"]) ){
        session_destroy();
        goback();
    } else {
        goback();
    }
    function goback()
    {
        header("Location: ".$_SERVER['HTTP_REFERER']);
        exit;
    }
?>