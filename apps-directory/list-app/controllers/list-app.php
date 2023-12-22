<?php

class listAppController {

    public static $filepath = '/var/www/html/apps-directory/list-app/lists.xml';

    public function __construct() {}

    static function index() {

        // Check if the file exists
        if (!file_exists(self::$filepath)) {
            die("File not found");
        }

        // Load the XML from the file
        $xml = simplexml_load_file(self::$filepath);

        echo json_encode($xml);
    }

    static function create() {
        

        // Read the JSON input stream and decode it
        $json = file_get_contents('php://input');
        $data = json_decode($json, true); // true for associative array

        // die(json_encode($data['listItems']));

        // Check if the file exists
        if (!file_exists(self::$filepath)) {
            die("File not found");
        }

        // Load the XML from the file
        $xml = simplexml_load_file(self::$filepath);

        if ($xml === false) {
            die("Failed to load XML");
        }
        
        $new_list = $xml->addChild('list');
        $new_list->addAttribute('user_id', $data['userId']);
        $new_list->addAttribute('shareable', 0);
        $new_list->addAttribute('community', 0);
        $new_list->addChild('name', $data['name']);
        $items = $new_list->addChild('items');

        foreach( $data['listItems'] as $item ) {
            $items->addChild('item', $item);
        }

        $dom = new DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());

        // Save the formatted XML back to the file
        $dom->save(self::$filepath);

        echo json_encode(1);
        // header('Location: http://192.168.1.23/apps/lists');

    }

}