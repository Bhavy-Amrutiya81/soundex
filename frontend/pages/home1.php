<?php
// This file doesn't require database connections as it's primarily static content
?>
<!DOCTYPE html>
<html lang="en">
<head>
   
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/home.css">
    
</head>
<body>
    
    <nav>
        
        
         <ul>
            <div class="logo"><a href="about.php"><h1>Soun <p>dex</p></h1></a></div><a href="about.php">
                
                
            
        
        
        
          </a><li><a href="Gallery.php">Gallery</a></li>
          <li><a href="services.php">Services</a></li>
          <li><a href="faqs.php">FAQs</a></li>
          <li><a href="contact us.php">Contact Us</a></li>
          <li><a href="about.php">About</a></li>
          
         </ul>
            
           
    </nav>
      
    <div id="main-content" class="main-content">
        <section class="new-speakers">
            <!--<h2>Buy New Speakers</h2>-->
            <div class="product">
                <img src="../../assets/images/product_gallery/1.jpg" alt="speaker 1" onerror="this.onerror=null; this.src='../../assets/images/product_gallery/default.jpg';">   
                <h2>Bluetooth Speaker</h2>
                <button> <a href="buy.php" class="buy-btn">Buy Now </a></button>
            </div>
            <div class="product">
                <img src="../../assets/images/product_gallery/2.jpg" alt="speaker 2" onerror="this.onerror=null; this.src='../../assets/images/product_gallery/default.jpg';">
                <h2>Portable Speaker</h2>
                <button class="buy-btn">Buy Now</button>
            </div>
        </section>

        <section class="repair-services">
            <h2>Repair or Sell Old Speakers & Devices</h2>
            <button class="repair-btn">Live Repair</button>
            <a href="sell.php"><button class="sell-btn">Sell Your Old Device</button></a>
        </section>

        <section class="internship">
            <h2>Free Internship</h2>
            <p>Gain experience by joining our repair team!</p>
            <a href="internship2.php"><button class="internship-btn">Apply Now</button></a>
        </section>
    </div>
   
</body>
</html>