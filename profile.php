<?php
    session_start();
    if(isset($_SESSION["LOGGED"])){
            include "components/header.php";
            include "components/navbar.php";

            if (isset($_GET["option"])) {
                if(!empty($_GET["option"]) || $_GET["option"] == "favourite" || $_GET["option"] == "comments" ){
                    $option = $_GET["option"];
                } else { $option = "favourite"; }
            } else { $option = "favourite"; }
            
            require "components/exec_db.php";

            $data = $db->use_query("SELECT COUNT(*) AS 'count' FROM `favourite` WHERE id_user = ".$_SESSION['id'].";");
            $number = mysqli_fetch_assoc($data);
            
            ?>

                <div class="container-fluid d-flex align-items-center flex-column py-5" style="min-height: 100vh;">
                        <div class="profile-card d-flex align-items-center justify-content-start">
                            <div class="rounded-circle profile-avatar" style="border: 3px solid <?php echo $_SESSION['color']; ?>;">
                                <i class="far fa-user p-5" style="color: <?php echo $_SESSION['color']; ?>;"></i>
                            </div>
                            <div class="d-flex profile-content align-self-stretch align-items-start justify-content-start flex-column p-4">
                                <h1 class="m-0"><?php echo $_SESSION['username']; ?></h1>
                                <small>Registered since <?php echo $_SESSION['registered']; ?></small>
                                <h4 class="mt-auto m-0">Favourite Movies <span class="px-2 py-1"><?php echo $number['count']; ?></span></h4>
                            </div>
                        </div>
                        
                        <div class="profile-sec d-flex align-items-start justify-content-start flex-column">
                            <ul class="nav">
                                <li>
                                    <a class="nav-link rounded-0 <?php if($_GET["option"] == "favourite") { echo "active"; } ?>" href="<?php echo $_SERVER['PHP_SELF'];?>?option=favourite">Favourite</a>
                                </li>
                                <li>
                                    <a class="nav-link rounded-0 <?php if($_GET["option"] == "comments") { echo "active"; } ?>" href="<?php echo $_SERVER['PHP_SELF'];?>?option=comments">Comments</a>
                                </li>
                            </ul>
                            <div class="profile-page d-flex align-items-center justify-content-start flex-column pt-4">
                                <?php if($_GET["option"] == "favourite") {
                                        if($number["count"] > 0) {
                                            require_once 'vendor/autoload.php';
                                            $records_per_page = 5;
                                            $pagination = new Zebra_Pagination();
                                            $data = $db->use_query('SELECT ID_movie FROM `favourite` WHERE id_user = '.$_SESSION["id"].' ORDER BY data DESC LIMIT '. (($pagination->get_page() - 1) * $records_per_page) . ', ' . $records_per_page . ';');
                                            $ids = mysqli_fetch_all($data);
                                            $pagination->records($number["count"]);
                                            $pagination->records_per_page($records_per_page);
                                            $pagination->always_show_navigation(false);
                                            $pagination->padding(false);
                                            $pagination->selectable_pages(6);
                                            /* foreach ($ids as $id) { echo $id[0]; } */
                                            $db->close_connection();
                                            
                                            
                                            $token  = new \Tmdb\ApiToken('API_KEY');
                                            $client = new \Tmdb\Client($token);

                                            

                                            foreach ($ids as $id) {
                                                $movie = $client->getMoviesApi()->getMovie($id[0]);
                                ?>
                                            <div class="movie-card align-self-center d-flex mb-4" >
                                                <img class="img-fluid" src=" <?php if(isset($movie["poster_path"])){ echo "https://image.tmdb.org/t/p/w185".$movie["poster_path"]; }else{ echo "img/default.jpg"; } ?>" alt="Movie Poster">
                                                <div class="d-flex p-3 align-items-start justify-content-start flex-column">
                                                    <a href="movie.php?id=<?php if(isset($movie["id"])){ echo $movie["id"];}?>"><h2 class="m-0"><?php if(isset($movie["title"])){ echo $movie["title"];}?></h2></a>
                                                    <small class="text-muted"><?php if(isset($movie["release_date"])){ echo $movie["release_date"];}?></small>
                                                    <p class="pt-3"><?php if(isset($movie["overview"])){ echo $movie["overview"];}?></p>
                                                </div>
                                            </div>
                                <?php } $pagination->render(); } } ?>
                                <?php if($_GET["option"] == "comments") {
                                        $rows = mysqli_fetch_assoc($db->use_query('SELECT COUNT(*) AS "num" FROM `comments` WHERE id_user = '.$_SESSION["id"].';'));
                                        /* echo $rows["num"]; */
                                        if($rows["num"] > 0) {
                                            require_once 'vendor/autoload.php';
                                            $records_per_page = 5;
                                            $pagination = new Zebra_Pagination();
                                            $data = $db->use_query('SELECT comments.ID, comments.id_movie, comments.data, comments.title, comments.comment_text, user.username FROM comments,user WHERE id_user = '.$_SESSION["id"].' AND comments.id_user = user.ID ORDER BY data DESC LIMIT '. (($pagination->get_page() - 1) * $records_per_page) . ', ' . $records_per_page . ';');
                                            $comments = mysqli_fetch_all($data, MYSQLI_ASSOC);
                                            $pagination->records($rows["num"]);
                                            $pagination->records_per_page($records_per_page);
                                            $pagination->always_show_navigation(false);
                                            $pagination->padding(false);
                                            $pagination->selectable_pages(6);
                                            $db->close_connection();
                                            
                                            $token  = new \Tmdb\ApiToken('API_KEY');
                                            $client = new \Tmdb\Client($token);

                                            foreach ($comments as $comment) {
                                                $dt = new DateTime($comment["data"]);
                                                $movie = $client->getMoviesApi()->getMovie($comment["id_movie"]);
                                ?>
                                                <div class="comment px-4 py-3 mb-4 d-flex align-items-start justify-content-start flex-column">
                                                    <h2 class="mb-0"><?php echo $comment["title"]; ?></h2>
                                                    <div class="undertitle mb-3"> <span>Written on <?php echo $dt->format('Y-m-d')." by ".$comment["username"]; ?></span> </div>
                                                    <p class="mb-2"> <?php echo $comment["comment_text"]; ?> </p>
                                                    <div class="profile-btn d-flex align-self-stretch align-items-center justify-content-between">
                                                        <a class="movie px-2 py-1" href="movie.php?id=<?php echo $movie["id"]; ?>"><i class="fas fa-film"></i> <?php echo $movie["title"]; ?></a>
                                                        <a class="trash" href="components/delete_comment.php?id=<?php echo $comment["ID"]; ?>"><i class="fas fa-trash-alt"></i></a>
                                                    </div>
                                                </div>
                                <?php } $pagination->render(); } } ?>
                            </div>
                        </div>
                        

                </div>

            <?php
            include "components/footer.php";
            include "components/scripts.php";
    } else { header("Location: index.php"); }
?>
