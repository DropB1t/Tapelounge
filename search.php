<?php
    session_start();
    require_once 'vendor/autoload.php';
    include "components/header.php";
    include "components/navbar.php";
    $res = false;
    if(isset($_GET["query"]) && trim($_GET['query']) != ""){
        $token  = new \Tmdb\ApiToken('API_KEY');
        $client = new \Tmdb\Client($token);
        if(isset( $_GET['page']) ){ $page=$_GET['page']; }else{ $page=1; }
        $result = $client->getSearchApi()->searchMovies($_GET['query'],["page"=>$page]);
        if($result["total_results"] > 0){
            $res = true;
        }
        $movies = $result["results"];

        $pagination = new Zebra_Pagination();
        $pagination->records($result["total_results"]);
        $pagination->records_per_page(20);
        $pagination->always_show_navigation(false);
        $pagination->padding(false);
        $pagination->selectable_pages(6);

    } else{ $res = false; }
?>
    <div class="container-fluid py-5" style="min-height: 100vh;">
        <div class="d-flex align-items-center justify-content-center flex-column">
            <form class="form-inline mb-5" method="get" action="search.php">
                <div class="input-group">
                    <input name="query" value="<?php if($res){echo($_GET["query"]);} ?>" class="search-input main-s s-bar form-control rounded-0 border-right-0 dropdown-toggle" data-id="#dd-1" data-toggle="dropdown" data-flip="false" type="search" placeholder="Search . . .">
                    <button class="s-button btn rounded-0" type="submit"><i class="fas fa-search"></i> Search</button>
                    <div class="dropdown-menu w-100 rounded-0" id="dd-1"></div>
                </div>
            </form>
            <div class="mb-4">
                <h1>Search Results 
                    <span class="badge badge-main rounded-0"> <?php if($res){ echo $result["total_results"]; }else{echo "0";} ?> </span>
                </h1>
            </div>
            <?php if($res){
                echo "<div class='s-movie-section d-flex align-items-center justify-content-around flex-wrap'>";
                foreach ($movies as $movie) {
                    ?>
                        <div class="movie-card align-self-center d-flex mb-4" <?php if($result["total_results"]==1){ echo 'style=" flex: auto !important; "'; }?> >
                            <img class="img-fluid" src=" <?php if(isset($movie["poster_path"])){ echo "https://image.tmdb.org/t/p/w185".$movie["poster_path"]; }else{ echo "img/default.jpg"; } ?>" alt="Movie Poster">
                            <div class="d-flex p-3 align-items-start justify-content-start flex-column">
                                <a href="movie.php?id=<?php if(isset($movie["id"])){ echo $movie["id"];}?>"><h2 class="m-0"><?php if(isset($movie["title"])){ echo $movie["title"];}?></h2></a>
                                <small class="text-muted"><?php if(isset($movie["release_date"])){ echo $movie["release_date"];}?></small>
                                <p class="pt-3"><?php if(isset($movie["overview"])){ echo $movie["overview"];}?></p>
                            </div>
                        </div>
                    <?php
                }
                echo "</div>";
            }else 
            { echo("<h2>There are no movies that matched your query</h2>"); } ?>
        </div>
        
    </div>
    <?php if($res){$pagination->render();} ?>
<?php
    include "components/footer.php";
    include "components/scripts.php";
?>