<?php
include 'api.php';
include 'Project.php';

$url = API_URL_PROJECTS;

if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

$url .= $page;
$projects = curlApi($url);
//var_dump(count($projects));
$no_of_records_per_page = 10;

$total_pages = count($projects) / $no_of_records_per_page;

//save in database
foreach ($projects as $p) {
    $project = new Project();
    $project->saveInDatabase($p);
}

$arrayBudgetsUAN = converterToUAN($projects);

$firstPart = $secondPart = $thirdPart = $fourthPart =  [];

foreach ($arrayBudgetsUAN as $amount) {
    if($amount < 500) {
       array_push($firstPart, $amount);
    } elseif ($amount >= 500 && $amount < 1000) {
        array_push($secondPart, $amount);
    } elseif ($amount >= 1000 && $amount < 5000) {
        array_push($thirdPart, $amount);
    } elseif ($amount >= 5000) {
        array_push($fourthPart, $amount);
    }
}

include 'index.html';