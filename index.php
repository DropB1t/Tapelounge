<?php
    session_start();
    include "components/header.php";

    /* if(isset($_SESSION["LOGGED"])){
        header("Location: home.php");
    }
    else{ */
    header("Location:guest.php");
    /* } */
    
    include "components/scripts.php";
?>