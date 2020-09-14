<?php
    session_start();
    if(isset($_SESSION["LOGGED"])){
        if (isset($_POST["submit"]) && !empty($_POST["submit"])) {
            if ( ctype_space($_POST["title"]) || ctype_space($_POST["comment"])){
                $_SESSION["comment-errors"] = "Please do not fill in the blank spaces!";
                goback();
            }

            require_once '../vendor/autoload.php';
            $token  = new \Tmdb\ApiToken('API_KEY');
            $client = new \Tmdb\Client($token);
            /* $movie = $client->getMoviesApi()->getMovie($_POST["submit"]);
            if (isset($movie["status_code"])) { goback(); } */
            try {
                $movie = $client->getMoviesApi()->getMovie($_POST["submit"]);
            } catch (\Throwable $th) {
                goback();
            }

            $title = filter_var(trim($_POST["title"]), FILTER_SANITIZE_STRING );
            $title = str_replace("'","\'",$title);
            $title = str_replace('"','\"',$title);
            $comment = filter_var(trim($_POST["comment"]), FILTER_SANITIZE_STRING );
            $comment = str_replace("'","\'",$comment);
            $comment = str_replace('"','\"',$comment);
            $id = $_POST["submit"];
            $id_user = $_SESSION["id"];

            require "exec_db.php";
            $add = $db->use_query("INSERT INTO `comments` (`ID`, `data`, `title`, `comment_text`, `id_movie`, `id_user`) VALUES (NULL, NOW(), '$title', '$comment', '$id', '$id_user');");
            $log = $db->use_query("INSERT INTO `logs` (`ID`, `data`, `action`, `id_user`) VALUES (NULL, NOW(), 'Comment written for $id movie', '$id_user');");
            $db->close_connection();
            goback();
        }else{ goback(); }
    } else { goback(); }

    function goback(){
        header("Location: ".$_SERVER['HTTP_REFERER']."#fm-comment");
        exit;
    }
?>