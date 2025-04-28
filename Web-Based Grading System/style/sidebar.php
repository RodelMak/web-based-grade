<?php
// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Debugging: Check session variables
var_dump($_SESSION); // Remove this after debugging

// Optional: Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit;
}
?>

<style>
/* Sidebar styles */
.sidebar {
  position: fixed;
  top: 0;
  left: 0;
  height: 100vh;
  width: 250px;
  background-color: #0d6efd;
  color: white;
  overflow-x: hidden;
  transform: translateX(0);
  transition: transform 0.3s ease-in-out;
  z-index: 1000;
  padding-top: 60px;
}

.sidebar.hidden {
  transform: translateX(-100%);
}

#main-content {
  margin-left: 250px;
  transition: margin-left 0.3s ease-in-out;
}

.sidebar.hidden + #main-content {
  margin-left: 0;
}

.toggle-btn {
  position: fixed;
  top: 15px;
  left: 15px;
  z-index: 1100;
  background-color: #0d6efd;
  color: white;
  border: none;
  padding: 8px 12px;
  border-radius: 5px;
  font-size: 18px;
  cursor: pointer;
}
</style>

<!-- Toggle Button -->
<button class="toggle-btn" onclick="toggleSidebar()">☰</button>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
  <!-- User Information at the top of the sidebar -->
  <div class="sidebar-header text-center">
    <h4 class="px-3"><?= ucfirst($_SESSION['firstname']) ?> <?= ucfirst($_SESSION['lastname']) ?></h4>
    <p class="px-3"><?= ucfirst($_SESSION['role']) ?></p>

  </div>

  <ul class="nav flex-column px-3">
    <li class="nav-item">
      <a href="#" class="nav-link text-white">Dashboard</a>
    </li>


<!-- Teacher-specific Links -->
<?php if ($_SESSION['role'] == 'Teacher'): ?>

<!-- Dropdown Menu for Teacher -->
<li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle text-white" href="/teacher/add_student.php" role="button" data-bs-toggle="dropdown" aria-expanded="false">
    Students
  </a>
  <ul class="dropdown-menu">
    <li><a class="dropdown-item" href="../add_student.php">Add Students</a></li>
    <li><a class="dropdown-item" href="../view_student.php">View Students</a></li>
  </ul>
</li>

<!-- Dropdown Menu for Test, Grade, Result -->
<li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle text-white" href="../teacher/add_student.php" role="button" data-bs-toggle="dropdown" aria-expanded="false">
    Test, Grade, Result
  </a>
  <ul class="dropdown-menu">
    <li><a class="dropdown-item" href="login.php">Test Type</a></li>
    <li><a class="dropdown-item" href="register.php">Test</a></li>
    <li><a class="dropdown-item" href="register.php">Grade View</a></li>
  </ul>
</li>

<!-- Dropdown Menu for Subject -->
<li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle text-white" href="../teacher/add_student.php" role="button" data-bs-toggle="dropdown" aria-expanded="false">
    Subject
  </a>
  <ul class="dropdown-menu">
    <li><a class="dropdown-item" href="login.php">Add Subject</a></li>
    <li><a class="dropdown-item" href="register.php">View Subject</a></li>
  </ul>
</li>


<?php endif; ?>

<!-- Student-specific Links -->
<?php if ($_SESSION['role'] == 'Student'): ?>
  
  <li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
    Students
  </a>
  <ul class="dropdown-menu">
    <li><a class="dropdown-item" href="">Add Students</a></li>
    <li><a class="dropdown-item" href="../teacher/view_ student.php">View Students</a></li>
  </ul>
</li>

<!-- Dropdown Menu for Test, Grade, Result -->
<li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle text-white" href="../teacher/add_student.php" role="button" data-bs-toggle="dropdown" aria-expanded="false">
    Test, Grade, Result
  </a>
  <ul class="dropdown-menu">
    <li><a class="dropdown-item" href="login.php">Test Type</a></li>
    <li><a class="dropdown-item" href="register.php">Test</a></li>
    <li><a class="dropdown-item" href="register.php">Grade View</a></li>
  </ul>
</li>

<?php endif; ?>

    <!-- Logout option (accessible for all roles) -->
    <li class="nav-item">
      <a href="logout.php" class="nav-link text-danger btn btn-danger">Logout</a>
    </li>
  </ul>
</div>

<script>
function toggleSidebar() {
  const sidebar = document.getElementById('sidebar');
  sidebar.classList.toggle('hidden');
}
</script>
