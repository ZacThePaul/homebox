<!-- dashboard.php -->
<?php
ob_start(); // Start output buffering

$page_name = "fss";
?>

<div class="content-container">
    <h2>Create a new bucket</h2>
    <form method="POST" action="<?php echo $_ENV['HOMEBOX_URL'] ?>/fss/bucket/create">
        <input type="text" name="bucket_name" placeholder="Desired bucket name">
        <input type="submit" value="Create your bucket">
    </form>
    <h2>Click on your bucket and upload some media!! WOOOO 
        <br>Storage Used: <span id="account-storage"></span>
        <br>Number of Buckets: <span id="account-buckets"></span>
    </h2>
    <div id="bucket-container">
    </div>
</div>

<script>

    function formatSize(sizeInBytes) {
        const sizeInKB = sizeInBytes / 1000;
        const sizeInMB = sizeInKB / 1000;
        const sizeInGB = sizeInMB / 1000;
        const sizeInTB = sizeInGB / 1000;

        if (sizeInKB < 1000) {
            return sizeInKB.toFixed(2) + " KB";
        } else if (sizeInMB < 1000) {
            return sizeInMB.toFixed(2) + " MB";
        } else if (sizeInGB < 1000) {
            return sizeInGB.toFixed(2) + " GB";
        } else {
            return sizeInTB.toFixed(2) + " TB";
        }
    }


    fetch('<?php echo $_ENV['HOMEBOX_URL'] ?>/fss/buckets', {
        'method': 'GET',
        'content-type': 'application/json'
    })
    .then(response => response.json())  // Parse the response as JSON
    .then(data => {

        let fssAccountStorage = 0;

        // Loop through buckets
        for (let i = 0; i < data.length; i++) {
            let element = data[i];
            let bucketObjs = element.bucket_objects;
            let bucketStorageUsed = 0;

            // Loop through objects
            for (let n = 0; n < bucketObjs.length; n++) {
                let objectSize = bucketObjs[n];
                bucketStorageUsed += objectSize.object_filesize;
            }

            fssAccountStorage += bucketStorageUsed;

            document.getElementById('bucket-container').innerHTML += 
                '<div class="single-bucket">' +
                    '<a href="<?php echo $_ENV['HOMEBOX_URL'] ?>/fss/bucket?slug=' + element.bucket_slug + '" class="bucket-title">' + element.bucket_name + '</a>' +
                    '<span class="bucket-obj-count"><b>Bucket Objects</b>: ' + element.bucket_objects.length + '</span>'+ 
                    '<span><b>Bucket Size</b>: ' + formatSize(bucketStorageUsed) + '</span>'+
                    '<a href="<?php echo $_ENV['HOMEBOX_URL'] ?>/fss/bucket/delete?slug=' + element.bucket_slug + '" class="object-delete"><i class="fa-regular fa-trash"></i> </a>'+
                '</div>';
        }

        // Attach the event listener to all elements with the class 'object-delete'
        const deleteButtons = document.getElementsByClassName('object-delete');

        Array.from(deleteButtons).forEach(button => {
            button.addEventListener('click', handleDeleteClick);
            console.log(button)
        })

        document.getElementById('account-storage').innerHTML = formatSize(fssAccountStorage);
        document.getElementById('account-buckets').innerHTML = data.length;

    })
    .catch(error => {
        console.error('Error fetching data:', error);
    });

    // Function to handle the click event
    function handleDeleteClick(event) {
        console.log('test');
        // Show a confirmation dialog
        const userConfirmed = window.confirm("Are you sure you want to delete this bucket? This cannot be undone.");

        // If the user did not confirm, prevent the default action
        if (!userConfirmed) {
            event.preventDefault();
        }
        // If the user confirmed, the default action will proceed
    }

</script>

<?php
$content = ob_get_clean(); // Store buffered content in a variable
$title = "FSS"; // Set the title for this page
include "/var/www/html/views/layout.php"; // Include the layout