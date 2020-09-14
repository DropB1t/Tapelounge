<?php
    session_start();
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require_once 'vendor/autoload.php';
    if(isset($_SESSION["LOGGED"])){
        $email = $_SESSION["email"];
        require "components/exec_db.php";
        $check = $db->use_query("SELECT psw_reset.ID FROM user,psw_reset WHERE email = '$email' AND user.ID = psw_reset.id_user AND data_expiration > NOW();");
        if ($check->num_rows > 0) {
            $db->close_connection();
            $_SESSION["email-error"] = "This email has already a valid reset password request";
            header("Location: options.php");
            exit;
        }
        create_token($email,$db);
    } else {
        if (isset($_POST["email"]) && !empty($_POST["email"])) {
            $email = trim($_POST["email"]);
            if (ctype_space($email)) {
                $_SESSION["email-error"] = "Please do not fill in the blank spaces";
                header("Location: ".$_SERVER['PHP_SELF']);
                exit;
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION["email-error"] = "Filled email is not a valid";
                header("Location: ".$_SERVER['PHP_SELF']);
                exit;
            }
            require "components/exec_db.php";
            $check = $db->use_query("SELECT email FROM `user` WHERE email = '$email';");
            if ($check->num_rows == 0) {
                $db->close_connection();
                $_SESSION["email-error"] = "This email is not registered";
                header("Location: ".$_SERVER['PHP_SELF']);
                exit;
            }
            $check = $db->use_query("SELECT psw_reset.ID FROM user,psw_reset WHERE email = '$email' AND user.ID = psw_reset.id_user AND data_expiration > NOW();");
            if ($check->num_rows > 0) {
                $db->close_connection();
                $_SESSION["email-error"] = "This email has already a valid reset password request";
                header("Location: ".$_SERVER['PHP_SELF']);
                exit;
            }
            create_token($email,$db);
        } else {
            include "components/header.php";
            include "components/navbar.php";
            ?>
                <div class="container-fluid d-flex align-items-center flex-column py-5" style="min-height: 90vh;">
                    <h1 class="mb-4" style="padding-top: 6rem; font-size:auto;">Enter your Email <i class="fas fa-envelope" style=" color: var(--box-color1);"></i></h1>
                    <div class="email-box d-flex align-items-center justify-content-center p-4">
                        <form class="email-option form-inline justify-content-center needs-validation" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" novalidate>
                            <input class="email-in-option rounded-0 form-control" type="email" name="email" placeholder="example@email.com" required>
                            <button type="submit" class="btn rounded-0 s-button ml-3 p-2">Send Email</button>
                            <div class="mt-3 text-center invalid-feedback">
                                Please enter a valid Email.
                            </div>
                        </form>
                    </div>
                    <?php if(isset($_SESSION["email-error"]) && !empty($_SESSION["email-error"])){ ?>
                        <div class="alert alert-danger alert-dismissible fade show text-center rounded-0 m-0 my-3" role="alert" style="pointer-events: auto; text-shadow:none;">
                            <?php echo $_SESSION["email-error"]; ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php unset($_SESSION["email-error"]); } ?>
                    <?php if(isset($_SESSION["email-sent"]) && !empty($_SESSION["email-sent"])){ ?>
                        <div class="alert alert-success alert-dismissible fade show text-center rounded-0 m-0 my-3" role="alert" style="pointer-events: auto; text-shadow:none;">
                            <?php echo $_SESSION["email-sent"]; ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php unset($_SESSION["email-sent"]); } ?>
                    <?php if(isset($_SESSION["psw-error"]) && !empty($_SESSION["psw-error"])){ ?>
                        <div class="alert alert-danger alert-dismissible fade show text-center rounded-0 m-0 my-3" role="alert" style="pointer-events: auto; text-shadow:none;">
                            <?php echo $_SESSION["psw-error"]; ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php unset($_SESSION["psw-error"]); } ?>
                </div>
            <?php
            include "components/footer.php";
            include "components/scripts.php";
        }
    }

    function create_token($email,$db){
        $token = openssl_random_pseudo_bytes(16);
        $token = bin2hex($token);

        $data = $db->use_query("SELECT ID FROM `user` WHERE email = '$email';");
        $id = mysqli_fetch_all($data, MYSQLI_ASSOC);
        $id = $id[0]["ID"];
        $db_token_set = $db->use_query("INSERT INTO `psw_reset` (`ID`, `data_expiration`, `data_request`, `token`, `id_user`) VALUES (NULL, DATE_ADD(NOW(), INTERVAL 10 MINUTE), NOW(), '$token', '$id');");
        $db->close_connection();

        send_mail($email,$token);
        $_SESSION["email-sent"] = "Check your email to reset password";
        if(isset($_SESSION["LOGGED"])){
            header("Location: options.php");
            exit;
        } else {
            header("Location: ".$_SERVER['PHP_SELF']);
            exit;
        }

    }

    function send_mail($email,$token) {
        
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
        $content = '<div style="display: flex; align-items: center; justify-content: center;"><h4>Click the link to reset your password ->  </h4> <a href="http://tapelounge.ddns.net/tapelounge/change-password.php?token='.$token.'">Reset Password</a></div>';
        $mail->Body = $content;
        $mail->Send();
        /* if(!$mail->Send()) {
            echo "Error while sending Email.";
            var_dump($mail);
        } else {
            echo "Email sent successfully";
        } */
    }
?>