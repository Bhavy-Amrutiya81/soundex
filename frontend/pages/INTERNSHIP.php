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
        $errors[] = 'Description is required.';
    }
    
    // Handle file uploads
    if (!isset($_FILES['photos']) || count($_FILES['photos']['name']) < 5) {
        $errors[] = 'Please upload at least 5 photos.';
    }
    
    if (empty($errors)) {
        // Process file uploads
        $uploadedPhotos = [];
        $uploadDir = '../../uploads/internship/';
        
        // Create directory if it doesn't exist
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        // Process each uploaded file
        for ($i = 0; $i < count($_FILES['photos']['name']); $i++) {
            if ($_FILES['photos']['error'][$i] === UPLOAD_ERR_OK) {
                $fileName = basename($_FILES['photos']['name'][$i]);
                $fileTmpName = $_FILES['photos']['tmp_name'][$i];
                $fileSize = $_FILES['photos']['size'][$i];
                $fileType = $_FILES['photos']['type'][$i];
                
                // Validate file type
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
                if (in_array($fileType, $allowedTypes)) {
                    $newFileName = uniqid() . '_' . $fileName;
                    $destination = $uploadDir . $newFileName;
                    
                    if (move_uploaded_file($fileTmpName, $destination)) {
                        $uploadedPhotos[] = $destination;
                    } else {
                        $errors[] = 'Failed to upload photo: ' . $fileName;
                    }
                } else {
                    $errors[] = 'Invalid file type for: ' . $fileName . '. Only JPG, PNG, GIF allowed.';
                }
            }
        }
        
        if (empty($errors) && count($uploadedPhotos) >= 5) {
            // Prepare application data
            $applicationData = [
                'full_name' => $name,
                'email' => $email,
                'phone' => $phone,
                'address' => $address,
                'qualification' => $qualification,
                'gender' => $gender,
                'college_school' => $school,
                'previous_scholarship' => $scholarship,
                'self_description' => $description
            ];
            
            // Prepare file data
            $fileData = [];
            foreach ($uploadedPhotos as $photoPath) {
                $fileData[] = [
                    'name' => basename($photoPath),
                    'path' => $photoPath,
                    'size' => filesize($photoPath)
                ];
            }
            
            // Save application to database
            $result = $appManager->submitApplication($applicationData, $fileData);
            
            if ($result['success']) {
                $message = 'Application submitted successfully!';
                $messageType = 'success';
                // Clear form after successful submission
                $_POST = array();
            } else {
                $message = $result['message'];
                $messageType = 'error';
            }
        } else {
            $message = 'Failed to upload required number of photos.';
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
  <title>Scholarship Form</title>
  <style>
    :root {
      --primary: #34495e;
      --accent: #1abc9c;
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
      margin: 40px auto;
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
      transition: background 0.3s ease;
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
  <div class="container">
    <h2>Scholarship Eligibility Form</h2>
    
    <?php if ($message): ?>
      <div style="padding: 10px; margin-bottom: 15px; border-radius: 4px; <?php echo $messageType === 'error' ? 'background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;' : 'background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb;'; ?>">
        <?php echo $message; ?>
      </div>
    <?php endif; ?>
    
    <form method="POST" enctype="multipart/form-data">
      <label>Name</label>
      <input type="text" name="name" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" required />

      <label>E-mail</label>
      <input type="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required />
      <div id="emailError" class="error"></div>

      <label>Phone Number</label>
      <input type="tel" name="phone" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>" required />
      <div id="phoneError" class="error"></div>

      <label>Address</label>
      <textarea name="address" rows="3" required><?php echo htmlspecialchars($_POST['address'] ?? ''); ?></textarea>

      <label>Qualification</label>
      <input type="text" name="qualification" value="<?php echo htmlspecialchars($_POST['qualification'] ?? ''); ?>" required />

      <label>Gender</label>
      <select name="gender" required>
        <option value="">--Select--</option>
        <option value="Male" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
        <option value="Female" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
        <option value="Other" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Other') ? 'selected' : ''; ?>>Other</option>
      </select>

      <label>College / School</label>
      <input type="text" name="school" value="<?php echo htmlspecialchars($_POST['school'] ?? ''); ?>" required />

      <label>Have you received any scholarship?</label>
      <select name="scholarship" required>
        <option value="">--Select--</option>
        <option value="Yes" <?php echo (isset($_POST['scholarship']) && $_POST['scholarship'] === 'Yes') ? 'selected' : ''; ?>>Yes</option>
        <option value="No" <?php echo (isset($_POST['scholarship']) && $_POST['scholarship'] === 'No') ? 'selected' : ''; ?>>No</option>
      </select>

      <label>Describe Yourself</label>
      <textarea name="description" rows="4" required><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>

      <label>Upload Your Photos (Minimum 5)</label>
      <input type="file" name="photos[]" multiple accept="image/*" required />
      <div id="photoError" class="error"></div>

      <button type="submit">Submit</button>
    </form>
  </div>

  <script>
    document.querySelector("form").addEventListener("submit", function (e) {
      let valid = true;
      const email = document.querySelector("[name='email']").value;
      const phone = document.querySelector("[name='phone']").value;
      const photosInput = document.querySelector("[name='photos[]']");
      const photos = photosInput.files;

      // Reset errors
      document.getElementById("emailError").textContent = "";
      document.getElementById("phoneError").textContent = "";
      document.getElementById("photoError").textContent = "";

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

      // Photo validation
      if (photos.length < 5) {
        document.getElementById("photoError").textContent = "Please upload at least 5 images.";
        valid = false;
      }

      if (!valid) {
        e.preventDefault();
      }
    });
  </script>
</body>
</html>