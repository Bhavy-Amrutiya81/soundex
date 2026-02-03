<?php
session_start();
require_once '../../backend/php/db_config.php';
require_once '../../backend/php/UserManager.php';

// Initialize UserManager
$userManager = new UserManager($pdo);

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$username = $isLoggedIn ? $_SESSION['username'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link rel="stylesheet" href="../css/header.css" />
    <style>
        /* Global Styles */
 * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}
body {
    font-family: Arial, sans-serif;
    
    background-color:beige;
    color: #333;
}
nav {
    background-color:#fff;
    overflow: hidden;
    position: fixed;
    display: flex;
    z-index: 2;
    width: 100%;
    
  }

  nav ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: right;
    
  }

 
  nav ul li a {
    
    color:black;
    padding: 14px 30px;
    text-align: center;
    text-transform: uppercase;
    text-decoration:none;
    font-weight:bolder;
    font-size: 90%;
  
  
    
  }
  /*nav span{
    position: absolute;
    top:0;
    left: 0;
    width:100px;
    height:100%;
    justify-items: auto;
    background: linear-gradient(45deg,red,blue);
    border-radius: 10px;
    transition: 0.5s;
  }*/
  
  li a::before{
    content:'';
    position: absolute;
    bottom:-2px;
    width: 0;   
    height:7px;
    background-color:steelblue;
    transition:all 0.5s ;
  }
  li a:hover::before
  {
    width:90px;
  }
  .logo h1
  {  
    color: black;
    display:flex;
    padding: 20px 40px;
    text-transform:uppercase;
    font-family:'Times New Roman', Times, serif;
    font-size: 30px;
  }

  @media screen and (max-width: 1024px) and (min-width: 769px) {
    .logo h1 {
      padding: 15px 30px;
      font-size: 26px;
    }
  }
 .logo a
 {
  text-decoration: none;
  }


.logo p{
  color:cornflowerblue;
  font-weight: 600;
  font-family:'Times New Roman', Times, serif;

}
  /*.navbar{
    background-color: white;
    font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    
    
    position: fixed;
    width: 100%;
    z-index: 2;
  }
  .navdiv{
    display: flex;
    align-items: center;
    justify-content:space-between ;
  }
  .logo{
    font-size: 30px;
    color:#fff;
    font-weight: 600;
  }
  li{
    list-style: none;
    display:inline-block;

  }
  li a{
    color:black;
    font-size:20px;
    font-weight: bold;
    margin-right: 20px;
    text-decoration:none;
  }*/
  @media screen and (max-width: 768px) and (min-width: 601px) {
  nav {
    flex-wrap: wrap;
  }
  nav ul {
    flex-wrap: wrap;
    justify-content: center;
  }
  nav ul li a {
    padding: 10px 15px;
    font-size: 80%;
  }
  .logo h1 {
    padding: 15px 20px;
    font-size: 24px;
  }
}

@media (max-width:600px) 
  
  {
    nav ul {
      flex-direction:row ;
    }
    nav ul li a {
      padding: 8px;
    }
    
  }


/* Header Styles */


/* About Us Section */
.about-us {
    background-color:beige;
    padding: 40px 0;
}

.about-us .container {
    
    width: 80%;
    margin: 0 auto;
}

.about-us h2 {
    font-size: 2em;
    margin-bottom: 20px;
}

.about-us p {
    font-size: 1.1em;
    line-height: 1.8;
    color: #666;
}

/* Team Section */
.team {
    background-color:beige;
     padding: 40px 0;
}

.team .container {
    width: 80%;
    margin: 0 auto;
}

.team h2 {
    font-size: 2em;
    margin-bottom: 20px;
    text-align: center;
}

.team-members {
    background-color:beige;
    display: flex;
    justify-content: space-around;
    margin-right: 70px;
    margin-top: 20px;
}

.member {
    background-color:beige;
    text-align: center;
    max-width: 250px;
}

.member img {
    width: 100%;
    border-radius: 50%;
    margin-bottom: 15px;
}

.member h3 {
    font-size: 1.5em;
    margin-bottom: 5px;
}

.member p {
    font-size: 1.1em;
    color: #666;
}

/* Contact Section */
.contact {
    background-color:beige;
   
    padding: 40px 0;
}

.contact .container {
    background-color:beige;
    width: 80%;
    margin: 0 auto;
    text-align: center;
}

.contact h2 {
    background-color:beige;
    font-size: 2em;
    margin-bottom: 20px;
}

.contact p {
    background-color:beige;
    font-size: 1.1em;
    margin: 10px 0;
}

.contact a {
    background-color:beige;
    color: #333;
    text-decoration: none;
}

.contact a:hover {
    text-decoration: underline;
}

/* Footer Styles */
footer {
    background-color:beige;
    color: rgb(17, 0, 0);
    padding: 10px 0;
    text-align: center;
}

footer p {
    font-size: 1em;
}

    </style>
</head>
<body>
    <!-- Navigation Header -->
    <nav>
        <ul>
            <div class="logo"><a href="../pages/about.php"><h1>Soun<p>Dex</p></h1></a></div>
            <li><a href="../pages/home.php">Home</a></li>
            <li><a href="../pages/Gallery.php">Gallery</a></li>
            <li><a href="../pages/faqs.php">FAQs</a></li>
            <li><a href="../pages/services.php">Services</a></li>
            <li><a href="../pages/contact us.php">Contact</a></li>
            <li><a href="../pages/about.php" class="active">About</a></li>
            <?php if ($isLoggedIn): ?>
            <li><a href="#" style="color: #0077cc; font-weight: bold;"><?php echo htmlspecialchars($username); ?></a></li>
            <li><a href="../logout.php">Logout</a></li>
            <?php else: ?>
            <li><a href="../pages/login.php">Login</a></li>
            <li><a href="../pages/signup.php">Sign Up</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <section class="about-us">
        <div class="container">
            <h2>About Our Company</h2>
            <p>Soundex contributes towards selling, repairing and exchanging speakers and also provides a way to learn about repairing speakers. </p>
        </div>
    </section>

    <section class="team">
        <div class="container">
            <h2>Meet Our Team</h2>
            <div class="team-members">
                <div class="member">

                    <h3>Amrutiya Bhavy</h3>
                    <p>Team Leader</p>
                </div>
                <div class="member">
                   
                    <h3>Babriya Dhruv</h3>
                    <p>Team member1</p>
                </div>
                <div class="member">
                
                    <h3>Bhatt Parv</h3>
                    <p>Team member2</p>
                </div>
            </div>
        </div>
    </section>

    <section class="contact">
        <div class="container">
            <h2>Contact Us</h2>
            <p>If you have any questions, feel free to reach out to us!</p>
            <p>Email: <a href="mailto:Soundex6@gmail.com.com">Soundex6@gmail.com</a></p>
            <p>Phone: +91 1234567890</p>
        </div>
    </section>

    <footer>
        <div class="container">
            <p>&copy; 2025 Our Company. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>