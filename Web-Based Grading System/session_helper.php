<?php
include 'config/database.php';
session_start();

function initializeSessionData($user_id) {
    global $db_conn;

    // Join users, user_role, and roles to get full user info and role name
    $query = "SELECT users.id, users.first_name, users.last_name, users.email, users.role_id, roles.role_name
              FROM users
              INNER JOIN roles ON users.role_id = roles.id
              WHERE users.id = :id
              LIMIT 1";

    $stmt = $db_conn->prepare($query);

    // Check if the user_id is valid before executing
    if (empty($user_id)) {
        echo "<script>alert('Invalid user ID provided.'); window.location.href='login.php';</script>";
        exit();
    }

    try {
        $stmt->execute([':id' => $user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            // Set session variables if user found
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['firstname'] = $user['first_name'];
            $_SESSION['lastname']  = $user['last_name'];
            $_SESSION['email']     = $user['email'];
            $_SESSION['role_id']   = $user['role_id'];  // The actual role ID
            $_SESSION['role']      = $user['role_name']; // The role name (e.g., 'Teacher', 'Student')
        } else {
            // Handle invalid user (no user found)
            echo "<script>alert('User not found in database.'); window.location.href='login.php';</script>";
            exit();
        }
    } catch (PDOException $e) {
        // Handle any PDO exceptions (errors during execution)
        echo "<script>alert('Database query failed: " . $e->getMessage() . "'); window.location.href='login.php';</script>";
        exit();
    }
}

// Function to check if the user is logged in and if the session data is valid
function checkUserSession() {
    // Ensure that all session variables are set
    $missing = [];
    if (!isset($_SESSION['user_id'])) $missing[] = 'user_id';
    if (!isset($_SESSION['role'])) $missing[] = 'role';
    if (!isset($_SESSION['email'])) $missing[] = 'email';
    if (!isset($_SESSION['firstname'])) $missing[] = 'firstname';
    if (!isset($_SESSION['lastname'])) $missing[] = 'lastname';

    // If any session data is missing, prompt the user to log in again
    if (!empty($missing)) {
        // Store the missing session data in a session variable
        $_SESSION['missing_fields'] = $missing;
        // Generate a readable list of missing session variables
        $readable = array_map(function($item) {
            return ucfirst(str_replace('_', ' ', $item)); // Convert to more user-friendly format
        }, array_unique($missing)); // Remove duplicates
        $missing_fields = implode(', ', $readable); // Join missing fields into a string

        // Redirect the user to the login page with the missing data prompt
        echo "<script>alert('Oops! You are missing session data: $missing_fields. Please log in again.'); window.location.href='login.php';</script>";
        exit();
    }

    // Role validation: Ensure role is either 'Teacher' or 'Student'
    if ($_SESSION['role'] !== 'Teacher' && $_SESSION['role'] !== 'Student') {
        session_destroy(); // Destroy the session if the role is invalid
        echo "<script>alert('Invalid role. Please log in again.'); window.location.href='login.php';</script>";
        exit();
    }
}

// Function to restore user session based on their user ID
function restoreUserSession() {
    if (isset($_SESSION['user_id'])) {
        initializeSessionData($_SESSION['user_id']); // Restore session data from database
    } else {
        // If user_id is not set, prompt the user to log in again
        echo "<script>alert('Session expired. Please log in again.'); window.location.href='login.php';</script>";
        exit();
    }
}

// Function to destroy the session and log out the user
function logoutUser() {
    session_unset(); // Unset all session variables
    session_destroy(); // Destroy the session
    header("Location: login.php"); // Redirect to the login page
    exit();
}
?>
