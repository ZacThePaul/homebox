<?php

require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

session_start();

include 'logger.php';
include 'router.php';

include 'fss-service/controllers/bucket.php'; // change this so that it's only called when it's needed in the route

include 'helpers.php';

?>

<?php

$router = new Router($_SERVER);

$router->get('/', function() {
    include 'home.php';
});

/*
/ FOR HANDLING USM CALLS
*/
include './usm-service/usm-routing.php';

/*
/ FOR HANDLING FSS CALLS
*/
include 'fss-service/fss-routing.php';

/*
/ FOR HANDLING APP CALLS
*/
include 'apps-directory/app-routing.php';

// phpinfo();
?>
