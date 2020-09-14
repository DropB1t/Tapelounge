<?php
    session_start();
    if(isset($_SESSION["LOGGED"])){
        if (isset($_GET["id"])) {

            require_once '../vendor/autoload.php';
            $token  = new \Tmdb\ApiToken('API_KEY');
            $client = new \Tmdb\Client($token);
            $movie = $client->getMoviesApi()->getMovie($_GET["id"]);
            if (isset($movie["status_code"])) { goback(); }

            require "exec_db.php";
            $data = $db->use_query("SELECT id_user FROM `favourite` WHERE ID_movie = ".$_GET['id']." and id_user = ".$_SESSION['id'].";");
            if($data->num_rows == 0){
                $add = $db->use_query("INSERT INTO `favourite` (`ID_movie`, `data`, `id_user`) VALUES ('".$_GET['id']."', NOW(), '".$_SESSION['id']."');");
                $log = $db->use_query("INSERT INTO `logs` (`ID`, `data`, `action`, `id_user`) VALUES (NULL, NOW(), 'Added ".$_GET['id']." into favourite', '".$_SESSION['id']."');");
                $db->close_connection();
                goback();
            } else {
                $db->close_connection();
                goback();
            }
            
        }else{ goback(); }
    } else { goback(); }

    function goback(){
        header("Location: ".$_SERVER['HTTP_REFERER']);
        exit;
    }
?>