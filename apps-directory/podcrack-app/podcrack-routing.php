<?php

include 'controllers/podcrack.php';

function downloadFile($url, $path) {
    $fp = fopen($path, 'w+');

    if ($fp === false) {
        throw new Exception('Could not open: ' . $path);
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MyPodcastDownloader/1.0)');

    $success = curl_exec($ch);

    if (curl_errno($ch)) {
        throw new Exception(curl_error($ch));
    }

    curl_close($ch);
    fclose($fp);

    return $success !== false;
}

/*
/ FOR HANDLING PodCRACK CALLS
*/

// frontend calls
$router->get('/apps/podcrack', function() {
    podcrackController::index();
});

$router->get('/apps/podcrack/podcast/search', function() {
    podcrackController::search_shows();
});

$router->get('/apps/podcrack/episodes/search', function() {
    podcrackController::search_episodes();
});

$router->post('/apps/podcrack/podcast/pull', function() {

    header('Content-Type: application/json');

    // Get the raw POST data
    $rawData = file_get_contents("php://input");

    // Log raw data for debugging
    error_log("Raw POST data: " . $rawData);

    // Decode the JSON into an array
    $data = json_decode($rawData, true);

    // Check for JSON decoding errors
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400); // Bad request
        error_log("JSON decoding error: " . json_last_error_msg());
        die(json_encode(["error" => "Invalid JSON data."]));
    }

    $counter = 0;

    // Check if 'podcasts' key exists
    if (isset($data['podcasts'])) {
        foreach($data['podcasts'] as $podcast) {
            downloadFile($podcast, '/var/www/html/apps/podcrack-app/podcasts/' . $counter . '.mp3');
            $counter++;
        }
        echo json_encode('download done');
    } else {
        http_response_code(400); // Bad request
        echo json_encode(["error" => "Missing 'podcasts' key."]);
    }
});

$router->get("/apps/podcrack/podcast/view", function() {
    podcrackController::show_podcast();
});

$router->get("/apps/podcrack/podcast/subscribe", function() {
    podcrackController::subscribe();
});

$router->get("/apps/podcrack/podcast/unsubscribe", function() {
    podcrackController::unsubscribe();
});
