<?php
include 'style/header.php'; // Include your header
include 'style/navbar.php'; // Include your navbar
include 'config/database.php'; // Include database connection

// Handle registration form submission
if (isset($_POST['register'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Hash the password before saving it
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user data into the database
    $query = "INSERT INTO users (first_name, last_name, email, pass, role_id, created_at)
              VALUES (:firstname, :lastname, :email, :password,
                      (SELECT id FROM roles WHERE role_name = :role LIMIT 1), NOW())";
    
    $stmt = $db_conn->prepare($query);
    $stmt->bindParam(':firstname', $firstname);
    $stmt->bindParam(':lastname', $lastname);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->bindParam(':role', $role);

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful! Please log in.'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Registration failed. Please try again.');</script>";
    }
}
?>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6 text-success glow-box p-4">
      <h2 class="text-center mb-4">User Registration</h2>
      <form action="register.php" method="POST">
        <!-- First Name & Last Name -->
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="firstname" class="form-label">First Name</label>
            <input type="text" class="form-control" name="firstname" required>
          </div>
          <div class="col-md-6">
            <label for="lastname" class="form-label">Last Name</label>
            <input type="text" class="form-control" name="lastname" required>
          </div>
        </div>

        <!-- Email -->
        <div class="mb-3">
          <label for="email" class="form-label">Email Address</label>
          <input type="email" class="form-control" name="email" required>
        </div>

        <!-- Password -->
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" name="password" required>
        </div>

        <!-- Role -->
        <div class="mb-3">
          <label for="role" class="form-label">Select Role</label>
          <select class="form-select" name="role" required>
            <option value="Student">Student</option>
            <option value="Teacher">Teacher</option>
          </select>
        </div>
        <p>Already have an account?<a href="login.php">Login</a></p>

        <div class="d-grid">
          <button type="submit" class="btn btn-primary" name="register">Register</button>
        </div>
      </form>
    </div>
  </div>
</div>

<style>
  .glow-box {
    box-shadow: 0 0 15px 3px rgba(0, 255, 0, 0.7);  /* Green glow effect */
    border-radius: 10px;
    transition: box-shadow 0.3s ease-in-out;
  }

  .glow-box:hover {
    box-shadow: 0 0 30px 5px rgba(0, 255, 0, 1);  /* Stronger glow effect on hover */
  }
</style>

<?php include 'style/footer.php'; ?>
