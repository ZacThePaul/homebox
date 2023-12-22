<!-- podcrack main.php -->
<?php
ob_start(); // Start output buffering

$page_name = "lists-app";

// var_dump(json_decode($var));

// foreach

?>

<div class="content-container">

    <?php include 'partials/header.php' ?>

    <!-- FOR DISPLAYING ALL LISTS -->
    <div class="list-index-container show">
        <div class="list-container">

            <div class="self-lists">
                <h3>Your Private Lists!</h3>
                <div id="self-lists-container">

                </div>
            </div>

            <hr>

            <div class="community-lists">
                <h3>Community Lists!</h3>
                <div id="community-lists-container">

                </div>
            </div>
        </div>
    </div>

    <!-- FOR ADDING NEW LISTS -->
    <div class="list-add-new-container hide">

        <h5>Add a New List!</h5>
        <input type="text" placeholder="List Name" id="list-name">

        <div id="list-item-container">
            <ul>
                <label for="">Add your list item</label>
                <li contenteditable="true" class="active-list-item"></li>
                <button class="delete-list-item">Delete</button>
            </ul>
        </div>

        <button id="add-list-item">Add New Item</button>
        <button id="save-list">Save Your List</button>

    </div>

    <!-- FOR EDITING EXISTING LISTS -->
    <div class="list-edit-container hide">
        <div class="utility-container hide-utilities">
            <button>Complete</button>
            <button>Edit</button>
            <button>Delete</button>
        </div>

        <div class="list-container">
            <div class="list-content">
                <input type="checkbox" id="test">
                <label for="test" contenteditable="true">List Item Content</label>
            </div>
            <button>Share</button>
            <button>Save</button>
        </div>
    </div>


</div>

<script>



</script>

<?php
$content = ob_get_clean(); // Store buffered content in a variable
$title = "ListyMcListyFace"; // Set the title for this page
include "/var/www/html/views/layout.php"; // Include the layout