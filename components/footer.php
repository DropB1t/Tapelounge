<a id="back-to-top" href="#" class="btn back-to-top" role="button"><i class="fas fa-chevron-up"></i></a>
<footer class="footer py-4 px-4">
    <div class="d-flex align-items-center justify-content-between" id="footer-flex">
        <img class="my-3" src="img/LogoAttribution.svg" alt="The movie database attribution" id="footer-attribution">
        <div class="d-flex justify-content-center align-items-center align-self-end flex-column my-3">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a class="nav-item nav-link" href="extra.php?topic=coming">Up Coming</a>
                <a class="nav-item nav-link" href="extra.php?topic=top">Top Rated</a>
                <a class="nav-item nav-link" href="extra.php?topic=popular">Popular</a>
            </div>
            <form class="form-inline my-2 my-xl-0" method="get" action="search.php">
                <div class="input-group dropup">
                    <input name="query" class="search-input s-bar form-control rounded-0 border-right-0 dropdown-toggle" data-id="#dd-2" data-toggle="dropdown" type="search" placeholder="Search . . .">
                    <button class="s-button btn rounded-0" type="submit"><i class="fas fa-search"></i> Search</button>
                    <div class="dropdown-menu w-100 rounded-0" id="dd-2" style="overflow: hidden;"></div>
                </div>
            </form>
        </div>
        <img class="align-self-end my-3" src="img/Logo.png" alt="Logo" style="height: 40px;" id="footer-logo">
    </div>
</footer>
