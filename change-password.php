<?php
    session_start();
    if(isset($_GET["token"]) && !empty($_GET["token"])){
       $token = trim($_GET["token"]);

       require "components/exec_db.php";
       $id = $db->use_query("SELECT user.ID FROM user,psw_reset WHERE token = '$token' AND user.ID = psw_reset.id_user AND data_expiration > NOW();");
       if ($id->num_rows == 0) {
           $db->close_connection();
           if (isset($_SESSION["LOGGED"])) {
                $_SESSION["psw-error"] = "This token has expired, try to request another email";
                header("Location: options.php");
                exit;
           } else {
                $_SESSION["psw-error"] = "This token has expired, try to request another email";
                header("Location: password-reset.php");
                exit;
           }
       }else{
           if (isset($_POST["password"]) && !empty($_POST["password"])) {
                if (ctype_space($_POST["password"])){
                    $_SESSION["psw-error"] = "Please do not fill in the blank spaces!";
                    header("Location: ".$_SERVER['PHP_SELF']."?token=$token");
                    exit;
                }
                $id = mysqli_fetch_all($id, MYSQLI_ASSOC);
                $id = $id[0]["ID"];
                $password = filter_var(trim($_POST["password"]), FILTER_SANITIZE_STRING );
                $password = md5($password."videotape");
                $execute = $db->use_query("UPDATE `user` SET `password` = '$password' WHERE `user`.`ID` = $id;");
                $log = $db->use_query("INSERT INTO `logs` (`ID`, `data`, `action`, `id_user`) VALUES (NULL, NOW(), 'Changed password', '$id');");
                $db->close_connection();
                if (isset($_SESSION["LOGGED"])) {
                    $_SESSION["psw-success"] = "Your password was successfully reseted!";
                    header("Location: options.php");
                    exit;
                } else {
                    $_SESSION["psw-success"] = "Your password was successfully reseted!";
                    header("Location: ".$_SERVER['PHP_SELF']."?token=$token");
                    exit;
                }
                
           } else {
                $db->close_connection();
                include "components/header.php";
                include "components/navbar.php";
                ?>

                    <div class="container-fluid d-flex align-items-center flex-column py-5" style="min-height: 90vh;">
                        <h1 class="mb-4" style="padding-top: 6rem; font-size:auto;">Enter your new password <i class="fas fa-lock" style=" color: var(--box-color1);"></i></h1>
                        <div class="email-box d-flex align-items-center justify-content-center p-4">
                            <form class="email-option form-inline justify-content-center needs-validation" action="<?php echo $_SERVER['PHP_SELF']."?token=$token"; ?>" method="post" novalidate>
                                <input class="email-in-option rounded-0 form-control" type="password" name="password" placeholder="Enter a password" required>
                                <button type="submit" class="btn rounded-0 s-button ml-3 p-2">Reset Password</button>
                                <div class="mt-3 text-center invalid-feedback">
                                    Please enter a valid password
                                </div>
                            </form>
                        </div>
                        <?php if(isset($_SESSION["psw-error"]) && !empty($_SESSION["psw-error"])){ ?>
                            <div class="alert alert-danger alert-dismissible fade show text-center rounded-0 m-0 my-3" role="alert" style="pointer-events: auto; text-shadow:none;">
                                <?php echo $_SESSION["psw-error"]; ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php unset($_SESSION["psw-error"]); } ?>

                        <?php if(isset($_SESSION["psw-success"]) && !empty($_SESSION["psw-success"])){ ?>
                            <div class="alert alert-success alert-dismissible fade show text-center rounded-0 m-0 my-3" role="alert" style="pointer-events: auto; text-shadow:none;">
                                <?php echo $_SESSION["psw-success"]; ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php unset($_SESSION["psw-success"]); } ?>
                    </div>

                <?php
                include "components/footer.php";
                include "components/scripts.php";
            }
       }
    } else {
        header("Location: guest.php");
        exit;
    }
?>