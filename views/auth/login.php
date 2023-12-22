<!-- login.php -->
<?php
ob_start(); // Start output buffering
?>

<div class="content-container">
    <p>This is the login page.</p>

    <form method="post" action="/usm/user/login">
        <input type="text" name="email" placeholder="enter your email">
        <input type="password" name="password" placeholder="enter pass">
        <input type="submit">
    </form>
</div>

<?php
$content = ob_get_clean(); // Store buffered content in a variable
$title = "Login"; // Set the title for this page
include '/var/www/html/views/layout.php'; // Include the layout