<?php
    session_start();
    if(isset($_SESSION["LOGGED"])){
        if (isset($_POST["email"]) && !empty($_POST["email"])) {
            $email = trim($_POST["email"]);
            if (ctype_space($email)) {
                $_SESSION["email-error"] = "Please do not fill in the blank spaces!";
                goback();
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION["email-error"] = "Filled email is not a valid!";
                goback(); 
            }
            if ($email == $_SESSION['email']) {
                $_SESSION["email-error"] = "Email entered is the same as the account email!";
                goback(); 
            }
            require "exec_db.php";
            $check = $db->use_query("SELECT email FROM `user` WHERE email = '$email';");
            if ($check->num_rows > 0) {
                $db->close_connection();
                $_SESSION["email-error"] = "Email entered is already used!";
                goback(); 
            }
            $set = $db->use_query("UPDATE `user` SET `email` = '$email' WHERE `user`.`ID` = ".$_SESSION['id'].";");
            $_SESSION['email'] = $email;
            $_SESSION["email-success"] = "Email was successfully changed!";
            $log = $db->use_query("INSERT INTO `logs` (`ID`, `data`, `action`, `id_user`) VALUES (NULL, NOW(), 'Changed email into $email', '".$_SESSION['id']."');");
            $db->close_connection();
            goback();
        }else{ goback(); }
    } else { header("Location: guest.php"); }

    function goback(){
        header("Location: ".$_SERVER['HTTP_REFERER']);
        exit;
    }
?>