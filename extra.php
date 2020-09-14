<?php
    session_start();
    if (isset($_GET["topic"]) && !empty($_GET["topic"])) {
        if($_GET["topic"] != "coming" && $_GET["topic"] != "top" && $_GET["topic"] != "popular"){
            header("Location: index.php");
        }

        if(isset( $_GET['page']) && !empty($_GET["page"])){ $page=$_GET['page']; }else{ $page=1; }

        include "components/header.php";
        include "components/navbar.php";

        require_once 'vendor/autoload.php';
        $token  = new \Tmdb\ApiToken('API_KEY');
        $client = new \Tmdb\Client($token);
        $repository = new \Tmdb\Repository\MovieRepository($client);

        switch ($_GET["topic"]){
            case "coming":
                $title = "Up Coming";
                $collection = $repository->getUpcoming(array('page' => $page));
                break;
            case "top":
                $title = "Top Rated";
                $collection = $repository->getTopRated(array('page' => $page));
                break;
            case "popular":
                $title = "Popular";
                $collection = $repository->getPopular(array('page' => $page));
                break;
        }
        
        $pagination = new Zebra_Pagination();
        $pagination->records($collection->getTotalResults());
        $pagination->records_per_page(20);
        $pagination->always_show_navigation(false);
        $pagination->padding(false);
        $pagination->selectable_pages(6);

        ?>
            <div class="container-fluid py-5" style="min-height: 100vh;">
                <div class="d-flex align-items-center justify-content-center flex-column">
                    <div class="mb-4">
                        <h1> <?php echo $title; ?> </h1>
                    </div>
                    <div class='s-movie-section d-flex align-items-center justify-content-around flex-wrap'>
                        <?php foreach($collection as $item){ ?>
                            <div class="movie-card align-self-center d-flex mb-4">
                                <img class="img-fluid" src="<?php if( null !== $item->getposterPath() && !empty($item->getposterPath())) { echo "https://image.tmdb.org/t/p/w185".$item->getposterPath();}else{ echo "img/default.jpg"; } ?>" alt="Movie Poster">
                                <div class="d-flex p-3 align-items-start justify-content-start flex-column">
                                    <a href="movie.php?id=<?php echo $item->getId(); ?>"><h2 class="m-0"><?php echo $item->getTitle(); ?></h2></a>
                                    <small class="text-muted"><?php echo $item->getReleaseDate()->format('Y-m-d'); ?></small>
                                    <p class="pt-3"><?php echo $item->getOverview(); ?></p>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php $pagination->render(); ?>
        <?php

        include "components/footer.php";
        include "components/scripts.php";
    }else{ header("Location: index.php"); }
?>