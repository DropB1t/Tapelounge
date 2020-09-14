<nav class="navbar navbar-expand-custom py-4 px-4">
  <a class="navbar-brand" href="index.php" id="a-logo">
    <img src="img/Logo.png" width="250px" height="37px" alt="logo">
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
    <i class="menu-button fas fa-bars"></i>
  </button>

  <div class="collapse navbar-collapse" id="navbar" style=" text-align: center;">
    <?php
        if(basename($_SERVER["SCRIPT_FILENAME"])!="guest.php" && basename($_SERVER["SCRIPT_FILENAME"])!="search.php"){
    ?>
    <form class="form-inline ml-lg-auto my-2 my-xl-0" method="get" action="search.php">
      <div class="input-group">
        <input name="query" class="search-input s-bar form-control rounded-0 border-right-0" data-id="#dd-3" data-toggle="dropdown" data-flip="false" type="search" placeholder="Search . . .">
        <button class="s-button btn rounded-0" type="submit" ><i class="fas fa-search"></i> Search</button>
        <div class="dropdown-menu w-100 rounded-0" id="dd-3"></div>
      </div>
    </form>
    <?php
        }
    ?>
    <div class="navbar-nav ml-lg-auto">
      <a class="nav-item align-self-start nav-link" href="extra.php?topic=coming">Up Coming</a>
      <a class="nav-item align-self-start nav-link" href="extra.php?topic=top">Top Rated</a>
      <a class="nav-item align-self-start nav-link" href="extra.php?topic=popular">Popular</a>
    </div>
    <?php
        if( isset($_SESSION["LOGGED"])){ ?>
            <div class="dropdown">
                <a class="rounded-circle dropdown-toggle" href="#" role="button" id="profileMenu" data-toggle="dropdown" style="border: 2px solid <?php echo $_SESSION['color']; ?>;">
                    <i class="far fa-user p-3" style="color: <?php echo $_SESSION['color']; ?>;"></i>
                </a>

                <div class="dropdown-menu dropdown-menu-center mt-1" aria-labelledby="profileMenu">
                    <a class="dropdown-item" href="profile.php?option=favourite">Profile Page</a>
                    <a class="dropdown-item" href="options.php">Options</a>
                    <a class="dropdown-item" href="components/logout.php" style="color: #EF233C;">Log Out</a>
                </div>
            </div>
            
        <?php } else { ?>
                <div class="d-flex align-items-center justify-content-center ml-xl-4 my-2 my-xl-0">
                    <a href="" type="button" data-toggle="modal" data-target="#login-modal">SIGN IN</a>
                    <div class="mx-3 separator"></div>
                    <button class="s-button btn rounded-0 my-2 my-sm-0" type="button" data-toggle="modal" data-target="#register-modal">SIGN UP</button>
                </div>
        <?php } ?>
    
  </div>
</nav>

<!-- Login Model -->
<div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="login-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content p-5 rounded-0">
            <h1 class="mb-5 mx-2 px-3 py-2">SIGN IN</h1>
            <form class="text-center needs-validation" action="components/l-action.php" method="post" novalidate>
                <div class="form-group">
                    <label for="l-email m-0">Email</label>
                    <input type="email" name="email" class="form-control s-bar" id="l-email" required>
                    <div class="invalid-feedback">
                        Please enter a valid Email.
                    </div>
                </div>
                <div class="form-group mb-5">
                    <label for="l-psw m-0">Password</label>
                    <input type="password" name="password" class="form-control s-bar" id="l-psw" required>
                    <div class="invalid-feedback">
                        Please enter a valid Password.
                    </div>
                </div>
                <button type="submit" class="s-button py-2 w-100 btn rounded-0">Login</button>
            </form>
            <div class="d-flex align-items-center justify-content-between flex-column pt-4">
                <span class="mb-2">Forgot password ? <a href="password-reset.php" type="button">Reset</a></span>
                <span>Don't have an account ? <a href="" type="button" data-toggle="modal" data-dismiss="modal" data-target="#register-modal">Sign up</a></span>
            </div>
        </div>
        <?php
            if(isset($_SESSION["l-errors"])){
                foreach ($_SESSION["l-errors"] as $error) {
        ?>
            <div class="alert alert-danger alert-dismissible fade show text-center rounded-0 m-0 mt-2" role="alert" style="pointer-events: auto;">
                <?php echo $error; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php   
                }
            }
        ?>
        <?php if(isset($_SESSION["registrated"])){ ?>
            <div class="alert alert-success alert-dismissible fade show text-center rounded-0 m-0 mt-2" role="alert" style="pointer-events: auto;">
                Please, check your email box and confirm your registration!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php } ?>
        <?php if(isset($_SESSION["confirmed"])){ ?>
            <div class="alert alert-success alert-dismissible fade show text-center rounded-0 m-0 mt-2" role="alert" style="pointer-events: auto;">
                Your Email was confirmed, try to log in.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php } ?>
    </div>
</div>

<!-- Register Model -->
<div class="modal fade" id="register-modal" tabindex="-1" role="dialog" aria-labelledby="register-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content p-5 rounded-0">
            <h1 class="mb-5 mx-2 px-3 py-2">SIGN UP</h1>
            <form class="text-center needs-validation" action="components/r-action.php" method="post" novalidate>
                <div class="form-group">
                    <label for="r-username m-0">Username</label>
                    <input type="text" name="username" class="form-control s-bar" id="r-username" required>
                    <div class="invalid-feedback">
                        Please enter a valid Username.
                    </div>
                </div>
                <div class="form-group">
                    <label for="r-email m-0">Email</label>
                    <input type="email" name="email" class="form-control s-bar" id="r-email" required>
                    <div class="invalid-feedback">
                        Please enter a valid Email.
                    </div>
                </div>
                <div class="form-group mb-5">
                    <label for="r-psw m-0">Password</label>
                    <input type="password" name="password" class="form-control s-bar" id="r-psw" required>
                    <div class="invalid-feedback">
                        Please enter a valid Password.
                    </div>
                </div>
                <button type="submit" name="submit" class="s-button py-2 w-100 btn rounded-0">Create Account</button>
            </form>  
        </div>
        <?php
            if(isset($_SESSION["r-errors"])){
                foreach ($_SESSION["r-errors"] as $error) {
        ?>
            <div class="alert alert-danger alert-dismissible fade show text-center rounded-0 m-0 mt-2" role="alert" style="pointer-events: auto;">
                <?php echo $error; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php   
                }
            }
        ?>
    </div>
</div>