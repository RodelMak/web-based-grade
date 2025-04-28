<?php
// Include the session helper which handles session data
include 'session_helper.php';

// Initialize and check the session data for the user
restoreUserSession();  // Automatically loads user data into the session from the database
checkUserSession();    // Ensures the session is complete and the role is valid for a Student

// Fetch student-specific data (e.g., grades and subjects) from the database
include 'config/database.php';

// Sample data - This should be replaced with actual queries to fetch data
$grades = [85, 90, 78]; // Placeholder for grades data fetched from the database
$subjects = ['CCIM', 'CSDS', 'CSAC']; // Placeholder for subjects data fetched from the database
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= $pageTitle ?? 'Web Grading System' ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
    }
  </style>
</head>
<body>

<!-- Include the sidebar -->
<?php include 'style/sidebar.php'; ?>

<!-- Main content wrapper -->
<div id="main-content" class="p-5 text-success">
  <div class="d-flex justify-content-between align-items-center">
    <h2>Welcome, <?= $_SESSION['firstname'] . ' ' . $_SESSION['lastname']; ?> 👨‍🎓</h2>
    <a href="logout.php" class="btn btn-danger">Logout</a>
  </div>
  <p>This is your Student dashboard. From here, you can manage student grades and view assigned subjects.</p>

  <!-- Dashboard cards -->
  <div class="mt-5 row g-4">
    <div class="col-md-6">
      <div class="card shadow">
        <div class="card-body">
          <h5 class="card-title">Grade Management</h5>
          <p class="card-text">Enter and review student performance in each subject.</p>
          <a href="grades.php" class="btn btn-primary">Go to Grades</a> <!-- Link to grades page -->
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card shadow">
        <div class="card-body">
          <h5 class="card-title">Subjects</h5>
          <p class="card-text">View and manage the subjects you're assigned to.</p>
          <a href="subjects.php" class="btn btn-primary">View Subjects</a> <!-- Link to subjects page -->
        </div>
      </div>
    </div>
  </div>

  <!-- Chart -->
  <div class="mt-5">
    <h5>Class Average Grades</h5>
    <canvas id="myChart" height="100"></canvas>
  </div>
</div>

<!-- Chart script -->
<script>
  const ctx = document.getElementById('myChart').getContext('2d');
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: <?= json_encode($subjects); ?>,  // Dynamically fetch the subjects from the database
      datasets: [{
        label: 'Average Grade',
        data: <?= json_encode($grades); ?>,  // Dynamically fetch the grades from the database
        backgroundColor: ['#0d6efd', '#198754', '#ffc107'],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true,
          max: 100
        }
      }
    }
  });
</script>

<?php include 'style/footer.php'; ?>
</body>
</html>
