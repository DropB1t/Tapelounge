<?php
    session_start();
    if(isset($_SESSION["LOGGED"])){
        if (isset($_GET["id"]) && !empty($_GET["id"])) {
            include "components/header.php";
            include "components/navbar.php";

            require_once 'vendor/autoload.php';
            $token  = new \Tmdb\ApiToken('API_KEY');
            $client = new \Tmdb\Client($token);
            try {
                $movie = $client->getMoviesApi()->getMovie($_GET["id"]);
            } catch (\Throwable $th) {
                echo '<div class="container-fluid" style="min-height: 80vh;"><h1 class="text-center pt-5">Movie id is not valid</h1></div>';
                include "components/footer.php";
                include "components/scripts.php";
                exit;
            }

            require "components/exec_db.php";

            $records_per_page = 10;
            $pagination = new Zebra_Pagination();
            $data = $db->use_query('SELECT comments.ID, comments.data, comments.title, comments.comment_text, user.username FROM comments,user WHERE id_movie = '.$_GET["id"].' AND comments.id_user = user.ID ORDER BY data DESC LIMIT '. (($pagination->get_page() - 1) * $records_per_page) . ', ' . $records_per_page . ';');
            $rows = mysqli_fetch_assoc($db->use_query('SELECT COUNT(*) AS rows FROM `comments` WHERE id_movie = '.$_GET["id"].';'));
            $pagination->records($rows['rows']);
            $pagination->records_per_page($records_per_page);
            $pagination->always_show_navigation(false);
            $pagination->padding(false);
            $pagination->selectable_pages(6);

            $db->close_connection();
            ?>
                <div class="container-fluid d-flex align-items-center flex-column py-5" style="min-height: 100vh;">
                    <a class="s-button align-self-start m-4 px-2 py-1" id="back-to-movie" href="movie.php?id=<?php echo $_GET["id"]; ?>"><i class="fas fa-arrow-left"></i> Go back to movie page</a>
                <?php
                    if($data->num_rows > 0){
                        $comments = mysqli_fetch_all($data, MYSQLI_ASSOC);
                ?>
                    <h1 class="mb-5 cast-title" style="padding-top: 5rem;">Comments <i class="fas fa-comment-alt" style=" color: var(--box-color1);"></i></h1>
                    <div class="comment-sec d-flex align-items-start justify-content-start flex-column">
                        <?php foreach($comments as $comment){
                                $dt = new DateTime($comment["data"]);
                        ?>
                            <div class="comment px-4 py-3 mb-4 d-flex align-items-start justify-content-start flex-column">
                                <h2 class="mb-0"><?php echo $comment["title"]; ?></h2>
                                <div class="undertitle mb-3"> <span>Written on <?php echo $dt->format('Y-m-d')." by ".$comment["username"]; ?></span> </div>
                                <p class="mb-2"> <?php echo $comment["comment_text"]; ?> </p>
                            </div>
                        <?php }?>
                    </div>
                <?php } $pagination->render(); ?>
                    <form action="components/send_comment.php" method="post" id="fm-comment" class="comment-form p-5 mt-5 needs-validation d-flex d-flex align-items-stretch justify-content-start flex-column" novalidate>
                        <div class="form-group m-0">
                            <input name="title" class="form-control rounded-0 mb-4" type="text" maxlength="60" placeholder="Title..." required>
                            <div class="invalid-feedback text-center">
                                Please don't leave the title blank!
                            </div>
                        </div>
                        <div class="form-groupm-0">
                            <textarea name="comment" id="comment-box" class="form-control px-2 py-2 mb-4" maxlength="1000" placeholder="Comment..." required></textarea>
                            <div class="invalid-feedback text-center">
                                Please don't leave the comment body blank!
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span id="comment-count">0/1000</span>
                            <button class="s-button btn rounded-0" type="submit" name="submit" value="<?php echo $movie['id']; ?>">Send</button>
                        </div>
                        <?php if(isset($_SESSION["comment-errors"]) && !empty($_SESSION["comment-errors"])){ ?>
                            <div class="alert alert-danger alert-dismissible fade show text-center rounded-0 m-0 mt-2" role="alert" style="pointer-events: auto; text-shadow:none;">
                                <?php echo $_SESSION["comment-errors"]; ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php unset($_SESSION["comment-errors"]); } ?>
                    </form>
                </div>
            <?php
            

            include "components/footer.php";
            include "components/scripts.php";
        }else{ goback(); }
    } else { goback(); }

    function goback(){
        header("Location: ".$_SERVER['HTTP_REFERER']);
        exit;
    }
?>
