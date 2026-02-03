<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'db_config.php';
require_once 'UserManager.php';
require_once 'ProductManager.php';
require_once 'ServiceManager.php';
require_once 'ApplicationManager.php';
require_once 'ContactManager.php';

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Initialize managers
$userManager = new UserManager($pdo);
$productManager = new ProductManager($pdo);
$serviceManager = new ServiceManager($pdo);
$applicationManager = new ApplicationManager($pdo);
$contactManager = new ContactManager($pdo);

// Get request data
$input = json_decode(file_get_contents('php://input'), true);
$action = $_GET['action'] ?? $_POST['action'] ?? '';

try {
    switch($action) {
        
        // User Management
        case 'register':
            $result = $userManager->registerUser(
                $input['username'],
                $input['email'],
                $input['password'],
                $input['first_name'] ?? '',
                $input['last_name'] ?? '',
                $input['phone'] ?? '',
                $input['address'] ?? ''
            );
            echo json_encode($result);
            break;
            
        case 'login':
            $result = $userManager->loginUser(
                $input['username'], 
                $input['password'],
                $input['guest_session_id'] ?? null
            );
            echo json_encode($result);
            break;
            
        case 'validate_session':
            $result = $userManager->validateSession($input['session_token']);
            echo json_encode(['valid' => $result !== false, 'user' => $result]);
            break;
            
        case 'logout':
            $result = $userManager->logoutUser($input['session_token']);
            echo json_encode(['success' => $result]);
            break;
            
        case 'is_admin':
            $result = $userManager->isAdmin($input['session_token']);
            echo json_encode(['is_admin' => $result]);
            break;
            
        case 'get_purchase_history':
            $result = $userManager->getUserPurchaseHistory($input['user_id']);
            echo json_encode(['success' => true, 'purchase_history' => $result]);
            break;
            
        case 'get_all_users':
            $result = $userManager->getAllUsers($input['session_token']);
            echo json_encode($result);
            break;
            
        // Product Management
        case 'get_products':
            $result = $productManager->getAllProducts();
            echo json_encode(['success' => true, 'products' => $result]);
            break;
            
        case 'add_to_cart':
            $result = $productManager->addToCart(
                $input['user_id'] ?? null,
                $input['session_id'],
                $input['product_id'],
                $input['quantity'] ?? 1
            );
            echo json_encode(['success' => $result]);
            break;
            
        case 'get_cart':
            $result = $productManager->getCartItems(
                $input['user_id'] ?? null,
                $input['session_id']
            );
            echo json_encode(['success' => true, 'cart_items' => $result]);
            break;
            
        case 'remove_from_cart':
            $result = $productManager->removeFromCart(
                $input['cart_id'],
                $input['user_id'] ?? null,
                $input['session_id']
            );
            echo json_encode(['success' => $result]);
            break;
            
        case 'create_order':
            // Check if user is authenticated before creating order
            if (!isset($input['user_id']) || !$input['user_id']) {
                echo json_encode(['success' => false, 'message' => 'Authentication required to place an order']);
                break;
            }
            
            // Get cart items first
            $cartItems = $productManager->getCartItems(
                $input['user_id'] ?? null,
                $input['session_id']
            );
            
            $result = $productManager->createOrder(
                $input['user_id'] ?? null,
                $cartItems,
                $input['shipping_address'],
                $input['billing_address'],
                $input['payment_method']
            );
            echo json_encode($result);
            break;
            
        // Service Booking
        case 'book_service':
            $result = $serviceManager->bookService($input);
            echo json_encode($result);
            break;
            
        case 'get_services':
            $result = $serviceManager->getAllServices();
            echo json_encode(['success' => true, 'services' => $result]);
            break;
            
        // Application Management
        case 'submit_application':
            // Handle file uploads
            $uploadedFiles = [];
            if (!empty($_FILES)) {
                $uploadDir = 'uploads/applications/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                foreach ($_FILES as $file) {
                    if ($file['error'] === UPLOAD_ERR_OK) {
                        $fileName = uniqid() . '_' . basename($file['name']);
                        $filePath = $uploadDir . $fileName;
                        
                        if (move_uploaded_file($file['tmp_name'], $filePath)) {
                            $uploadedFiles[] = [
                                'name' => $file['name'],
                                'path' => $filePath,
                                'size' => $file['size']
                            ];
                        }
                    }
                }
            }
            
            $result = $applicationManager->submitApplication($input, $uploadedFiles);
            echo json_encode($result);
            break;
            
        case 'get_applications':
            $result = $applicationManager->getAllApplications($input['status'] ?? null);
            echo json_encode(['success' => true, 'applications' => $result]);
            break;
            
        // Contact Management
        case 'send_message':
            $result = $contactManager->submitMessage(
                $input['name'],
                $input['email'],
                $input['subject'] ?? '',
                $input['message']
            );
            echo json_encode($result);
            break;
            
        case 'get_messages':
            $result = $contactManager->getAllMessages($input['status'] ?? null);
            echo json_encode(['success' => true, 'messages' => $result]);
            break;
            
        case 'submit_faq_feedback':
            $result = $contactManager->submitFAQFeedback(
                $input['faq_id'],
                $input['helpful'],
                $input['user_id'] ?? null,
                $_SERVER['REMOTE_ADDR'] ?? null
            );
            echo json_encode($result);
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
            break;
    }
    
} catch(Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}