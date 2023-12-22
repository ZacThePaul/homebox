<!-- podcrack main.php -->
<?php
ob_start(); // Start output buffering

$page_name = "podcrack";
?>

<div class="content-container">

    <?php include 'partials/header.php' ?>
    
    <div id="podcast-container"></div>

    <div class="podcrack-library">

        <?php if(isset($_SESSION['user_id'])) : ?>
            <?php foreach( $var->library as $library ) : ?>
                <?php if( $library['user_id'] == $_SESSION['user_id'] ) : ?>
                    
                    <?php foreach( $var->library->podcast as $podcast ) : ?>
                        <div class="podcrack-library-item">
                            <a href="podcrack/podcast/view?object=<?= urlencode($podcast->object) ?>">
                                <img src="<?= $podcast->artwork ?>" alt="" style="">
                            </a>
                        </div>
                    <?php endforeach; ?>

                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?> 

    </div>

</div>

<script>
    const homeboxUrl = "<?php echo $_ENV['HOMEBOX_URL'] ?>";
</script>
<?php get_scripts('apps/podcrack.min.js')?>

<?php
$content = ob_get_clean(); // Store buffered content in a variable
$title = "PodCRACK"; // Set the title for this page
include "/var/www/html/views/layout.php"; // Include the layout