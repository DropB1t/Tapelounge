<?php

require_once 'vendor/autoload.php';

$token  = new \Tmdb\ApiToken("API_KEY");
$client = new \Tmdb\Client($token);

$configRepository = new \Tmdb\Repository\ConfigurationRepository($client);
$config = $configRepository->load();

$imageHelper = new \Tmdb\Helper\ImageHelper($config);

$repository = new \Tmdb\Repository\MovieRepository($client);
$collection = $repository->getTopRated(array('page' => 1));

/* echo $collection->getTotalPages();
echo $collection->getTotalResults(); */
/* var_dump($collection); */



foreach ($collection as $item) {
    echo $item->getposterPath();
    echo $item->getId();
    echo $item->getTitle();
    echo $item->getOverview();
    echo $item->getReleaseDate()->format('Y-m-d');
}

/* foreach ($collection as $item) {
    $dates = $item->getReleaseDate();
    foreach ($dates as $date) {
        echo $date;
    }
} */

/* echo $collection->getResults(); */