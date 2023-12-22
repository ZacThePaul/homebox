<!-- podcrack main.php -->
<?php
ob_start(); // Start output buffering

$page_name = "podcrack-single";

$podcast = $_SESSION["single_podcast_object"];
$episodes = $_SESSION["single_podcast_episodes"];
?>

<div class="content-container">

    <?php include 'partials/header.php' ?>

    <div id="podcast-container"></div>

    <div class="single-podcast">

        <div class="single-podcast-details">

            <div class="single-podcast-logo">
                <img src="<?= $podcast->artwork ?>" alt="">
            </div>

            <div class="single-podcast-text">
                <h2><?= $podcast->title ?></h2>
                <p><?= $podcast->description ?></p>
                <?php if (is_subscribed($podcast->id)) : ?>
                    <a href="/apps/podcrack/podcast/unsubscribe?user=<?= $_SESSION['user_id'] ?>&podcast=<?= $podcast->id ?>">
                        <i class="fa-solid fa-check"></i> Subscribed
                    </a>
                <?php else : ?>
                    <a href="/apps/podcrack/podcast/subscribe?podcast=<?= urlencode(json_encode($podcast)) ?>">
                        <i class="fa-solid fa-plus"></i> Subscribe
                    </a>
                <?php endif; ?>
            </div>

        </div>

        <div class="single-podcast-episodes-index">
            <?php
            // Assuming $yourArray is the array you provided
            if (isset($episodes['items']) && is_array($episodes['items'])) :
                foreach ($episodes['items'] as $episode) : ?>

                    <div class="single-podcast-single-episode">
                        <div>
                            <?php echo $episode['episode'] ? '<b>E' . $episode['episode'] . '</b>' . ' <h4>' . $episode['title'] . '</h4>' : ' <h4>' . $episode['title'] . '</h4>' ?>
                        </div>
                        <div>
                            <div>
                                <p>Duration: <?= formatSeconds($episode['duration']) ?></p>
                                <p><?= date('m/d/Y', $episode['datePublished']) ?></p>
                            </div>
                            <div>
                                <a href="<?= $episode['enclosureUrl'] ?>" class="single-podcast-episode-play is_paused">
                                    <i class="fa-solid fa-play"></i>
                                    <i class="fa-solid fa-pause"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                <?php endforeach;
            endif; ?>
        </div>

    </div>

</div>

<script>
    const homeboxUrl = "<?php echo $_ENV['HOMEBOX_URL'] ?>";
</script>
<?php get_scripts('apps/podcrack.min.js')?>

<?php
$content = ob_get_clean(); // Store buffered content in a variable
$title = "PodCRACK - " . $podcast->title; // Set the title for this page
include "/var/www/html/views/layout.php"; // Include the layout