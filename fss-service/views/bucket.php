<?php
include $_ENV['HOMEBOX_HEADER'];
?>

<link rel="stylesheet" href="<?php echo $_ENV['HOMEBOX_URL'] ?>/stylesheets/fss.css">

<body class="dark-theme single-bucket">

    <?php hb_header('HomeBox - FSS | File Storage Service', "/homebox.png"); ?>

    <a href="/fss">Back to FSS</a>

    <form action="/fss/object/upload" method="post" enctype="multipart/form-data">
        <input type="text" name="object-name" id="object-name" placeholder="File Name" required>
        <input type="file" name="fileToUpload" id="fileToUpload" required>
        <input type="submit" value="Upload File" name="submit">
        <input type="hidden" name="bucket-slug" value="<?php echo $item->bucket_slug ?>">
    </form>

    <div id="object-container">

    </div>

</body>

<script>

    let bucketSlug = <?php echo json_encode($item->bucket_slug); ?>;

    fetch('http://192.168.1.23/fss/objects?slug=' + bucketSlug, {
        'method': 'GET',
        'content-type': 'application/json'
    })
    .then(response => response.json())  // Parse the response as JSON
    .then(data => {

        data.forEach((element) => document.getElementById('object-container').innerHTML += 
        '<li>'+
            '<a href="<?php echo $_ENV['HOMEBOX_URL'] ?>/fss-service/storage/' + element.object_location + '">' + element.object_name + '</a>'+
            '<span>' + (element.object_filesize / 1000 / 1000).toFixed(2) + 'MB</span>'+
            '<a href="/fss/object/delete?slug=' + encodeURIComponent(bucketSlug) + '&object_location=' + encodeURIComponent(element.object_location) + '" class="object-delete"> X </a>'+
        '</li>')
        
    })
    .catch(error => {
        console.error('Error fetching data:', error);
    });

</script>

<style>


</style>