<?php
require_once '../../backend/php/db_config.php';
require_once '../../backend/php/ApplicationManager.php';

// Start session for header logic
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$username = $isLoggedIn ? $_SESSION['username'] : '';

// Initialize ApplicationManager
$applicationManager = new ApplicationManager($pdo);

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Process the device submission form
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $phone = trim($_POST['phone']);
  $address = trim($_POST['address']);
  $deviceName = trim($_POST['deviceName']);
  $brand = trim($_POST['brand']);
  $serial = trim($_POST['serial']);
  $billProof = trim($_POST['billProof']);
  $description = trim($_POST['description']);

  // Get selected problems
  $problems = $_POST['problems'] ?? [];

  // Validation
  if (
    empty($name) || empty($email) || empty($phone) || empty($address) ||
    empty($deviceName) || empty($brand) || empty($serial) || empty($billProof) || empty($description)
  ) {
    $message = 'All fields are required.';
    $messageType = 'error';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $message = 'Please enter a valid email address.';
    $messageType = 'error';
  } elseif (strlen($phone) != 10 || !is_numeric($phone)) {
    $message = 'Phone number must be exactly 10 digits.';
    $messageType = 'error';
  } else {
    // Prepare application data for device selling
    $applicationData = [
      'full_name' => $name,
      'email' => $email,
      'phone' => $phone,
      'address' => $address,
      'qualification' => 'Device Selling Request', // Using qualification field for purpose
      'gender' => 'N/A', // Not applicable for device selling
      'college_school' => $deviceName, // Using college_school field for device name
      'previous_scholarship' => 0, // Not applicable
      'self_description' => $description . ' | Brand: ' . $brand . ' | Serial: ' . $serial . ' | Bill Proof: ' . $billProof,
    ];

    // Process file uploads
    $uploadedFiles = [];
    if (isset($_FILES['photos']) && $_FILES['photos']['error'][0] !== UPLOAD_ERR_NO_FILE) {
      $uploadDir = '../../backend/php/uploads/applications/';
      if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
      }

      $fileCount = count($_FILES['photos']['name']);
      for ($i = 0; $i < $fileCount; $i++) {
        if ($_FILES['photos']['error'][$i] === UPLOAD_ERR_OK) {
          $fileName = uniqid() . '_' . basename($_FILES['photos']['name'][$i]);
          $filePath = $uploadDir . $fileName;

          if (move_uploaded_file($_FILES['photos']['tmp_name'][$i], $filePath)) {
            $uploadedFiles[] = [
              'name' => $_FILES['photos']['name'][$i],
              'path' => $filePath,
              'size' => $_FILES['photos']['size'][$i]
            ];
          }
        }
      }
    }

    // Submit device selling request (using application system)
    $result = $applicationManager->submitApplication($applicationData, $uploadedFiles);

    if ($result['success']) {
      $message = 'Device submission received successfully! We will contact you shortly.';
      $messageType = 'success';
      // Clear form after successful submission
      $name = $email = $phone = $address = $deviceName = $serial = $description = '';
    } else {
      $message = $result['message'];
      $messageType = 'error';
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Soundex Device Submission</title>
  <link rel="stylesheet" href="../css/header.css">
  <link rel="stylesheet" href="../css/sell.css">
</head>

<body>
  <!-- Navigation Header -->
  <nav>
    <ul>
      <div class="logo"><a href="../pages/about.php">
          <h1>Soun<p>Dex</p>
          </h1>
        </a></div>
      <li><a href="../pages/home.php">Home</a></li>
      <li><a href="../pages/Gallery.php">Gallery</a></li>
      <li><a href="../pages/faqs.php">FAQs</a></li>
      <li><a href="../pages/services.php">Services</a></li>
      <li><a href="../pages/contact us.php">Contact</a></li>
      <li><a href="../pages/about.php">About</a></li>
      <?php if ($isLoggedIn): ?>
        <li><a href="../pages/history.php">History</a></li>
        <li><a href="#" style="color: #0077cc; font-weight: bold;"><?php echo htmlspecialchars($username); ?></a></li>
        <li><a href="../logout.php">Logout</a></li>
      <?php else: ?>
        <li><a href="../pages/login.php">Login</a></li>
        <li><a href="../pages/signup.php">Sign Up</a></li>
      <?php endif; ?>
      <li><a href="../pages/checkout.php" class="cart-icon" id="cartIcon">
          🛒
          <span class="cart-count" id="cartCount">0</span>
        </a></li>
    </ul>
  </nav>

  <div class="container">
    <h2>Sell Your Device</h2>
    <p style="text-align: center; color: #666; margin-bottom: 30px;">Fill out the form below to get a quote for your
      used audio equipment.</p>

    <?php if ($message): ?>
      <div class="message <?php echo $messageType; ?>">
        <?php echo htmlspecialchars($message); ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="" id="deviceForm" enctype="multipart/form-data">
      <div class="form-group">
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>"
          required />
      </div>

      <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>"
          required />
      </div>

      <div class="form-group">
        <label for="phone">Phone Number</label>
        <input type="tel" id="phone" name="phone" value="<?php echo isset($phone) ? htmlspecialchars($phone) : ''; ?>"
          required />
      </div>

      <div class="form-group">
        <label for="address">Address</label>
        <textarea id="address" name="address"
          required><?php echo isset($address) ? htmlspecialchars($address) : ''; ?></textarea>
      </div>

      <div class="form-group">
        <label for="deviceName">Device Name / Model</label>
        <input type="text" id="deviceName" name="deviceName"
          value="<?php echo isset($deviceName) ? htmlspecialchars($deviceName) : ''; ?>" required />
      </div>

      <div class="form-group">
        <label for="brand">Brand</label>
        <select id="brand" name="brand" required>
          <option value="">-- Select Brand --</option>
          <option value="Apple" <?php echo (isset($brand) && $brand == 'Apple') ? 'selected' : ''; ?>>Apple</option>
          <option value="Samsung" <?php echo (isset($brand) && $brand == 'Samsung') ? 'selected' : ''; ?>>Samsung</option>
          <option value="Sony" <?php echo (isset($brand) && $brand == 'Sony') ? 'selected' : ''; ?>>Sony</option>
          <option value="JBL" <?php echo (isset($brand) && $brand == 'JBL') ? 'selected' : ''; ?>>JBL</option>
          <option value="Bose" <?php echo (isset($brand) && $brand == 'Bose') ? 'selected' : ''; ?>>Bose</option>
          <option value="Marshall" <?php echo (isset($brand) && $brand == 'Marshall') ? 'selected' : ''; ?>>Marshall
          </option>
          <option value="Other" <?php echo (isset($brand) && $brand == 'Other') ? 'selected' : ''; ?>>Other</option>
        </select>
      </div>

      <div class="form-group">
        <label for="serial">Serial Number</label>
        <input type="text" id="serial" name="serial"
          value="<?php echo isset($serial) ? htmlspecialchars($serial) : ''; ?>" required />
      </div>

      <div class="form-group">
        <label for="photos">Upload Device Photos (Min 5)</label>
        <input type="file" id="photos" name="photos[]" multiple accept="image/*" required />
        <small style="color: #666; display: block; margin-top: 5px;">Clear photos from all angles help us assess the
          value better.</small>
      </div>

      <div class="form-group">
        <label>Check any issues with the device:</label>
        <div class="checkbox-group">
          <label><input type="checkbox" name="problems[]" value="Screen Issue" <?php echo (isset($problems) && in_array('Screen Issue', $problems)) ? 'checked' : ''; ?> /> Screen/Display</label>
          <label><input type="checkbox" name="problems[]" value="Battery Problem" <?php echo (isset($problems) && in_array('Battery Problem', $problems)) ? 'checked' : ''; ?> /> Battery/Charging</label>
          <label><input type="checkbox" name="problems[]" value="Audio Issue" <?php echo (isset($problems) && in_array('Audio Issue', $problems)) ? 'checked' : ''; ?> /> Audio/Speaker</label>
          <label><input type="checkbox" name="problems[]" value="Physical Damage" <?php echo (isset($problems) && in_array('Physical Damage', $problems)) ? 'checked' : ''; ?> /> Physical Damage</label>
          <label><input type="checkbox" name="problems[]" value="Connectivity" <?php echo (isset($problems) && in_array('Connectivity', $problems)) ? 'checked' : ''; ?> /> Connectivity</label>
          <label><input type="checkbox" name="problems[]" value="None" <?php echo (isset($problems) && in_array('None', $problems)) ? 'checked' : ''; ?> /> No Issues</label>
        </div>
      </div>

      <div class="form-group">
        <label>Do you have original accessories?</label>
        <select id="billProof" name="billProof" required>
          <option value="">-- Select --</option>
          <option value="Bill only" <?php echo (isset($billProof) && $billProof == 'Bill only') ? 'selected' : ''; ?>>Bill
            only</option>
          <option value="Box only" <?php echo (isset($billProof) && $billProof == 'Box only') ? 'selected' : ''; ?>>Box
            only</option>
          <option value="Bill and Box" <?php echo (isset($billProof) && $billProof == 'Bill and Box') ? 'selected' : ''; ?>>Bill and Box</option>
          <option value="None" <?php echo (isset($billProof) && $billProof == 'None') ? 'selected' : ''; ?>>None</option>
        </select>
      </div>

      <div class="form-group">
        <label for="description">Additional Condition Details</label>
        <textarea id="description" name="description"
          placeholder="Please describe any scratches, dents, or functional issues..."
          required><?php echo isset($description) ? htmlspecialchars($description) : ''; ?></textarea>
      </div>

      <button type="submit">Submit for Valuation</button>
    </form>
  </div>

  <script>
    document.getElementById("deviceForm").addEventListener("submit", function (e) {
      const photoCount = document.getElementById("photos").files.length;
      if (photoCount < 5) {
        e.preventDefault();
        alert("Please upload at least 5 images of your device.");
        return;
      }

      // Form validation
      const requiredFields = document.querySelectorAll('input[required], select[required], textarea[required]');
      let isValid = true;

      requiredFields.forEach(field => {
        if (!field.value.trim()) {
          isValid = false;
          field.style.borderColor = '#e74c3c';
        } else {
          field.style.borderColor = '#ddd'; // Reset to default var
        }
      });

      if (!isValid) {
        e.preventDefault();
        alert('Please fill in all required fields.');
      }
    });

    // Real-time validation feedback
    const inputs = document.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
      input.addEventListener('blur', function () {
        if (this.hasAttribute('required') && !this.value.trim()) {
          this.style.borderColor = '#e74c3c';
        } else if (this.value.trim()) {
          this.style.borderColor = '#27ae60';
        }
      });

      input.addEventListener('focus', function () {
        this.style.borderColor = '#4b6cb7';
      });
    });

    // Cart count update
    function updateCartCount() {
      const cart = JSON.parse(localStorage.getItem('cart')) || [];
      const totalItems = cart.reduce((total, item) => total + (item.quantity || 1), 0);
      const cartCountElement = document.getElementById('cartCount');
      const cartIconElement = document.getElementById('cartIcon');

      if (cartCountElement) {
        cartCountElement.textContent = totalItems;

        // Add/remove empty class based on cart status
        if (totalItems > 0) {
          cartIconElement.classList.remove('empty');
        } else {
          cartIconElement.classList.add('empty');
        }
      }
    }

    // Update on load
    document.addEventListener('DOMContentLoaded', updateCartCount);
  </script>
</body>

</html>