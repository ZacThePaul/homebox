<!-- podcrack main.php -->
<?php
ob_start(); // Start output buffering

$page_name = "lists-app-new-list";
?>

<div class="content-container">

    <?php include 'partials/header.php' ?>

    

</div>

<?php
$content = ob_get_clean(); // Store buffered content in a variable
$title = "ListyMcListyFace"; // Set the title for this page
include "/var/www/html/views/layout.php"; // Include the layout