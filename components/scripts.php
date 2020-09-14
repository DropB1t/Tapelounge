    </body>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

    <script src="js/tmdb.js"></script>

    <script src="js/main.js"></script>

    <?php if(isset($_SESSION["r-errors"]) && !empty($_SESSION["r-errors"])){ echo ( "<script> $('#register-modal').modal('show') </script>" ); unset($_SESSION["r-errors"]);}?>

    <?php if(isset($_SESSION["l-errors"]) && !empty($_SESSION["l-errors"])){ echo ( "<script> $('#login-modal').modal('show') </script>" ); unset($_SESSION["l-errors"]);}?>

    <?php if(isset($_SESSION["registrated"]) && $_SESSION["registrated"]){ echo ( "<script> $('#login-modal').modal('show') </script>" ); unset($_SESSION["registrated"]);}?>

    <?php if(isset($_SESSION["confirmed"]) && $_SESSION["confirmed"]){ echo ( "<script> $('#login-modal').modal('show') </script>" ); unset($_SESSION["confirmed"]);}?>

</html>