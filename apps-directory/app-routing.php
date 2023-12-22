<?php

$router->get('/apps', function() {
    views('apps/index.php');
});

/*
/ FOR HANDLING PODCRACK CALLS
*/
include 'podcrack-app/podcrack-routing.php';
include 'list-app/list-app-routing.php';