<?php

function hb_header($title, $favicon_url) { 
    
    ?>

    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>

    <div class="hb-header">

        <link rel="icon" type="image/x-icon" href="<?php echo $favicon_url; ?>">
        <title><?php echo $title; ?></title>

        <a href="/">HOME</a>
        <?php if (!isset($_SESSION['user_email'])) : ?>
            <a href="/usm/register">Register</a>
            <a href="/usm/login">Login</a>
        <?php 
            else: ?>
            <span>Hello <?php echo $_SESSION['user_name']; ?>!</span>
            <a href="/usm/user/logout">Logout</a>
        <?php endif; ?>

    </div>

<?php }