<?php
    session_start();
    if(isset($_SESSION["LOGGED"])){
            include "components/header.php";
            include "components/navbar.php";
            ?>

                <div class="container-fluid d-flex align-items-center flex-column py-5" style="min-height: 100vh;">
                    <h1 class="mb-4 cast-title" style="padding-top: 6rem;">Options <i class="fas fa-cog" style=" color: var(--box-color1);"></i></h1>
                    <div class="options d-flex align-items-start justify-content-start flex-column p-4">
                        <a href="password-reset.php" class="btn rounded-0 s-button mb-5 p-3"> Change Password </a>
                        <form class="email-option form-inline align-self-stretch mb-5 needs-validation" action="components/set-email.php" method="post" novalidate>
                            <input class="email-in-option rounded-0 form-control" type="email" name="email" value="<?php echo $_SESSION["email"]; ?>" placeholder="example@email.com" required>
                            <button type="submit" class="btn rounded-0 s-button ml-3 p-2">Change Email</button>
                            <div class="mt-3 invalid-feedback">
                                Please enter a valid Email.
                            </div>
                        </form>
                        <h4 class="mb-3">Accent Color</h4>
                        <div class="accent-color d-flex align-items-center justify-content-start">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="color mr-2 <?php if($_SESSION["color"] == "#EF233C"){ echo "active-color";}?>" data-color="#EF233C" style="background-color: #EF233C;"></div>
                                <div class="color mr-2 <?php if($_SESSION["color"] == "#157FFB"){ echo "active-color";}?>" data-color="#157FFB" style="background-color: #157FFB;"></div>
                                <div class="color mr-2 <?php if($_SESSION["color"] == "#6F47BE"){ echo "active-color";}?>" data-color="#6F47BE" style="background-color: #6F47BE;"></div>
                                <div class="color mr-2 <?php if($_SESSION["color"] == "#FDC02F"){ echo "active-color";}?>" data-color="#FDC02F" style="background-color: #FDC02F;"></div>
                                <div class="color mr-2 <?php if($_SESSION["color"] == "#2FC898"){ echo "active-color";}?>" data-color="#2FC898" style="background-color: #2FC898;"></div>
                            </div>
                                
                            <form class="form-inline" action="components/set-color.php" method="post">
                                <input id="input-color" type="text" value="" name="color" hidden>
                                <button type="submit" class="btn rounded-0 s-button p-2">Save</button>
                            </form>
                        </div>
                        
                    </div>
                    <?php if(isset($_SESSION["email-error"]) && !empty($_SESSION["email-error"])){ ?>
                        <div class="alert alert-danger alert-dismissible fade show text-center rounded-0 m-0 my-3" role="alert" style="pointer-events: auto; text-shadow:none;">
                            <?php echo $_SESSION["email-error"]; ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php unset($_SESSION["email-error"]); } ?>

                    <?php if(isset($_SESSION["email-success"]) && !empty($_SESSION["email-success"])){ ?>
                        <div class="alert alert-success alert-dismissible fade show text-center rounded-0 m-0 my-3" role="alert" style="pointer-events: auto; text-shadow:none;">
                            <?php echo $_SESSION["email-success"]; ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php unset($_SESSION["email-success"]); } ?>

                    <?php if(isset($_SESSION["email-sent"]) && !empty($_SESSION["email-sent"])){ ?>
                        <div class="alert alert-success alert-dismissible fade show text-center rounded-0 m-0 my-3" role="alert" style="pointer-events: auto; text-shadow:none;">
                            <?php echo $_SESSION["email-sent"]; ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php unset($_SESSION["email-sent"]); } ?>

                    <?php if(isset($_SESSION["psw-success"]) && !empty($_SESSION["psw-success"])){ ?>
                        <div class="alert alert-success alert-dismissible fade show text-center rounded-0 m-0 my-3" role="alert" style="pointer-events: auto; text-shadow:none;">
                            <?php echo $_SESSION["psw-success"]; ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php unset($_SESSION["psw-success"]); } ?>

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
    } else { header("Location: index.php"); }
?>