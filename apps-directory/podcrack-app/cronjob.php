<?php

function downloadFile($url, $path) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MyPodcastDownloader/1.0)');
    $data = curl_exec($ch);
    curl_close($ch);
    return file_put_contents($path, $data);
}

function download_podcast( ) {

    $url = 'https://feed.podbean.com/casualpreppers/feed.xml';

    // Load the XML file
    $xml = simplexml_load_file($url);

    if ($xml === false) {
        echo "Failed to load XML file.";
        exit;
    }

    // Iterate over each item in the XML file
    foreach ($xml->channel->item as $item) {
        // Extract information for each episode
        $title = $item->title;
        $pubDate = $item->pubDate;

        // Extract the enclosure URL
        $enclosureUrl = (string)$item->enclosure['url'];

        // Output the information
        echo "Title: $title\n";
        echo "Publish Date: $pubDate\n";
        echo "Enclosure URL: $enclosureUrl\n\n";

        // Download the podcast episode
        $episodeContent = downloadFile($enclosureUrl, '/var/www/html/apps/podcrack/podcasts/' . $title . '.mp3');

        break;
    }

}

// Check if the script is called from the command line with a specific argument
if (isset($argv) && isset($argv[1])) {
    switch ($argv[1]) {
        case "download_podcasts":
            download_podcast();
            break;
    }
} else {
    echo "This script is intended to be run from the command line.";
}

/*

//\\ ==== EVENING THOUGHTS ==== //\\

what if we used FSS as storage for podcasts and pinged it programatically to store everything.. 
I mean it already has the infrastructure.

The only issue I can foresee is in the future when we restrict access to the API to registered users via middleware.
Then we will need to create pseudo-users for apps to use.
Right now anyone can just send a request to the API... smart.

This really shows the distinction between "apps" and "services" well. 
An app can rely on a service, a service probably wouldn't rely on an app.

cool.

*/