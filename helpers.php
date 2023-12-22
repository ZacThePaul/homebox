<?php

function views($view, $var = false) {
    include '/var/www/html/views/' . $view;
}

function get_scripts($script) {
    echo '<script src="http://192.168.1.23/public/scripts/' . $script . '"></script>';
}

function formatSeconds($seconds) {
    // Convert string to integer
    $seconds = intval($seconds);

    // Calculate hours and minutes
    $hours = floor($seconds / 3600);
    $minutes = floor(($seconds % 3600) / 60);

    if ($hours == 0) {
        // Format the string
        $formattedTime = $minutes . "m";
    } else {
        $formattedTime = $hours . "h" . $minutes;
    }

    return $formattedTime;
}

function is_subscribed($podcast_id) {
    // Makes sure a user is logged in for this
    if ($_SESSION['user_id']) {

        // Define the file path
        $filePath = '/var/www/html/apps-directory/podcrack-app/podcrack.xml';

        // Check if the file exists
        if (!file_exists($filePath)) {
            die("File not found");
        }

        $is_subscribed = 0;

        // Load the XML from the file
        $xml = simplexml_load_file($filePath);

        foreach( $xml->library as $library ) :
            // If it's the correct library
            if( $library['user_id'] == $_SESSION['user_id'] ) :
                foreach( $xml->library->podcast as $podcast ) :
                    if( $podcast->id == $podcast_id ) :
                        // kind of a janky solution, but I can't think of any other way to do it right now
                        $is_subscribed += 1;
                    else:
                        $is_subscribed += 0;
                    endif;
                endforeach;
            endif;
        endforeach;

        return $is_subscribed;
    }
}