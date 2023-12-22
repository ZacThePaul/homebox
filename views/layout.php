<!-- layout.php -->
<!DOCTYPE html>
<html>
<head>
    <title>HomeBox - <?php echo isset($title) ? $title : 'Default Title'; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="homebox.png">
    <link rel="stylesheet" href="/public/styles/main.css">

    <link href="/public/images/icons/kit-e63c5d2ed2-web/css/fontawesome.css" rel="stylesheet">
    <link href="/public/images/icons/kit-e63c5d2ed2-web/css/regular.css" rel="stylesheet">
    <link href="/public/images/icons/kit-e63c5d2ed2-web/css/solid.css" rel="stylesheet">
    <!-- https://fontawesome.com/docs/web/setup/host-yourself/webfonts <-- REFERENCE --> 


</head>
<body class="<?php echo isset($page_name) ? $page_name . '-page' : 'homebox-page'?>">

    <?php include("partials/header.php"); ?>
    
    <!-- Main content -->
    <main>
        <?php if (isset($content)) echo $content; ?>
        <?php include("partials/footer.php"); ?>
    </main>
    <script src="/assets/scripts/main.min.js">
</body>

</html>
