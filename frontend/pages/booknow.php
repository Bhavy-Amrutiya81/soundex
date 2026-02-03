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
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $deviceType = trim($_POST['device-type'] ?? '');
    $brand = trim($_POST['brand'] ?? '');
    $problem = trim($_POST['problem'] ?? '');
    $preferredDate = trim($_POST['preferred-date'] ?? '');
    $preferredTime = trim($_POST['preferred-time'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $terms = isset($_POST['terms']);
    
    // Validate input
    $errors = [];
    
    if (empty($name)) {
        $errors[] = 'Full name is required.';
    }
    
    if (empty($phone)) {
        $errors[] = 'Phone number is required.';
    } elseif (!preg_match('/^(\+91[\-\s]?)?[0-9]{10}$/', $phone)) {
        $errors[] = 'Please enter a valid phone number.';
    }
    
    if (empty($email)) {
        $errors[] = 'Email address is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }
    
    if (empty($address)) {
        $errors[] = 'Complete address is required.';
    }
    
    if (empty($deviceType)) {
        $errors[] = 'Device type is required.';
    }
    
    if (empty($problem)) {
        $errors[] = 'Problem description is required.';
    }
    
    if (empty($preferredDate)) {
        $errors[] = 'Preferred date is required.';
    }
    
    if (empty($preferredTime)) {
        $errors[] = 'Preferred time is required.';
    }
    
    if (!$terms) {
        $errors[] = 'You must agree to the Terms & Conditions and Privacy Policy.';
    }
    
    if (empty($errors)) {
        // Prepare application data
        $applicationData = [
            'full_name' => $name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'qualification' => 'Repair Booking', // Using qualification field for service type
            'gender' => $deviceType, // Using gender field for device type
            'college_school' => $brand, // Using college_school field for brand
            'previous_scholarship' => 'No', // Using this field to indicate if this is a repair booking
            'self_description' => "Device Type: $deviceType, Brand: $brand, Problem: $problem, Preferred Date: $preferredDate, Preferred Time: $preferredTime, Location: $location"
        ];
        
        // Save application to database
        $result = $appManager->submitApplication($applicationData, []);
        
        if ($result['success']) {
            $message = 'Thank you! Your repair appointment has been scheduled. We will contact you shortly to confirm the details.';
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Repair Service - Soundex</title>
    <link rel="stylesheet" href="../CSS/header.css">
    <link rel="stylesheet" href="../CSS/gallary.css">
    <style>
        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f0;
            color: #333;
            line-height: 1.6;
        }
        
        /* Main container with proper spacing for fixed header */
        .main-container {
            padding: 120px 0 40px 0;
            min-height: calc(100vh - 160px);
        }
        
        /* Booking section container */
        .booking-section {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Booking form container */
        .booking-container {
            max-width: 650px;
            margin: 0 auto;
            padding: 40px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        
        /* Form title styling */
        .form-header {
            margin-bottom: 30px;
        }
        
        .form-header h1 {
            color: #1a1a2e;
            font-size: 2.2rem;
            margin-bottom: 10px;
            font-weight: 700;
        }
        
        .form-header p {
            color: #666;
            font-size: 1.1rem;
        }
        
        /* Form styling */
        .booking-form {
            text-align: left;
            margin-top: 20px;
        }
        
        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .form-group {
            flex: 1;
            margin-bottom: 20px;
        }
        
        .form-group.full-width {
            flex: 1 0 100%;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 0.95rem;
        }
        
        .required {
            color: #e74c3c;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 14px;
            border: 2px solid #e1e1e1;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
            background-color: #fafafa;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #3498db;
            background-color: white;
        }
        
        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }
        
        /* Checkbox styling */
        .checkbox-group {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin: 25px 0;
        }
        
        .checkbox-group input[type="checkbox"] {
            width: auto;
            margin-top: 4px;
        }
        
        .checkbox-group label {
            font-weight: normal;
            margin-bottom: 0;
            color: #555;
        }
        
        .checkbox-group a {
            color: #3498db;
            text-decoration: none;
        }
        
        .checkbox-group a:hover {
            text-decoration: underline;
        }
        
        /* Submit button */
        .submit-btn {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            padding: 16px 30px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
            margin-top: 10px;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        }
        
        .submit-btn:hover {
            background: linear-gradient(135deg, #2980b9, #1f618d);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(52, 152, 219, 0.4);
        }
        
        .submit-btn:active {
            transform: translateY(0);
        }
        
        /* Responsive design */
        @media (max-width: 768px) {
            .booking-container {
                padding: 30px 20px;
                margin: 0 15px;
            }
            
            .form-header h1 {
                font-size: 1.8rem;
            }
            
            .form-row {
                flex-direction: column;
                gap: 0;
            }
            
            .submit-btn {
                font-size: 16px;
                padding: 14px 25px;
            }
        }
        
        @media (max-width: 480px) {
            .booking-container {
                padding: 25px 15px;
            }
            
            .form-header h1 {
                font-size: 1.5rem;
            }
            
            .form-group input,
            .form-group select,
            .form-group textarea {
                padding: 12px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <!-- Fixed Navigation Header -->
    <nav>
        <ul>
            <div class="logo"><a href="../pages/about.php"><h1>Soun<p>Dex</p></h1></a></div>
            <li><a href="../pages/home.php">Home</a></li>
            <li><a href="../pages/Gallery.php">Gallery</a></li>
            <li><a href="../pages/faqs.php">FAQs</a></li>
            <li><a href="../pages/services.php">Services</a></li>
            <li><a href="../pages/contact us.php">Contact</a></li>
            <li><a href="../pages/about.php">About</a></li>
        </ul>
    </nav>
    
    <!-- Main Content Container -->
    <main style="padding-top: 100px;">
    <div class="main-container">
        <div class="booking-section">
            <div class="booking-container">
                <div class="form-header">
                    <h1>Book Your Repair Service</h1>
                    <p>Fill in your details below to schedule a repair appointment with our experts</p>
                </div>
                
                <?php if ($message): ?>
                  <div style="padding: 15px; margin-bottom: 20px; border-radius: 8px; <?php echo $messageType === 'error' ? 'background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;' : 'background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb;'; ?>">
                    <?php echo $message; ?>
                  </div>
                <?php endif; ?>
                
                <form class="booking-form" method="POST" action="">
                    <!-- Personal Information -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Full Name <span class="required">*</span></label>
                            <input type="text" id="name" name="name" placeholder="Enter your full name" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Phone Number <span class="required">*</span></label>
                            <input type="tel" id="phone" name="phone" placeholder="+91 XXXXX XXXXX" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address <span class="required">*</span></label>
                        <input type="email" id="email" name="email" placeholder="your.email@example.com" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="address">Complete Address <span class="required">*</span></label>
                        <textarea id="address" name="address" placeholder="Enter your complete address including city and postal code" required><?php echo htmlspecialchars($_POST['address'] ?? ''); ?></textarea>
                    </div>
                    
                    <!-- Device Information -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="device-type">Device Type <span class="required">*</span></label>
                            <select id="device-type" name="device-type" required>
                                <option value="">Select device type</option>
                                <option value="speaker" <?php echo (isset($_POST['device-type']) && $_POST['device-type'] === 'speaker') ? 'selected' : ''; ?>>Speaker</option>
                                <option value="headphones" <?php echo (isset($_POST['device-type']) && $_POST['device-type'] === 'headphones') ? 'selected' : ''; ?>>Headphones/Earphones</option>
                                <option value="microphone" <?php echo (isset($_POST['device-type']) && $_POST['device-type'] === 'microphone') ? 'selected' : ''; ?>>Microphone</option>
                                <option value="amplifier" <?php echo (isset($_POST['device-type']) && $_POST['device-type'] === 'amplifier') ? 'selected' : ''; ?>>Amplifier</option>
                                <option value="soundbar" <?php echo (isset($_POST['device-type']) && $_POST['device-type'] === 'soundbar') ? 'selected' : ''; ?>>Soundbar</option>
                                <option value="other" <?php echo (isset($_POST['device-type']) && $_POST['device-type'] === 'other') ? 'selected' : ''; ?>>Other Audio Device</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="brand">Brand & Model</label>
                            <input type="text" id="brand" name="brand" placeholder="e.g., JBL Flip 5, Sony WH-1000XM4" value="<?php echo htmlspecialchars($_POST['brand'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="problem">Describe the Problem <span class="required">*</span></label>
                        <textarea id="problem" name="problem" placeholder="Please describe the issue with your device in detail. Include any error messages, symptoms, or when the problem started." required><?php echo htmlspecialchars($_POST['problem'] ?? ''); ?></textarea>
                    </div>
                    
                    <!-- Scheduling -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="preferred-date">Preferred Date <span class="required">*</span></label>
                            <input type="date" id="preferred-date" name="preferred-date" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="preferred-time">Preferred Time <span class="required">*</span></label>
                            <select id="preferred-time" name="preferred-time" required>
                                <option value="">Select time slot</option>
                                <option value="morning" <?php echo (isset($_POST['preferred-time']) && $_POST['preferred-time'] === 'morning') ? 'selected' : ''; ?>>Morning (9:00 AM - 12:00 PM)</option>
                                <option value="afternoon" <?php echo (isset($_POST['preferred-time']) && $_POST['preferred-time'] === 'afternoon') ? 'selected' : ''; ?>>Afternoon (12:00 PM - 4:00 PM)</option>
                                <option value="evening" <?php echo (isset($_POST['preferred-time']) && $_POST['preferred-time'] === 'evening') ? 'selected' : ''; ?>>Evening (4:00 PM - 7:00 PM)</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="location">Service Location Preference</label>
                        <select id="location" name="location">
                            <option value="onsite" <?php echo (isset($_POST['location']) && $_POST['location'] === 'onsite') ? 'selected' : ''; ?>>At My Place (On-site Technician Visit)</option>
                            <option value="store" <?php echo (isset($_POST['location']) && $_POST['location'] === 'store') ? 'selected' : ''; ?>>At Store (Drop-off Service)</option>
                            <option value="both" <?php echo (isset($_POST['location']) && $_POST['location'] === 'both') ? 'selected' : ''; ?>>Flexible - Either option works</option>
                        </select>
                    </div>
                    
                    <!-- Terms and Conditions -->
                    <div class="checkbox-group">
                        <input type="checkbox" id="terms" name="terms" <?php echo isset($_POST['terms']) ? 'checked' : ''; ?> required>
                        <label for="terms">I agree to the <a href="#">Terms & Conditions</a> and <a href="#">Privacy Policy</a> <span class="required">*</span></label>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" class="submit-btn">Schedule Repair Appointment</button>
                </form>
            </div>
        </div>
    </div>
    </main>
    
    <script>
        // Set minimum date to today
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('preferred-date').setAttribute('min', today);
            
            // Form validation and submission
            const form = document.querySelector('.booking-form');
            form.addEventListener('submit', function(e) {
                // The server-side validation will handle this, but client-side validation can still be useful
            });
            
            // Real-time validation feedback
            const inputs = form.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    if (this.hasAttribute('required') && !this.value.trim()) {
                        this.style.borderColor = '#e74c3c';
                    } else if (this.value.trim()) {
                        this.style.borderColor = '#27ae60';
                    }
                });
                
                input.addEventListener('focus', function() {
                    this.style.borderColor = '#3498db';
                });
            });
        });
    </script>
</body>
</html>