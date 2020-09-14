<?php
    session_start();
    if(isset($_SESSION["LOGGED"])){
        if (isset($_GET["id"]) && !empty($_GET["id"])) {

            require "exec_db.php";
            $exist = $db->use_query("SELECT ID FROM `comments` WHERE ID = ".$_GET['id'].";");
            if($exist->num_rows > 0){
                $id = $_GET["id"];
                $id_user = $_SESSION['id'];
                $delete = $db->use_query("DELETE FROM `comments` WHERE `comments`.`ID` = ".$_GET['id'].";");
                $log = $db->use_query("INSERT INTO `logs` (`ID`, `data`, `action`, `id_user`) VALUES (NULL, NOW(), 'Comment $id was deleted', '$id_user ');");
                $db->close_connection();
                goback();
            }else{ goback(); }
        }else{ header("Location: index.php"); }
    } else { header("Location: index.php"); }

    function goback(){
        header("Location: ".$_SERVER['HTTP_REFERER']);
        exit;
    }
?>
