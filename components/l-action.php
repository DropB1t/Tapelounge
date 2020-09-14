<?php
    session_start();

    if ( !empty($_POST) ) {
        $_SESSION["l-errors"] = [];

        if (ctype_space($_POST["password"]) || ctype_space($_POST["email"])){
            $_SESSION["l-errors"][] = "Please do not fill in the blank spaces!";
            goback();
        }

        if (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
            $_SESSION["l-errors"][] = "Filled email is not a valid!";
            goback();
        }

        require "exec_db.php";
        $data = $db->use_query("SELECT email FROM user WHERE email='".$_POST['email']."'");
        if($data->num_rows == 0){
            $_SESSION["l-errors"][] = "Email is not registered!";
            $db->close_connection();
            goback();
        }

        $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_STRING );
        $password = filter_var(trim($_POST["password"]), FILTER_SANITIZE_STRING );
        $password = md5($password."videotape");

        $data = $db->use_query("SELECT * FROM user WHERE email='$email' AND password='$password'");

        if($data->num_rows > 0){
            $account = mysqli_fetch_assoc($data);
            if($account["active"] == 1){
                $log = $db->use_query("INSERT INTO `logs` (`ID`, `data`, `action`, `id_user`) VALUES (NULL, NOW(), 'Logged', '".$account['ID']."');");
                /* "ID username password email active img_color "; */
                $_SESSION['LOGGED']= true;
                $_SESSION['id']= $account["ID"];
                $_SESSION['username']= utf8_encode($account["username"]);
                $_SESSION['email']= $account["email"];
                $dt = new DateTime($account["registered"]);
                $_SESSION['registered']= $dt->format('d/m/Y');
                $_SESSION['color']= $account["img_color"];

                $db->close_connection();
                goback();
            }
            else {
                $_SESSION["l-errors"][] = "Account is not confirmed, please check your email box!";
                $db->close_connection();
                goback();
            }
        } else {
            /* unset($_POST); */
            $_SESSION["l-errors"][] = "Email or password not valid!";
            $db->close_connection();
            goback();
        }
    }
    function goback()
    {
        header("Location: ".$_SERVER['HTTP_REFERER']);
        exit;
    }
?>