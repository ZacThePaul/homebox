<?php

class podcrackController {
    public function __construct() {

    }

    static function search_shows() {

        $search_query = str_replace(' ', '+', $_GET['search-query']);

        // Required values
        $apiKey = $_ENV['PODCASTINDEX_API_KEY'];
        $apiSecret = $_ENV['PODCASTINDEX_API_SECRET'];
        $apiHeaderTime = time();
    
        // Hash them to get the Authorization token
        $hash = sha1($apiKey.$apiSecret.$apiHeaderTime);
    
        // Set the required headers
        $headers = [
            "User-Agent: PodCRACK/1.0 (ZacBanas; zacbanas27@gmail.com)",
            "X-Auth-Key: $apiKey",
            "X-Auth-Date: $apiHeaderTime",
            "Authorization: $hash"
        ];
    
        // Make the request to an API endpoint
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.podcastindex.org/api/1.0/search/byterm?q=" . $search_query);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
        // Uncomment the line below to disable SSL verification (not recommended for production)
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
        // Collect and show the results
        $response = curl_exec($ch);
        if ($response === false) {
            echo 'Curl error: ' . curl_error($ch);
        }
    
        curl_close($ch);
    
        // Decode the JSON response into an associative array
        $data = json_decode($response, true);
    
        // Check if the 'feeds' key exists in the data
        if (isset($data['feeds'])) {
            echo json_encode($data);
        } else {
            echo $data;
        }
    
    }
    static function search_episodes( $feed_id = false, $episode_max = false ) {

        // $feed_id determines whether the method is being called via php or javascript

        if (!isset($feed_id)) {
            $search_query = str_replace(' ', '+', $_GET['search-query']);
        }
        else {
            $search_query = $feed_id;
        }

        // Required values
        $apiKey = $_ENV['PODCASTINDEX_API_KEY'];
        $apiSecret = $_ENV['PODCASTINDEX_API_SECRET'];
        $apiHeaderTime = time();
    
        // Hash them to get the Authorization token
        $hash = sha1($apiKey.$apiSecret.$apiHeaderTime);
    
        // Set the required headers
        $headers = [
            "User-Agent: PodCRACK/1.0 (ZacBanas; zacbanas27@gmail.com)",
            "X-Auth-Key: $apiKey",
            "X-Auth-Date: $apiHeaderTime",
            "Authorization: $hash"
        ];
    
        // Make the request to an API endpoint
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.podcastindex.org/api/1.0/episodes/byfeedid?id=" . $search_query . "&pretty" . ($episode_max ? "&max=" . $episode_max : "&max=1000" . ""));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
        // Uncomment the line below to disable SSL verification (not recommended for production)
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
        // Collect and show the results
        $response = curl_exec($ch);
        if ($response === false) {
            echo 'Curl error: ' . curl_error($ch);
        }
    
        curl_close($ch);
    
        // Decode the JSON response into an associative array
        $data = json_decode($response, true);
    
        // Check if the 'feeds' key exists in the data
        if (isset($data['items'])) {
            // if $feed_id then the method needs to return data to be assigned via php
            // if not, then it needs to echo in order to be retrieved via js
            if (!$feed_id) {
                echo json_encode($data);
            }
            else {
                return $data;
            }
        } else {
            if (!$feed_id) {
                echo $data;
            }
            else {
                return false;
            }
        }

    }

    static function show_podcast() {
        $podcast_string = urldecode($_GET['object']);
        $podcast_object = json_decode($podcast_string);

        $podcast_episodes = self::search_episodes( $podcast_object->id );

        $_SESSION['single_podcast_object'] = $podcast_object;
        $_SESSION['single_podcast_episodes'] = $podcast_episodes;

        views('apps/podcrack/single-podcast.php');
    }

    static function index() {

        // Define the file path
        $filePath = '/var/www/html/apps-directory/podcrack-app/podcrack.xml';

        // Check if the file exists
        if (!file_exists($filePath)) {
            die("File not found");
        }

        // Load the XML from the file
        $xml = simplexml_load_file($filePath);

        views('apps/podcrack/main.php', $xml);
    }

    static function subscribe() {
        $podcast = urldecode($_GET['podcast']);
        $podcast = json_decode($podcast);

        // Define the file path
        $filePath = '/var/www/html/apps-directory/podcrack-app/podcrack.xml';

        // Check if the file exists
        if (!file_exists($filePath)) {
            die("File not found");
        }

        // Load the XML from the file
        $xml = simplexml_load_file($filePath);

        // Add a new podcast element (continue as in the previous example)
        $newPodcast = $xml->library->addChild('podcast');
        $newPodcast->addChild('id', $podcast->id);
        $newPodcast->addChild('name', $podcast->title);
        $newPodcast->addChild('description', $podcast->description);
        $newPodcast->addChild('author', $podcast->author);
        $newPodcast->addChild('artwork', $podcast->artwork);
        $newPodcast->addChild('rss', $podcast->url);
        $newPodcast->addChild('object', json_encode($podcast));
        $newPodcast->addChild('metadata');

        $dom = new DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());

        // Save the formatted XML back to the file
        $dom->save($filePath);

        header('Location: ' . '/apps/podcrack');

    }

    static function unsubscribe() {
        $podcast_id = $_GET['podcast'];
        $user = $_GET['user'];

        // Define the file path
        $filePath = '/var/www/html/apps-directory/podcrack-app/podcrack.xml';

        // Check if the file exists
        if (!file_exists($filePath)) {
            die("File not found");
        }

        // Load the XML from the file using DOMDocument
        $dom = new DOMDocument();
        $dom->load($filePath);
        $xpath = new DOMXPath($dom);

        // XPath query to find the specific podcast node
        $query = "/podcrack/library[@user_id='$user']/podcast[id='$podcast_id']";
        $podcastNode = $xpath->query($query)->item(0);

        if ($podcastNode) {
            // Remove the podcast node
            $podcastNode->parentNode->removeChild($podcastNode);
            // Save the updated XML back to the file
            $dom->save($filePath);
            header('Location: ' . '/apps/podcrack');
        } else {
            die("Podcast not found");
        }

    }

}

