<?php
session_start(); // Start the session
include('config/database.php'); // Database connection
include('session_helper.php'); // Include the session helper to manage sessions

if (isset($_POST['register'])) {

    // Sanitize user inputs
    $first_name = htmlspecialchars($_POST['firstname']);
    $last_name = htmlspecialchars($_POST['lastname']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    $role_name = $_POST['role'];  // 'Student' or 'Teacher'

    // Hash the password before saving it
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Step 1: Check if the email already exists in the `users` table
    $check_email_sql = "SELECT id FROM users WHERE email = :email";
    $check_email_stmt = $db_conn->prepare($check_email_sql);
    $check_email_stmt->execute([':email' => $email]);
    $existing_user = $check_email_stmt->fetchColumn();

    if ($existing_user) {
        // If email exists, show an error and prevent further registration
        echo "<script>alert('Email is already taken. Please use a different email address.'); window.history.back();</script>";
        exit;  // Stop further execution of the script
    }

    // Step 2: Check if the role exists in the `roles` table, and insert if not
    $check_role_sql = "SELECT id FROM roles WHERE role_name = :role_name";
    $check_role_stmt = $db_conn->prepare($check_role_sql);
    $check_role_stmt->execute([':role_name' => $role_name]);
    $role_id = $check_role_stmt->fetchColumn();

    // If role doesn't exist, insert it
    if (!$role_id) {
        // Insert 'Student' or 'Teacher' into the roles table
        $insert_role_sql = "INSERT INTO roles (role_name) VALUES (:role_name)";
        $insert_role_stmt = $db_conn->prepare($insert_role_sql);
        if ($insert_role_stmt->execute([':role_name' => $role_name])) {
            // Fetch the new role_id after inserting it
            $role_id = $db_conn->lastInsertId();
        } else {
            // If insertion fails, show an error
            echo "<script>alert('Error: Failed to insert role.'); window.history.back();</script>";
            exit;
        }
    }

    // Step 3: Insert the user data into the `users` table
    $reg_query = "INSERT INTO users (first_name, last_name, email, pass, role_id)
                  VALUES (:first_name, :last_name, :email, :pass, :role_id)";
    
    $query_run = $db_conn->prepare($reg_query);
    $query_exe = $query_run->execute([ 
        ":first_name" => $first_name,
        ":last_name" => $last_name,
        ":email" => $email,
        ":pass" => $hashed_password,
        ":role_id" => $role_id // Insert the correct role_id here
    ]);

    if ($query_exe) {
        // After successful user registration, fetch the user's data
        $user_id = $db_conn->lastInsertId(); // Get the user_id of the newly inserted user

        // Initialize session data
        $_SESSION['user_id'] = $user_id;
        $_SESSION['role'] = $role_name;
        $_SESSION['email'] = $email;
        $_SESSION['password'] = $password;  // You may choose to store the hashed password instead
        $_SESSION['firstname'] = $first_name;
        $_SESSION['lastname'] = $last_name;

        // Redirect based on the role
        if ($role_name === 'Student') {
            echo "<script>alert('User Created Successfully!'); window.location.href='student_dashboard.php';</script>";
        } elseif ($role_name === 'Teacher') {
            echo "<script>alert('User Created Successfully!'); window.location.href='teacher_dashboard.php';</script>";
        }
    } else {
        echo "<script>alert('Error occurred. Please try again later.'); window.history.back();</script>";
    }
}
?>
