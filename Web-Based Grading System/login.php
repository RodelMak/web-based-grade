<?php
session_start();
include 'style/header.php'; 
include 'style/navbar.php'; 
include 'config/database.php'; 

// Handle login form submission
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT users.id, users.pass, users.first_name, users.last_name, users.role_id, roles.role_name
              FROM users
              JOIN roles ON users.role_id = roles.id
              WHERE users.email = :email LIMIT 1";
    $stmt = $db_conn->prepare($query);
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['pass'])) {
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role_name'];
        $_SESSION['role_id'] = $user['role_id'];
        $_SESSION['firstname'] = $user['first_name'];
        $_SESSION['lastname'] = $user['last_name'];
        $_SESSION['email'] = $email;
        $_SESSION['password'] = $user['pass'];

        // Redirect based on role
        if ($user['role_name'] == 'Teacher') {
            header('Location: teacher_dashboard.php');
            exit;
        } elseif ($user['role_name'] == 'Student') {
            header('Location: student_dashboard.php');
            exit;
        } else {
            echo "<script>alert('Invalid role. Please contact admin.');</script>";
        }
    } else {
        echo "<script>alert('Invalid credentials. Please try again.');</script>";
    }
}

// Check if session is missing necessary fields and prompt user
if (isset($_SESSION['missing_fields'])) {
    $missing_fields = implode(', ', $_SESSION['missing_fields']);
    echo "<script>alert('Oops! You are missing session data: $missing_fields. Please log in again.');</script>";
    unset($_SESSION['missing_fields']); // Clear the session data after showing the alert
}

?>

<!-- Login Form -->
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6 text-success glow-box p-4">
      <h2 class="text-center mb-4">User Login</h2>
      <form action="login.php" method="POST">
        <!-- Email -->
        <div class="mb-3">
          <label for="email" class="form-label text-success">Email address</label>
          <input type="email" class="form-control" name="email" required>
        </div>

        <!-- Password -->
        <div class="mb-3">
          <label for="password" class="form-label text-success">Password</label>
          <input type="password" class="form-control" name="password" required>
        </div>

        <p>Don't have an account?<a href="register.php">Register</a></p>

        <div class="d-grid">
          <button type="submit" class="btn btn-primary" name="login">Login</button>
        </div>
      </form>
    </div>
  </div>
</div>

<style>
  /* Glow effect for the login form container */
  .glow-box {
    box-shadow: 0 0 15px 3px rgba(0, 255, 0, 0.7);  /* Green glow effect */
    border-radius: 10px;
    transition: box-shadow 0.3s ease-in-out;
    padding: 20px;  /* Padding inside the glow box */
  }

  /* Stronger glow effect when hovered */
  .glow-box:hover {
    box-shadow: 0 0 30px 5px rgba(0, 255, 0, 1);  /* Stronger glow effect on hover */
  }
</style>

<?php include 'style/footer.php'; ?>
