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
    $deviceName = trim($_POST['deviceName'] ?? '');
    $brand = trim($_POST['brand'] ?? '');
    $serial = trim($_POST['serial'] ?? '');
    $billProof = trim($_POST['billProof'] ?? '');
    $description = trim($_POST['description'] ?? '');
    
    // Get selected problems
    $problems = $_POST['problems'] ?? [];
    
    // Validate input
    $errors = [];
    
    if (empty($name)) {
        $errors[] = 'Name is required.';
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid email is required.';
    }
    
    if (empty($phone)) {
        $errors[] = 'Phone number is required.';
    }
    
    if (empty($address)) {
        $errors[] = 'Address is required.';
    }
    
    if (empty($deviceName)) {
        $errors[] = 'Device name is required.';
    }
    
    if (empty($brand)) {
        $errors[] = 'Brand selection is required.';
    }
    
    if (empty($serial)) {
        $errors[] = 'Serial number is required.';
    }
    
    if (empty($billProof)) {
        $errors[] = 'Bill proof selection is required.';
    }
    
    if (empty($description)) {
        $errors[] = 'Device condition description is required.';
    }
    
    if (empty($problems)) {
        $errors[] = 'At least one problem must be selected.';
    }
    
    // Handle file uploads
    if (!isset($_FILES['photos']) || count($_FILES['photos']['name']) < 5) {
        $errors[] = 'Please upload at least 5 photos of your device.';
    }
    
    if (empty($errors)) {
        // Process file uploads
        $uploadedPhotos = [];
        $uploadDir = '../../uploads/devices/';
        
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
                'qualification' => $deviceName, // Using qualification field for device name
                'gender' => $brand, // Using gender field for brand
                'college_school' => $serial, // Using college_school field for serial number
                'previous_scholarship' => $billProof === 'Yes' ? 1 : 0, // Using this field for bill proof
                'self_description' => "Problems: " . implode(', ', $problems) . "\nDescription: " . $description
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
                $message = 'Device submission form submitted successfully!';
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
  <title>Soundex Device Submission</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 20px;
    }
    .container {
      max-width: 800px;
      margin: auto;
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      color: #333;
    }
    label {
      display: block;
      margin-top: 15px;
      font-weight: bold;
    }
    input, select, textarea {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    input[type="file"] {
      padding: 5px;
    }
    .checkbox-group {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
    }
    .checkbox-group label {
      font-weight: normal;
    }
    button {
      margin-top: 20px;
      padding: 12px 20px;
      background-color: #0078D4;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    button:hover {
      background-color: #005fa3;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Soundex Device Submission Form</h2>
    
    <?php if ($message): ?>
      <div style="padding: 10px; margin-bottom: 15px; border-radius: 4px; <?php echo $messageType === 'error' ? 'background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;' : 'background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb;'; ?>">
        <?php echo $message; ?>
      </div>
    <?php endif; ?>
    
    <form method="POST" enctype="multipart/form-data">
      <label for="name">Name</label>
      <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" required />

      <label for="email">E-mail</label>
      <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required />

      <label for="phone">Phone Number</label>
      <input type="tel" name="phone" id="phone" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>" required />

      <label for="address">Address</label>
      <textarea name="address" id="address" rows="3" required><?php echo htmlspecialchars($_POST['address'] ?? ''); ?></textarea>

      <label for="deviceName">Device Name</label>
      <input type="text" name="deviceName" id="deviceName" value="<?php echo htmlspecialchars($_POST['deviceName'] ?? ''); ?>" required />

      <label for="brand">Select Brand Name</label>
      <select name="brand" id="brand" required>
        <option value="">--Select--</option>
        <option value="Apple" <?php echo (isset($_POST['brand']) && $_POST['brand'] === 'Apple') ? 'selected' : ''; ?>>Apple</option>
        <option value="Samsung" <?php echo (isset($_POST['brand']) && $_POST['brand'] === 'Samsung') ? 'selected' : ''; ?>>Samsung</option>
        <option value="Dell" <?php echo (isset($_POST['brand']) && $_POST['brand'] === 'Dell') ? 'selected' : ''; ?>>Dell</option>
        <option value="HP" <?php echo (isset($_POST['brand']) && $_POST['brand'] === 'HP') ? 'selected' : ''; ?>>HP</option>
        <option value="Other" <?php echo (isset($_POST['brand']) && $_POST['brand'] === 'Other') ? 'selected' : ''; ?>>Other</option>
      </select>

      <label for="serial">Serial Number</label>
      <input type="text" name="serial" id="serial" value="<?php echo htmlspecialchars($_POST['serial'] ?? ''); ?>" required />

      <label for="photos">Upload Device Photos (Minimum 5)</label>
      <input type="file" name="photos[]" id="photos" multiple accept="image/*" required />

      <label>Select Problems on Your Device</label>
      <div class="checkbox-group">
        <label><input type="checkbox" name="problems[]" value="Screen Issue" <?php echo (isset($_POST['problems']) && in_array('Screen Issue', $_POST['problems'])) ? 'checked' : ''; ?> /> Screen Issue</label>
        <label><input type="checkbox" name="problems[]" value="Battery Problem" <?php echo (isset($_POST['problems']) && in_array('Battery Problem', $_POST['problems'])) ? 'checked' : ''; ?> /> Battery Problem</label>
        <label><input type="checkbox" name="problems[]" value="Software Crash" <?php echo (isset($_POST['problems']) && in_array('Software Crash', $_POST['problems'])) ? 'checked' : ''; ?> /> Software Crash</label>
        <label><input type="checkbox" name="problems[]" value="Overheating" <?php echo (isset($_POST['problems']) && in_array('Overheating', $_POST['problems'])) ? 'checked' : ''; ?> /> Overheating</label>
        <label><input type="checkbox" name="problems[]" value="Other" <?php echo (isset($_POST['problems']) && in_array('Other', $_POST['problems'])) ? 'checked' : ''; ?> /> Other</label>
      </div>

      <label>Do you have original bill, box, or e-bill?</label>
      <select name="billProof" id="billProof" required>
        <option value="">--Select--</option>
        <option value="Yes" <?php echo (isset($_POST['billProof']) && $_POST['billProof'] === 'Yes') ? 'selected' : ''; ?>>Yes</option>
        <option value="No" <?php echo (isset($_POST['billProof']) && $_POST['billProof'] === 'No') ? 'selected' : ''; ?>>No</option>
      </select>

      <label for="description">Device Condition / Health Description</label>
      <textarea name="description" id="description" rows="4" required><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>

      <button type="submit">Submit</button>
    </form>
  </div>

  <script>
    document.querySelector("form").addEventListener("submit", function (e) {
      const photoCount = document.querySelector("[name='photos[]']").files.length;
      if (photoCount < 5) {
        e.preventDefault();
        alert("Please upload at least 5 images of your device.");
        return;
      }
    });
  </script>
</body>
</html>