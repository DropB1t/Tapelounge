<?php
    session_start();
    require_once 'vendor/autoload.php';
    include "components/header.php";
    include "components/navbar.php";

    if(isset($_GET["id"])){
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
        
        if (!isset($movie["status_code"])) {
        
            $credits = $client->getMoviesApi()->getCredits($_GET["id"]);
            $cast = [];
            $crew = [];
            if (isset($credits)) {
                $cast = $credits["cast"];
                $crew = $credits["crew"];
            }

            $videos = $client->getMoviesApi()->getVideos($_GET["id"]);
            $video_path = "";
            foreach ($videos["results"] as $video) {
                if($video["type"] == "Trailer" && $video["site"] == "YouTube"){
                    $video_path = $video["key"];
                    break;
                }
            }

            if(isset($movie["poster_path"])){
                $poster = "https://image.tmdb.org/t/p/w300".$movie["poster_path"];
            }else{
                $poster = "img/default-w300.jpg";
            }

            if(isset($movie["title"])){ $title = $movie["title"]; } else { $title = ""; }

            if(isset($movie["release_date"])){ $r_date = $movie["release_date"]; } else { $r_date = ""; }

            if(isset($movie["overview"])){
                $overview = '<div class="overview pt-4">
                                <h4 class="mb-1">Overview</h4>
                                <p>'.$movie["overview"].'</p>
                            </div>' ;
            } else { $overview = ""; }

            if(isset($movie["runtime"])){ $runtime = $movie["runtime"]; } else { $runtime = ""; }

            $genres = [];
            if (isset($movie["genres"])) {
                foreach ($movie["genres"] as $var) {
                    $genres[] = $var["name"];
                }
                $g_list = implode(', ', $genres);
            } else { $g_list = ""; }
            

            if(isset($movie["vote_average"])){
                $v_avg = strval($movie["vote_average"]);
                $v_avg = str_replace(".", "", $v_avg);
                $vote_box = '<h1 class="m-0 p-3" id="vote">'.$v_avg.'</h1>';
            } else {
                $vote_box = "";
            }

            if(isset($movie["status"])){
                $status = '<div class="d-flex align-items-center mb-3">
                                <h4 class="m-0 mr-3">Status</h4>
                                <span id="status">'.$movie["status"].'</span>
                            </div>';
            } else { $status = ""; }

            $screenplay = "";
            $story = "";
            $director = "";
            if(!empty($crew)){
                foreach ($crew as $person) {
                    if($person["job"]=="Screenplay"){
                        $screenplay = '<div class="d-flex align-items-center mb-3">
                                            <h4 class="m-0 mr-3">Screenplay</h4>
                                            <span>'.$person["name"].'</span>
                                        </div>';
                    }
                    if($person["job"]=="Story"){
                        $story = '<div class="d-flex align-items-center mb-3">
                                    <h4 class="m-0 mr-3">Story</h4>
                                    <span>'.$person["name"].'</span>
                                </div>';
                    }
                    if($person["job"]=="Director"){
                        $director = '<div class="d-flex align-items-center mb-3">
                                        <h4 class="m-0 mr-3">Director</h4>
                                        <span>'.$person["name"].'</span>
                                    </div>';
                    }
                }
            }

            if(isset($movie["revenue"]) && $movie["revenue"] != 0){
                $revenue = number_format($movie["revenue"] , 0, ',', '.');
                $revenue_box = '<div class="d-flex align-items-center mb-3">
                                    <h4 class="m-0 mr-3">Revenue</h4>
                                    <span>$'.$revenue.'</span>
                                </div>';
            } else { $revenue_box = ""; }
            
            if(isset($movie["budget"]) && $movie["budget"] != 0){
                $budget = number_format($movie["budget"] , 0, ',', '.');
                $budget_box = '<div class="d-flex align-items-center mb-3">
                                    <h4 class="m-0 mr-3">Budget</h4>
                                    <span>$'.$budget.'</span>
                                </div>';
            } else { $budget_box = ""; }

            if (!empty($cast)) {
                if (count($cast) > 12) {
                    $cast = array_slice($cast, 0, 12);
                }
            }else { $cast = []; }
            require "components/exec_db.php";
        }
?>
    <div class="container-fluid d-flex align-items-center flex-column py-5" style="min-height: 100vh;">
        <div class="m-page d-flex align-items-start justify-content-start">
            <img class="ml-4 img-fluid" id="poster" src="<?php echo $poster; ?>" alt="Movie Poster">
            <div class="ml-4 p-3 d-flex align-text-center justify-content-start flex-column">
                <h2 class="mb-1"><?php echo $title; ?></h2>
                <div class="undertitle">
                    <span><?php echo $r_date; ?></span>
                    <span><?php echo $g_list; ?></span>
                    <span style="white-space: nowrap;"><i class="fas fa-stopwatch" style=" color: var(--box-color1);"></i> <?php echo $runtime; ?></span>
                </div>
                <?php echo $overview; ?>
                <div class="pt-3 m-info d-flex align-items-start justify-content-start">
                    <div class="info-col mr-4">
                        <?php echo $status; ?>
                        <?php echo $screenplay; ?>
                        <?php echo $story; ?>
                    </div>
                    <div class="info-col">
                        <?php echo $director; ?>
                        <?php echo $budget_box; ?>
                        <?php echo $revenue_box; ?>
                    </div>
                </div>
            </div>
            <div id="m-vote" class="ml-auto p-4 d-flex align-self-stretch align-items-end justify-content-between flex-column">
                <?php if(isset($_SESSION["LOGGED"])){
                        $data = $db->use_query("SELECT id_user FROM `favourite` WHERE ID_movie = ".$movie['id']." and id_user = ".$_SESSION['id'].";");
                        if($data->num_rows > 0){
                ?>
                            <a id="unheart" href="components/rmv_movie.php?id=<?php echo $movie["id"]; ?>"><i class="fas fa-heart"></i></a>
                <?php 
                        } else {?>
                            <a id="heart" href="components/add_movie.php?id=<?php echo $movie["id"]; ?>"><i class="far fa-heart"></i></a>
                        <?php }
                    } else {
                ?>
                    <a id="heart" href="" data-toggle="modal" data-target="#register-modal"><i class="far fa-heart"></i></a>
                <?php } ?>
                <!-- <a href="" id="heart"><i class="far fa-heart"></i></a> -->
                <?php echo $vote_box; ?>
            </div>
        </div>
        <?php if(count($cast) != 0){?>
            <h1 class="mb-1 cast-title" style="padding-top: 8rem;">Cast</h1>
        <?php }?>
        
        <div class="cast-scroll">
            <?php foreach ($cast as $person) {?>
                <div class="cast-card mr-3 d-flex align-items-start justify-content-start flex-column">
                    <img class="img-fluid w-100" src="<?php if(isset($person["profile_path"])){ echo "https://image.tmdb.org/t/p/w185".$person["profile_path"];}else{ echo "img/default.jpg"; }?>" alt="Cast Image">
                    <div class="p-3">
                        <h4 class="mb-0"><?php if(isset($person["name"])){ echo $person["name"];}?></h4>
                        <h6 class="mb-0"><?php if(isset($person["character"])){ echo $person["character"];}?></h6>
                    </div>
                </div>
            <?php }?>
        </div>
        
        <?php if(!empty($video_path)){?>
            <h1 class="mb-2 cast-title" style="padding-top: 8rem;">Video <i class="fas fa-video" style=" color: var(--box-color1);"></i></h1>
            <div class="py-3 px-4" id="v-box">
                <iframe src="https://www.youtube-nocookie.com/embed/<?php echo $video_path; ?>" frameborder="0" allow="encrypted-media;" allowfullscreen></iframe>
            </div>
        <?php }?>
        
        <?php
            $data = $db->use_query("SELECT comments.ID, comments.data, comments.title, comments.comment_text, user.username FROM comments,user WHERE user.ID = comments.id_user AND id_movie = ".$movie['id']." ORDER BY data DESC LIMIT 2");
            if($data->num_rows > 0){
                $comments = mysqli_fetch_all($data, MYSQLI_ASSOC);
        ?>
        <h1 class="mb-2 cast-title" style="padding-top: 8rem;">Comments <i class="fas fa-comment-alt" style=" color: var(--box-color1);"></i></h1>
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
            <?php 
                $data = $db->use_query("SELECT * FROM comments WHERE id_movie = ".$movie['id'].";");
                if($data->num_rows > 2){
                    if(isset($_SESSION["LOGGED"])){
            ?>
                <a class="s-button align-self-center px-2 py-1" href="comments.php?id=<?php echo $movie["id"]; ?>">See more...</a>
                    <?php } else {?>
                <a class="s-button align-self-center px-2 py-1" href="" data-toggle="modal" data-target="#register-modal">See more...</a>
            <?php } 
                }
            ?>
        <?php } ?>
        
        <?php $db->close_connection(); ?>

            <form action="components/send_comment.php" method="post" id="fm-comment" class="comment-form p-5 needs-validation d-flex d-flex align-items-stretch justify-content-start flex-column" style="margin-top: 8rem;" novalidate>
                
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
                    <?php if(isset($_SESSION["LOGGED"])){?>
                        <button class="s-button btn rounded-0" type="submit" name="submit" value="<?php echo $movie['id']; ?>">Send</button>
                    <?php } else {?>
                        <button class="s-button btn rounded-0" type="button" data-toggle="modal" data-target="#register-modal">Send</button>
                    <?php } ?>
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
        
        
    </div>
<?php
    } else{
        header("Location: index.php");
    }
    include "components/footer.php";
    include "components/scripts.php";
?>
