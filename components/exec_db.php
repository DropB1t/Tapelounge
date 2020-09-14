<?php

    include "variables.php";

    include "DBclass.php";

    $db = new DB($servername, $user_db, $password_db, $db);

    $db->create_connection();

?>
