<?php
    session_start();
    include "components/header.php";
    include "components/navbar.php";
?>
    <!-- Main Title -->
    <div class="container-fluid mt-75">
        <div class="main-title row">
            <div class="col-2 pr-0">
                <div class="separator-2 mr-4">&nbsp;</div>
            </div>
            <div class="col-9">
                    <h1 class="mb-3">Welcome to <span class="py-1 px-3">TAPE Lounge</span></h1>
                    <h2>Browse millions of movies and discover <br> the best for you</h2>
            </div>
            <div class="col-1">
            </div>
        </div>
        <img src="img/pop-corn.svg" alt="popcorn-illustration" class="il-1">
    </div>
    <div class="d-flex justify-content-center" style="margin-top:150px">
        <form class="form-inline" method="get" action="search.php">
            <div class="input-group">
                <input name="query" class="search-input main-s s-bar form-control rounded-0 border-right-0 dropdown-toggle" data-id="#dd-1" data-toggle="dropdown" data-flip="false" type="search" placeholder="Search . . .">
                <button class="s-button btn rounded-0" type="submit"><i class="fas fa-search"></i> Search</button>
                <div class="dropdown-menu w-100 rounded-0" id="dd-1" role="menu"></div>
            </div>
        </form>
        
    </div>
    <div class="d-flex align-items-center flex-column mb-5" style="margin-top:80px">
        <span>Scroll down to find out more</span>
        <img class="mt-3 animate__animated animate__infinite animate__shakeY" style="--animate-duration: 15s;" src="img/arrow.svg" alt="arrow pointing down">
    </div>


    <!-- Card Section -->
    <div class="container-fluid" style="margin-top:150px">
        <div class="d-flex align-items-center justify-content-around" id="info-sec">
            <img class="info-img mb-5" src="img/undraw_video_files_fu10.svg" alt="video files">
            <div class="info-card mb-5">
                <p class="h-100 m-0">Browse an <span>unlimited</span> film archive and find the best of the film industry</p>
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-around mt-75" id="info-sec">
            <div class="info-card order-2 order-lg-1 mb-5">
                <p class="h-100 m-0">Save your <span>favourite</span> films and keep track of them in your personal <span>list</span></p>
            </div>
            <img class="info-img order-1 order-lg-2 mb-5" src="img/undraw_Choose_bwbs.svg" alt="Choose Favourite">
        </div>
        <div class="d-flex align-items-center justify-content-around mt-75" id="info-sec">
            <img class="info-img mb-5" src="img/undraw_public_discussion_btnw.svg" alt="Choose Favourite">
            <div class="info-card mb-5">
                <p class="h-100 m-0"><span>Express</span> your personal thoughts about films and <span>share</span> them with the others</p>
            </div>
        </div>
    </div>

    <!-- Join Section -->
    <div class="container-fluid mb-5" style="margin-top:150px">
        <h1 id="join-title">JOIN TODAY</h1>
        <div class="d-flex align-items-stretch justify-content-around pt-5 " id="join-box">
            <img class="px-3 mb-4" src="img/undraw_horror_movie_3988.svg" alt="watching horror films" id="join-img">
            <div class="d-flex align-items-center justify-content-center flex-column px-3 mb-4" id="join-card">
                <p class="pb-5 m-0">Register now and try <br> out all our features ! <br> What are you waiting for?</p>
                <button class="s-button btn rounded-0" type="button" data-toggle="modal" data-target="#register-modal">SIGN UP</button>
            </div>
        </div>
    </div>
    
<?php
    include "components/footer.php";
    include "components/scripts.php";
?>