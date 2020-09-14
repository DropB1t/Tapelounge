<?php
    session_start();
    if( isset($_GET["email"]) ){
        require "exec_db.php";
        
        $data = $db->use_query("SELECT active FROM user WHERE email='".$_GET['email']."'");
        $account = mysqli_fetch_assoc($data);
        if($account["active"] == 0){
            $update = $db->use_query("UPDATE `user` SET `active` = '1' WHERE `user`.`email` = '".$_GET["email"]."';");
            $data = $db->use_query("SELECT ID FROM user WHERE email='".$_GET['email']."'");
            if($data->num_rows > 0){
                $account = mysqli_fetch_assoc($data);
                $log = $db->use_query("INSERT INTO `logs` (`ID`, `data`, `action`, `id_user`) VALUES (NULL, NOW(), 'Registered', '".$account['ID']."');");
            }
            $_SESSION["confirmed"] = true;
        }
        $db->close_connection();
        header("Location: ../guest.php");
        exit();
    }
    else{
        header("Location: ../index.php");
        exit();
    }
?>