<?php
    session_start();
    if(isset($_SESSION["LOGGED"])){
        if (isset($_POST["color"]) && !empty($_POST["color"])) {
            if ($_POST["color"] != "#EF233C" && $_POST["color"] != "#157FFB" && $_POST["color"] != "#6F47BE" && $_POST["color"] != "#FDC02F" && $_POST["color"] != "#2FC898") {
                goback();
            }
            require "exec_db.php";
            $color = $_POST["color"];
            $set = $db->use_query("UPDATE `user` SET `img_color` = '$color' WHERE `user`.`ID` = ".$_SESSION['id'].";");
            $_SESSION['color']= $color;
            $log = $db->use_query("INSERT INTO `logs` (`ID`, `data`, `action`, `id_user`) VALUES (NULL, NOW(), 'Changed color', '".$_SESSION['id']."');");
            $db->close_connection();
            goback();
        }else{ goback(); }
    } else { header("Location: guest.php"); }

    function goback(){
        header("Location: ".$_SERVER['HTTP_REFERER']);
        exit;
    }
?>