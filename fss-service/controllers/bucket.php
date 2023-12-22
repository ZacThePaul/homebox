<?php

const BUCKETS_JSON = 'http://192.168.1.23/fss-service/buckets.json';

// Grabbed from https://www.uuidgenerator.net/dev-corner/php
function guidv4($data = null) {
    // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
    $data = $data ?? random_bytes(16);
    assert(strlen($data) == 16);
    // Set version to 0100
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    // Set bits 6-7 to 10
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
    // Output the 36 character UUID.
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

class Bucket {

    public function __construct() {
    }

    static function index() {
        // Get the current user's ID from the session
        $currentUserId = $_SESSION['user_id'];
    
        // Read the JSON file
        $json = file_get_contents(BUCKETS_JSON);
        $buckets = json_decode($json, true);
    
        // Filter the buckets array to include only those owned by the current user
        $userBuckets = array_filter($buckets, function ($bucket) use ($currentUserId) {
            return $bucket['bucket_owner'] == $currentUserId;
        });
    
        // Encode the filtered array back to JSON and echo it
        echo json_encode(array_values($userBuckets));
    }    

    static function show() {
        echo 'show';
    }

    static function create() {

        $bucket = array(
            'bucket_id'     => guidv4(),
            'bucket_name'   => $_POST['bucket_name'],
            'bucket_slug'   => strtolower( str_replace( ' ', '-', $_POST['bucket_name'] ) ),
            'bucket_objects'=> array(),
            'bucket_owner'  => $_SESSION['user_id']
        );

        $json = file_get_contents(BUCKETS_JSON);
        $data = json_decode($json);

        if (count($data) > 0){
            foreach ( $data as $item ) {
                if ($item->bucket_name == $bucket['bucket_name']) {
                    die('Sorry but a bucket name must be unique, and this bucket name has already been used. ');
                }
            }
        }

        $data[] = $bucket;

        file_put_contents('/var/www/html/fss-service/buckets.json', json_encode($data, JSON_PRETTY_PRINT));

        header('Location: ' . '/fss/bucket?slug=' . $bucket["bucket_slug"]);
    }

    static function delete() {
        $bucket_slug = $_GET['slug']; // Get the slug of the bucket to be deleted
    
        // Load the buckets data
        $json = file_get_contents(BUCKETS_JSON);
        $data = json_decode($json, true);
    
        // New array to hold the remaining buckets
        $newData = [];
    
        // Find and keep buckets that don't match the slug
        foreach ($data as $bucket) {
            if ($bucket['bucket_slug'] !== $bucket_slug) {
                $newData[] = $bucket;
            } else {
                // Delete all files associated with the bucket
                foreach ($bucket['bucket_objects'] as $object) {
                    $file = "/var/www/html/fss-service/storage/" . $object['object_location'];
                    if (file_exists($file)) {
                        unlink($file); // Delete the file
                    }
                }
            }
        }
    
        // Save the updated data back to the JSON file
        file_put_contents('/var/www/html/fss-service/buckets.json', json_encode($newData, JSON_PRETTY_PRINT));
    
        // Redirect or inform the user
        header('Location: /fss'); // Redirect to a safe location, update as needed
    }
    
    static function object_create() {

        $parent_bucket_slug = $_POST['bucket-slug'];

        $target_dir = "/var/www/html/fss-service/storage/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

        move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
        
        $json = file_get_contents(BUCKETS_JSON);
        $data = json_decode($json);
    
        foreach ( $data as $item ) {
            if ($item->bucket_slug == $parent_bucket_slug) {
                // $item->bucket_objects[] = $target_file;
    
                // Create an associative array with the desired properties
                $objectDetails = [
                    "object_id"         => guidv4(), // Replace with actual ID
                    "object_location"   => basename($_FILES["fileToUpload"]["name"]), // Replace with actual location
                    "object_filesize"   => filesize($target_file), // Replace with actual file size
                    "object_name"       => $_POST['object-name']
                ];
    
                // Append this associative array to the bucket_objects
                $item->bucket_objects[] = $objectDetails;
            }
        }
    
        file_put_contents('/var/www/html/fss-service/buckets.json', json_encode($data, JSON_PRETTY_PRINT));
    
        header('Location: ' . '/fss/bucket?slug=' . $parent_bucket_slug);

    }
    static function object_delete() {

        $storage_dir = glob("/var/www/html/fss-service/storage/*");

        foreach ($storage_dir as $file) {

            $parent_bucket  = $_GET['slug'];
            $object_to_kill = $_GET['object_location'];

            if ($object_to_kill == end(explode('/', $file))) {

                // Remove the file from /storage
                unlink($file);

                $json = file_get_contents(BUCKETS_JSON);
                $data = json_decode($json, true);

                // Parameters to match
                $bucketSlugToMatch = $parent_bucket;
                $objectLocationToMatch = $object_to_kill;

                // Iterate through buckets
                foreach ($data as $key => $bucket) {
                    if ($bucket['bucket_slug'] === $bucketSlugToMatch) {
                        // Iterate through objects in the matched bucket
                        foreach ($bucket['bucket_objects'] as $objectKey => $object) {
                            if ($object['object_location'] === $objectLocationToMatch) {
                                // Remove the object from buckets.json
                                unset($data[$key]['bucket_objects'][$objectKey]);
                                // Re-index the array to maintain structure
                                $data[$key]['bucket_objects'] = array_values($data[$key]['bucket_objects']);
                                break; // Stop the loop if object is found and removed
                            }
                        }
                    }
                }

                // Save the updated JSON back to the file
                file_put_contents('/var/www/html/fss-service/buckets.json', json_encode($data, JSON_PRETTY_PRINT));
                header('Location: ' . '/fss/bucket?slug=' . $parent_bucket);
            }

        }

    }
}