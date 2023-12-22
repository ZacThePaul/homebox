<!-- <?php
// URL of the image
// $imageUrl = 'https://toscrape.com/img/books.png'; // Replace with the actual URL

// $targetDirectory = '/var/www/html/test/'; // Specify your target directory
// $fileName = $targetDirectory . basename($imageUrl);

// // Use file_get_contents() to download the file
// $imageData = file_get_contents($imageUrl);

// if ($imageData !== false) {
//     // Save the image
//     echo file_put_contents($fileName, $imageData);
//     echo "Image downloaded successfully.";
// } else {
//     echo "Failed to download the image.";
// }
?> -->

<?php
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
}
?>