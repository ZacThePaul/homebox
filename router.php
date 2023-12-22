<?php

// include 'auth-middleware';

class Router {

    private $request;

    public function __construct($request) {
        $this->request = $request;
    }

    private function normalizeUrl($url) {
        // Remove trailing slash for consistent matching, except for root '/'
        return $url == '/' ? $url : rtrim($url, '/');
    }

    public function get($url, $function, $params=false) {

        // if ($auth_require) {
        //     if (is_authenticated()) {
        //         allow it
        //     }
        //     else {
        //         redirect to login
        //     }
        // }

        // Do this so the $function can process url parameters
        $base_url = explode('?', $_SERVER['REQUEST_URI'], 2)[0];
        $base_url = $this->normalizeUrl($base_url); // Normalize the URL

        if ($this->request['REQUEST_METHOD'] == 'GET') {
            if ($url == $base_url) {
                $function();
            }
        }
        // else {
        //     echo 'request types do not match - router.php <br><br>';
        // }
    }

    public function post($url, $function) {
        if ($this->request['REQUEST_METHOD'] == 'POST') {
            // echo $url . ' - ' . $this->request['REQUEST_URI'] . '<br><br>';
            
            if ($url == $this->request['REQUEST_URI']) {
                $function();
            }
            // else {
            //     echo 'request types do not match - router.php <br><br>';
            // }
        }
    }

    public function spill() {
        var_dump($this->request);
    }

}