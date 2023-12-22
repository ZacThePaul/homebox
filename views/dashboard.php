<!-- dashboard.php -->
<?php
ob_start(); // Start output buffering
?>

<div class="content-container">
    <h1>Hello <?php echo $_SESSION['user_name']; ?>!</h1>
    <p>This is the dashboard page.</p>
</div>

<?php

// phpinfo();

$content = ob_get_clean(); // Store buffered content in a variable
$title = "Dashboard"; // Set the title for this page
include "layout.php"; // Include the layout