<?php

include 'usm-helpers.php';
include 'User.php';
include $_ENV['HOMEBOX_HEADER'];

hb_header('HomeBox - USM | User and Security Management', "/homebox.png")

?>

<h1>USM - User and Security Management</h1>

<ul>
    <?php

        $user = new UserModel();
        // $user->dropTable('users');
        // $user->createTable();
        // $user->addUser('zac banas', 'zac', 'zacbanas27@gmail.com', 'Rowan1995!');
        
        $user->dumpAllRows();

    ?>

    <div id="user-container"></div>
</ul>

<script>

    fetch('<?php $_ENV['HOMEBOX_URL'] ?>/usm/users', {
        'method': 'GET',
        'content-type': 'application/json'
    })
    .then(response => response.json())
    .then(data => {

        if ( data[0] == false) {
            document.getElementById('user-container')
            .innerHTML = 'PERMISSION DENIED: insufficient permissions'
            return;
        }
        
        data.forEach((element) => document.getElementById('user-container')
            .innerHTML += '<li>' + element.name + ' - ' + element.policy + '</li>')
    })
    
</script>