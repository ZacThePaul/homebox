<?php

include "controllers/UserController.php";

// FRONTEND
$router->get('/usm', function() {
    views('usm/main.php');
});

$router->get('/dashboard', function() {
    UserController::showProfile();
});

$router->get("/usm/register", function() {
    UserController::showRegister();
});

$router->get("/usm/login", function() {
    UserController::showLogin();
});

// API
$router->get('/usm/users', function() {
    UserController::index();
});

$router->post('/usm/user/create', function() {
    UserController::create();
});

$router->post('/usm/user/login', function() {
    UserController::login();
});

$router->get('/usm/user/logout', function() {
    UserController::logout();
});