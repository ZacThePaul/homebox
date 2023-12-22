<?php

class UserController {

    public function __construct() {
    }

    static function index() {

        $current_user = UserController::getUser($_SESSION["user_email"]);

        // if there is a logged in user and the user has the correct permissions
        if ($current_user && $current_user["policy"] == 'UserAccess') {

            try {
                $db = new PDO($_ENV['PATH_TO_USM_DB']);
            } catch (PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
            }
    
            try {
                $stmt = $db->prepare("SELECT * FROM users");
                $stmt->execute();
                echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            } catch (PDOException $e) {
                echo 'Fetch users failed: ' . $e->getMessage();
                return [];
            }
        } else {
            echo json_encode([false]);
        }

    }

    static function create() {

        $name = $_POST['name'];
        $username = str_replace(' ', '', strtolower($_POST['username']));
        $email = $_POST['email'];
        $password = $_POST['password'];

        try {
            $db = new PDO($_ENV['PATH_TO_USM_DB']);
            $stmt = $db->prepare("INSERT INTO users (name, username, email, password) VALUES (:name, :username, :email, :password)");
            $stmt->execute([
                ':name'     => $name, 
                ':username' => $username,
                ':email'    => $email,
                ':password' => password_hash($password, PASSWORD_DEFAULT) // Hash the password for security
            ]);
            echo "User added successfully.";
            header('Location: ' . '/usm');
        } catch (PDOException $e) {
            echo 'Add user failed: ' . $e->getMessage();
        }
    }

    static function login() {
    
        $email = $_POST['email'];
        $password = $_POST['password'];
    
        try {
            $db = new PDO($_ENV['PATH_TO_USM_DB']);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->execute([':email' => $email]);
    
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($user) {
                if (password_verify($password, $user['password'])) {
                    // Password is correct, set the session variables
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_name'] = $user['name'];
                    echo "Login successful.";
                    header('Location: ' . '/dashboard');
                    exit; // Ensure script stops after header redirect
                } else {
                    echo "Invalid email or password.";
                }
            } else {
                echo "Invalid email or password.";
            }
        } catch (PDOException $e) {
            echo 'Login failed: ' . $e->getMessage();
        }
    }        

    static function logout() {

        // Start the session if it's not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    
        // Unset all of the session variables.
        $_SESSION = array();

        // If it's desired to kill the session, also delete the session cookie.
        // Note: This will destroy the session, and not just the session data!
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
    
        // Finally, destroy the session.
        session_destroy();
    
        // Redirect to login page or home page
        header('Location: /');
        exit; // Ensure script stops after header redirect
    }

    static function getUser($email) {

        try {
            $db = new PDO($_ENV['PATH_TO_USM_DB']);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->execute([':email' => $email]);
    
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $user; // Return the user data
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
            return null; // Return null in case of error
        }
        
    }

    static function showProfile() {
        include '/var/www/html/views/dashboard.php';
    }

    static function showRegister() {
        // include __DIR__ .'/../views/register.php';
        views('auth/register.php');
    }

    static function showLogin() {
        // include __DIR__ .'/../views/login.php';
        views('auth/login.php');
    }

}