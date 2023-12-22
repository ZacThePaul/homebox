<!-- register.php -->
<?php
ob_start(); // Start output buffering
?>

<div class="content-container">
    <p>This is the register page.</p>

    <form method="post" action="/usm/user/create">
        <input type="text" name="name" placeholder="Your name">
        <input type="text" name="username" placeholder="Your username">
        <input type="email" name="email" placeholder="Your email">
        <input type="text" name="password" placeholder="Password">
        <input type="submit">
    </form>
</div>

<?php
$content = ob_get_clean(); // Store buffered content in a variable
$title = "Register"; // Set the title for this page
include '/var/www/html/views/layout.php'; // Include the layout