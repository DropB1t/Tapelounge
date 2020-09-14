<?php
    session_start();
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    // Load Composer's autoloader
    require_once '../vendor/autoload.php';
    if ( !empty($_POST) ) {
        $_SESSION["r-errors"] = [];

        if ( ctype_space($_POST["username"]) || ctype_space($_POST["password"]) || ctype_space($_POST["email"])){
            $_SESSION["r-errors"][] = "Please do not fill in the blank spaces!";
            goback();
        }

        if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            $_SESSION["r-errors"][] = "Filled email is not a valid!";
            goback(); 
        }

        require "exec_db.php";
        $data = $db->use_query("SELECT email FROM user WHERE email='".$_POST['email']."'");
        if($data->num_rows > 0){
            $_SESSION["r-errors"][] = "Email is already taken!";
            $db->close_connection();
            goback(); 
        }

        $data = $db->use_query("SELECT username FROM user WHERE username='".$_POST['username']."'");
        if($data->num_rows > 0){
            $_SESSION["r-errors"][] = "Username is already taken!";
            $db->close_connection();
            goback();
        }

        $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_STRING );
        $username = filter_var(trim($_POST["username"]), FILTER_SANITIZE_STRING );
        $password = filter_var(trim($_POST["password"]), FILTER_SANITIZE_STRING );
        $password = md5($password."videotape");
        $execute = $db->use_query("INSERT INTO `user` (`ID`, `username`, `password`, `email`, `registered`, `active`, `img_color`) VALUES (NULL, '$username', '$password', '$email', NOW(), '0', '#EF233C');");
        
        if($execute){
            send_mail($email);
            unset($_POST);
            $db->close_connection();
            $_SESSION["registrated"] = true;
            goback();
        }
        $db->close_connection();
        goback();
    } else { goback(); }

    /* function send_mail($email) {
    	// Multiple recipients
        $to = $email; // note the comma

        // Subject
        $subject = 'Tape Lounge - Confirm Your Registration';

        // Message
        $message = '
        <html>
          <body>    
                <h3>Confirm your Account Registration -> </h3>
                <a href="tapelounge.ddns.net/tapelounge/components/r-confirm.php?email='.$email.'">CONFIRM</a>
          </body>
      	</html>
        ';

        // To send HTML mail, the Content-type header must be set
        $headers[] = 'MIME-Version: 1.0\r\n';
        $headers[] = 'Content-type: text/html; charset=iso-8859-1\r\n';

        // Additional headers
        $headers[] = 'Reply-To: User <'.$email.'>\r\n';
        $headers[] = 'From: Tape Lounge <tape.lounge.web@gmail.com>\r\n';

        // Mail it
        mail($to, $subject, $message, implode("\r\n", $headers));
    } */

    function send_mail($email) {
        
        $mail = new PHPMailer(true);

        //Server settings
        $mail->IsSMTP();
        $mail->Mailer = "smtp";
        $mail->SMTPDebug  = 0;
        $mail->SMTPAuth   = TRUE;
        $mail->SMTPSecure = "tls";
        $mail->Port       = 587;
        $mail->Host       = "smtp.gmail.com";
        $mail->Username   = "tape.lounge.web@gmail.com";
        $mail->Password   = "fdbebwkztrfwbqqw";

        
        $mail->AddAddress($email);
        $mail->SetFrom("tape.lounge.web@gmail.com", "Tape Lounge");
        $mail->AddBCC("tape.lounge.web@gmail.com");

        $mail->IsHTML(true);
        $mail->Subject = "Tape Lounge - Confirm Your Registration";
        $content = '<div style="display: flex; align-items: center; justify-content: center;"><h4>Confirm your Account Registration ->  </h4> <a href="http://tapelounge.ddns.net/tapelounge/components/r-confirm.php?email='.$email.'">CONFIRM</a></div>';
        $mail->Body = $content;
        $mail->Send();
        /* if(!$mail->Send()) {
            echo "Error while sending Email.";
            var_dump($mail);
        } else {
            echo "Email sent successfully";
        } */
    }
    function goback()
    {
        header("Location: ".$_SERVER['HTTP_REFERER']);
        exit;
    }
?>
