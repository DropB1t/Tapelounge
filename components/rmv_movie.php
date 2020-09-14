<?php
    session_start();
    if(isset($_SESSION["LOGGED"])){
        if (isset($_GET["id"])) {
            require "exec_db.php";
            $data = $db->use_query("SELECT id_user FROM `favourite` WHERE ID_movie = ".$_GET['id']." and id_user = ".$_SESSION['id'].";");
            if($data->num_rows > 0){
                $id = $_GET["id"];
                $id_user = $_SESSION['id'];
                $rmv = $db->use_query("DELETE FROM `favourite` WHERE `favourite`.`ID_movie` = ".$_GET['id']." AND `favourite`.`id_user` = ".$_SESSION['id'].";");
                $log = $db->use_query("INSERT INTO `logs` (`ID`, `data`, `action`, `id_user`) VALUES (NULL, NOW(), 'Removed $id from favourite', '$id_user');");
                $db->close_connection();
                goback();
            } else {
                $db->close_connection();
                goback();
            }
            
        }else{ goback(); }
    } else { goback(); }

    function goback(){
        header("Location: ".$_SERVER['HTTP_REFERER']);
        exit;
    }
?>