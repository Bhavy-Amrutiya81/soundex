<?php
require_once '../../backend/php/db_config.php';
require_once '../../backend/php/ApplicationManager.php';

// Initialize ApplicationManager
$appManager = new ApplicationManager($pdo);

$message = '';
$messageType = '';

if ($_POST) {
    // Get form data
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $qualification = trim($_POST['qualification'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    $school = trim($_POST['school'] ?? '');
    $scholarship = trim($_POST['scholarship'] ?? '');
    $description = trim($_POST['description'] ?? '');
    
    // Validate input
    $errors = [];
    
    if (empty($name)) {
        $errors[] = 'Name is required.';
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid email is required.';
    }
    
    if (empty($phone) || strlen($phone) !== 10 || !is_numeric($phone)) {
        $errors[] = 'Phone number must be exactly 10 digits.';
    }
    
    if (empty($address)) {
        $errors[] = 'Address is required.';
    }
    
    if (empty($qualification)) {
        $errors[] = 'Qualification is required.';
    }
    
    if (empty($gender)) {
        $errors[] = 'Gender is required.';
    }
    
    if (empty($school)) {
        $errors[] = 'College/School is required.';
    }
    
    if (empty($scholarship)) {
        $errors[] = 'Scholarship status is required.';
    }
    
    if (empty($description)) {
        $errors[] = 'Self-description is required.';
    }
    
    if (empty($errors)) {
        // Prepare application data
        $applicationData = [
            'full_name' => $name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'qualification' => $qualification,
            'gender' => $gender,
            'college_school' => $school,
            'previous_scholarship' => $scholarship === 'Yes' ? 1 : 0,
            'self_description' => $description
        ];
        
        // Save application to database
        $result = $appManager->submitApplication($applicationData, []);
        
        if ($result['success']) {
            $message = 'Internship application submitted successfully!';
            $messageType = 'success';
            // Clear form after successful submission
            $_POST = array();
        } else {
            $message = $result['message'];
            $messageType = 'error';
        }
    } else {
        $message = implode('<br>', $errors);
        $messageType = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Internship Form</title>
  <style>
  *{
      margin: 0;
      padding: 0;
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
  li a:hover::before{
    
    width:90px;
  }
  .logo h1{
    
    color: black;
    display:flex;
    padding:0 600px 0 40px;
    text-transform:uppercase;
    font-family:'Times New Roman', Times, serif;
    font-size:30px;
  }
  .logo a{
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
  @media (max-width:600px) 
  {
    nav ul {
      flex-direction:row ;
    }
    nav ul li a {
      padding: 8px;
    }
    
  }

    :root {
      --primary: #2c3e50;
      --accent: #16a085;
      --light: #fdfdfd;
      --error: #e74c3c;
    }

    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, var(--primary), var(--accent));
      color: #333;
    }

    .container {
      max-width: 900px;
      margin:  auto;
      background: var(--light);
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
      animation: fadeIn 1s ease-in-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    h2 {
      text-align: center;
      color: var(--primary);
      margin-bottom: 20px;
    }

    label {
      font-weight: bold;
      margin-top: 15px;
      display: block;
    }

    input, select, textarea {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 6px;
      transition: border 0.3s ease;
    }

    input:focus, select:focus, textarea:focus {
      border-color: var(--accent);
      outline: none;
    }

    .error {
      color: var(--error);
      font-size: 0.9em;
      margin-top: 5px;
    }

    button {
      margin-top: 25px;
      padding: 12px 20px;
      background-color: var(--accent);
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: 0.3s ease;
    }

    button:hover {
      background-color: var(--primary);
    }

    @media (max-width: 600px) {
      .container {
        padding: 20px;
      }
    }
  </style>
</head>
<body>
  <nav>
        
        
  <ul>
     <div class="logo"><a href="about.php"><h1>Soun<p>Dex</p></h1></a></div>
   
     
   <li><a href="home.php">Home</a></li>
   <li><a href="Gallery.php">Gallery</a></li>
   <li><a href="faqs.php">FAQs</a></li>
   <li><a href="services.php">Services</a></li>
   <li><a href="contact us.php">Contact</a></li>
   <li><a href="about.php">About</a></li>
   
  </ul>
     
  
</nav>
  <div class="container">
    <h2>Repair Speakers Internship Form By Soundex</h2>
    
    <?php if ($message): ?>
      <div style="padding: 10px; margin-bottom: 15px; border-radius: 4px; <?php echo $messageType === 'error' ? 'background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;' : 'background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb;'; ?>">
        <?php echo $message; ?>
      </div>
    <?php endif; ?>
    
    <form method="POST">
      <label>Name</label>
      <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" required />

      <label>E-mail</label>
      <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required />
      <div id="emailError" class="error"></div>

      <label>Phone Number</label>
      <input type="tel" name="phone" id="phone" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>" required />
      <div id="phoneError" class="error"></div>

      <label>Address</label>
      <textarea name="address" id="address" rows="3" required><?php echo htmlspecialchars($_POST['address'] ?? ''); ?></textarea>

      <label>Qualification</label>
      <input type="text" name="qualification" id="qualification" value="<?php echo htmlspecialchars($_POST['qualification'] ?? ''); ?>" required />

      <label>Gender</label>
      <select name="gender" id="gender" required>
        <option value="">--Select--</option>
        <option value="Male" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
        <option value="Female" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
        <option value="Other" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Other') ? 'selected' : ''; ?>>Other</option>
      </select>

      <label>College / School</label>
      <input type="text" name="school" id="school" value="<?php echo htmlspecialchars($_POST['school'] ?? ''); ?>" required />

      <label>Have you received any scholarship?</label>
      <select name="scholarship" id="scholarship" required>
        <option value="">--Select--</option>
        <option value="Yes" <?php echo (isset($_POST['scholarship']) && $_POST['scholarship'] === 'Yes') ? 'selected' : ''; ?>>Yes</option>
        <option value="No" <?php echo (isset($_POST['scholarship']) && $_POST['scholarship'] === 'No') ? 'selected' : ''; ?>>No</option>
      </select>

      <label>Describe Yourself</label>
      <textarea name="description" id="description" rows="4" required><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>

      <button type="submit">Submit</button>
    </form>
  </div>

  <script>
    document.querySelector("form").addEventListener("submit", function (e) {
      let valid = true;
      const email = document.querySelector("[name='email']").value;
      const phone = document.querySelector("[name='phone']").value;

      // Reset errors
      document.getElementById("emailError").textContent = "";
      document.getElementById("phoneError").textContent = "";

      // Email validation
      if (!email.includes("@") || email.length < 5) {
        document.getElementById("emailError").textContent = "Please enter a valid email.";
        valid = false;
      }

      // Phone validation
      if (phone.length !== 10 || isNaN(phone)) {
        document.getElementById("phoneError").textContent = "Phone number must be exactly 10 digits.";
        valid = false;
      }

      if (!valid) {
        e.preventDefault();
      }
    });
  </script>
</body>
</html>