<?php

include 'controllers/list-app.php';

$app_route = '/apps/lists';

$router->get($app_route, function() {
    if (isset($_SESSION['user_id'])) {
        views('apps/list-app/main.php', 'index');
    }
    else {
        header('Location: /usm/login');
    }
});

// front end
$router->get($app_route . '/create', function() {
    views('apps/list-app/new-list.php');
});

// api
$router->get($app_route .'/get_all', function() {
    listAppController::index();
});

$router->post($app_route . '/create', function() {
    listAppController::create();
});