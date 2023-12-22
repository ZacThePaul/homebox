<?php

function initializeDatabase() {
    $db = new PDO('sqlite:/var/www/html/usm-service/usm.db');

    // Create a table
    $db->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY,
        name TEXT NOT NULL,
        role TEXT NOT NULL,
        password TEXT NOT NULL
    )");

    // Insert a user
    $stmt = $db->prepare("INSERT INTO users (name, role, password) VALUES (:name, :role, :password)");
    $stmt->bindValue(':name', 'tim', SQLITE3_TEXT);
    $stmt->bindValue(':role', 'administrator', SQLITE3_TEXT);
    $stmt->bindValue(':password', password_hash('password123', PASSWORD_DEFAULT), SQLITE3_TEXT);
    $stmt->execute();
}
function getAllUsers() {
    $db = new PDO('sqlite:/var/www/html/usm-service/usm.db');

    $result = $db->query('SELECT * FROM users');

    foreach ($result as $row) {
        echo "Name: " . $row['name'] . ", Role: " . $row['role'] . ", Password: " . $row['password'] . "<br/>";
    }
}
function dumpAllRows() {
    try {
        $db = new PDO('sqlite:/var/www/html/usm-service/usm.db');
        $result = $db->query('SELECT * FROM users');

        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Name</th><th>Role</th><th>Password</th></tr>";
        foreach ($result as $row) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['role'] . "</td>";
            echo "<td>" . $row['password'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }
}

function deleteAllUsers() {
    try {
        $db = new PDO('sqlite:/var/www/html/usm-service/usm.db');
        $result = $db->exec('DELETE FROM users');

        echo "All users deleted successfully.";
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }
}
