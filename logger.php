<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

const NETWORK_IP = '192.168.1';
const TAILSCALE_IP = '100.127.60.38';

// if ( !str_contains($_SERVER['REMOTE_ADDR'], NETWORK_IP) ||  !str_contains($_SERVER['REMOTE_ADDR'], TAILSCALE_IP)) {
//     die('Access Denied');
// }

file_put_contents(
    'access.log',
    'Date and Time: ' . date('m/d/Y h:i:s a', $_SERVER['REQUEST_TIME']) . ' | ' . 'Request IP: ' . $_SERVER['REMOTE_ADDR'] . PHP_EOL,
    FILE_APPEND
);