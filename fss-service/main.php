<?php
// include $_ENV['HOMEBOX_HEADER'];
?>

<link rel="stylesheet" href="<?php echo $_ENV['HOMEBOX_URL'] ?>/stylesheets/fss.css">

<body class="dark-theme">
    <div class="content-container">
        <h2>Create a new bucket!!!</h2>
        <form method="POST" action="<?php echo $_ENV['HOMEBOX_URL'] ?>/fss/bucket/create">
            <input type="text" name="bucket_name" placeholder="Desired bucket name">
            <input type="submit" value="Create your bucket">
        </form>
        <h2>Click on your bucket and upload some media!!888 WOOOO 
            <br>Storage Used: <span id="account-storage"></span>
            <br>Number of Buckets: <span id="account-buckets"></span>
        </h2>
        <div id="bucket-container">
        </div>
    </div>
</body>

<script>

    const websiteUrl = window.location.host;

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

    console.log(websiteUrl);

    fetch( websiteUrl + '/fss/buckets', {
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
                    '<a href="' + websiteUrl + '/fss/bucket?slug=' + element.bucket_slug + '" class="bucket-title">' + element.bucket_name + '</a>' +
                    '<span class="bucket-obj-count"><b>Bucket Objects</b>: ' + element.bucket_objects.length + '</span>'+ 
                    '<span><b>Bucket Size</b>: ' + formatSize(bucketStorageUsed) + '</span>'+
                    '<a href="<?php echo $_ENV['HOMEBOX_URL'] ?>/fss/bucket/delete?slug=' + element.bucket_slug + '" class="object-delete"> Delete bucket </a>'+
                '</div>';
        }

        document.getElementById('account-storage').innerHTML = formatSize(fssAccountStorage);
        document.getElementById('account-buckets').innerHTML = data.length;

    })
    .catch(error => {
        console.error('Error fetching data:', error);
    });

</script>