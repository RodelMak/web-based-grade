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
  <h4 class="px-3"><?= ucfirst($_SESSION['role']) ?> Panel</h4>
  <ul class="nav flex-column px-3">
    <li class="nav-item">
      <a href="#" class="nav-link text-white">Dashboard</a>
    </li>
    
    <!-- Check if the user is a teacher -->
    <?php if ($_SESSION['role'] == 'teacher'): ?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Students
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Login</a></li>
            <li><a class="dropdown-item" href="#">Register</a></li>
          </ul>
        </li>
    <?php elseif ($_SESSION['role'] == 'student'): ?>
      <!-- Check if the user is a student -->
      <li class="nav-item">
        <a href="#" class="nav-link text-white">My Grades</a>
      </li>
      <li class="nav-item">
        <a href="#" class="nav-link text-white">My Subjects</a>
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
