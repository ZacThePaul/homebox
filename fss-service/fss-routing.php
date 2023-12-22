<?php

/*
/ FOR HANDLING FSS CALLS
*/

// frontend calls
$router->get('/fss', function() {
    // function to get some information
    views('fss/main.php');
});

$router->get('/fss/about', function() {
    // include 'test2.php';
    echo '/about';
});

$router->get('/fss/bucket/create', function() {
    Bucket::create();
});

$router->get('/fss/bucket', function() {
    // die('test');

    $bucket_slug = $_GET['slug'];

    $json = file_get_contents('http://192.168.1.23/fss-service/buckets.json');
    $data = json_decode($json);

    // die(var_dump($data));

    if (count($data) > 0) {
        // die('counted');

        foreach ( $data as $item ) {
            // die($item->bucket_slug . ' ---- ' . $bucket_slug);

            if ($item->bucket_slug == $bucket_slug) {
                include 'views/bucket.php';
            }
        }
    }

});

// api calls
$router->get('/fss/buckets', function() {
    Bucket::index();
});

$router->get('/fss/objects', function() {

    $bucket_slug = $_GET['slug'];

    $json = file_get_contents('/var/www/html/fss-service/buckets.json');
    
    $data = json_decode($json);

    // die('here');

    foreach ( $data as $item ) {

        if ($item->bucket_slug == $bucket_slug) {

            echo json_encode($item->bucket_objects);
        }
    }

});

$router->post('/fss/bucket/create', function() {
    Bucket::create();
});

$router->get('/fss/bucket/delete', function() {
    Bucket::delete();
});

$router->post('/fss/object/upload', function() {
    Bucket::object_create();
});

$router->get('/fss/object/delete', function() {
    Bucket::object_delete();
});