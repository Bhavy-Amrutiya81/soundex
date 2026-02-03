<?php
// This is a simple static page that doesn't require database connections
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Navigation Bar</title>
  <style>
    /* Basic Styling for the navigation bar */
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }

    nav {
      background-color: #333;
      overflow: hidden;
    }

    nav ul {
      list-style-type: none;
      margin: 0;
      padding: 0;
      display: flex;
    }

    nav ul li {
      display: inline;
    }

    nav ul li a {
      display: block;
      color: white;
      padding: 14px 20px;
      text-align: center;
      text-decoration: none;
    }

    nav ul li a:hover {
      background-color: #575757;
    }

    /* Responsive Design */
    @media (max-width: 600px) {
      nav ul {
        flex-direction: column;
      }
      nav ul li a {
        padding: 10px;
      }
    }
  </style>
</head>
<body>

  <nav>
    <ul>
      <li><a href="../pages/home.php">Home</a></li>
      <li><a href="../pages/about.php">About</a></li>
      <li><a href="../pages/services.php">Services</a></li>
      <li><a href="../pages/contact us.php">Contact</a></li>
    </ul>
  </nav>

</body>
</html>