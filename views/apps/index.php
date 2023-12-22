<!-- dashboard.php -->
<?php
ob_start(); // Start output buffering

$page_name = "apps";
?>

<div class="content-container">
    <a href="/apps/podcrack">PodCRACK</a>
    <a href="/apps/lists">ListyMcListyFace</a>
</div>

<?php
$content = ob_get_clean(); // Store buffered content in a variable
$title = "Apps"; // Set the title for this page
include "/var/www/html/views/layout.php"; // Include the layout