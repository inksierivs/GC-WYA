<?php
session_start();
if (!isset($_SESSION['uid'])) {
    header('Location: login.html');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard</title>
  <link rel="icon" type="image/png" href="pics/WYA-logo.png" />
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <!-- Link to external CSS -->
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>

  <!-- Sidebar -->
  <div id="sidebar" class="sidebar">
    <button onclick="toggleSidebar()">✖</button>
    <ul>
      <li><a class="fa fa-dashboard" href="dasboard.html">Dashboard</a></li>
      <li><a class="fa fa-monitor" href="class.html">Classes</a></li>
      <li><a class="fa fa-file" href="qr.html">Generate PDF</a></li>
      <li><a class="fa fa-user" href="profile.html">Profile</a></li>

      <button onclick="location.href='login.html'" class="logout-btn">
          <i class="fas fa-sign-out-alt"></i>
          <span class="nav-text">Log Out</span>
      </button>
    </ul>
  </div>

  <!-- Header -->
  <div class="header">
    <button onclick="toggleSidebar()" class="menu-btn">☰</button>
    <h1>Dashboard</h1>

  </div>

  <h1>Welcome, <?php echo htmlspecialchars($_SESSION['firstName'] ?? ''); ?> <?php echo htmlspecialchars($_SESSION['lastName'] ?? ''); ?>!</h1>
  <p>You are logged in with UID: <?php echo htmlspecialchars($_SESSION['uid']); ?></p>
  <p>Your email is: <?php echo htmlspecialchars($_SESSION['email']); ?></p>
  <!-- Main content -->
  <div class="main">
    <div class="card-container">
      <div class="card">
        <h2>0</h2>
        <p>Presents</p>
      </div>
      <div class="card">
        <h2>0</h2>
        <p>Absents</p>
      </div>
      <div class="card">
        <h2>gagawan graph</h2>
        <p>Presents</p>
      </div>
      <div class="card">
        <h2>gagawan graph</h2>
        <p>Absents</p>
      </div>
    </div>

    <div class="button-group">
      <button class="action-btn">Classes</button>
      <button class="action-btn">Generate PDF</button>
      <button class="action-btn">Profile</button>
    </div>
  </div>

  <!-- Link to external JS -->
  <script src="js/scripts.js"></script>
</body>
</html>
