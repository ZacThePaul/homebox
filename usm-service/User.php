<?php

// User Model
class UserModel {
    private $db;

    public function __construct() {
        try {
            $this->db = new PDO($_ENV['PATH_TO_USM_DB']);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    public function createTable() {
        try {
            $this->db->exec("CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY,
                name TEXT NOT NULL,
                username TEXT NOT NULL,
                email TEXT NOT NULL,
                password TEXT NOT NULL,
                policy TEXT DEFAULT 'UserAccess' NOT NULL
            )");
            echo "Users table created successfully.";
        } catch (PDOException $e) {
            echo 'Table creation failed: ' . $e->getMessage();
        }
    }

    function dropTable($tableName) {
        try {
            $db = new PDO($_ENV['PATH_TO_USM_DB']);
            
            // Prepare the DROP TABLE statement
            $stmt = $db->prepare("DROP TABLE IF EXISTS " . $tableName);
            
            // Execute the statement
            $stmt->execute();
    
            echo "Table '" . $tableName . "' dropped successfully.";
        } catch (PDOException $e) {
            echo 'Drop table failed: ' . $e->getMessage();
        }
    }

    public function dumpAllRows() {
        try {
            $db = new PDO($_ENV['PATH_TO_USM_DB']);
            $result = $db->query('SELECT * FROM users');
    
            echo "<table border='1'>";
            echo "<tr><th>ID</th><th>Name</th><th>Username</th><th>Email</th><th>Password</th><th>Policy</th></tr>";
            foreach ($result as $row) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['name'] . "</td>";
                echo "<td>" . $row['username'] . "</td>";
                echo "<td>" . $row['email'] . "</td>";
                echo "<td>" . $row['password'] . "</td>";
                echo "<td>" . $row['policy'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    public function getUsers() {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Fetch users failed: ' . $e->getMessage();
            return [];
        }
    }

    public function getUser($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Fetch user failed: ' . $e->getMessage();
            return null;
        }
    }

    public function addUser($name, $username, $email, $password) {
        try {
            $stmt = $this->db->prepare("INSERT INTO users (name, username, email, password) VALUES (:name, :username, :email, :password)");
            $stmt->execute([
                ':name'     => $name, 
                ':username' => $username,
                ':email'    => $email,
                ':password' => password_hash($password, PASSWORD_DEFAULT) // Hash the password for security
            ]);
            echo "User added successfully.";
        } catch (PDOException $e) {
            echo 'Add user failed: ' . $e->getMessage();
        }
    }
    
    public function deleteUser($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
            $stmt->execute([':id' => $id]);
            echo "User deleted successfully.";
        } catch (PDOException $e) {
            echo 'Delete user failed: ' . $e->getMessage();
        }
    }
    

    // Other methods related to 'users' table can be added here
    // e.g., addUser(), deleteUser(), getAllUsers(), etc.
}
