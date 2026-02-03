<?php
require_once 'db_config.php';

class UserManager
{
    private $pdo;

    public function __construct($database)
    {
        $this->pdo = $database;
    }

    // Register new user
    public function registerUser($username, $email, $password, $firstName = '', $lastName = '', $phone = '', $address = '')
    {
        try {
            // Store password in plain text as requested (NOT RECOMMENDED FOR PRODUCTION)
            $hashedPassword = $password;

            $stmt = $this->pdo->prepare("INSERT INTO users (username, email, password, first_name, last_name, phone, address, role) VALUES (?, ?, ?, ?, ?, ?, ?, 'user')");
            $result = $stmt->execute([$username, $email, $hashedPassword, $firstName, $lastName, $phone, $address]);

            if ($result) {
                return ['success' => true, 'message' => 'User registered successfully', 'user_id' => $this->pdo->lastInsertId()];
            } else {
                return ['success' => false, 'message' => 'Registration failed'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    // Login user
    public function loginUser($username, $password, $guestSessionId = null)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT id, username, email, password, first_name, last_name, role FROM users WHERE username = ? AND is_active = TRUE");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify password using plain text comparison
            if ($user && $password === $user['password']) {
                // Create session
                $sessionToken = bin2hex(random_bytes(32));
                $expiresAt = date('Y-m-d H:i:s', strtotime('+24 hours'));

                $stmt = $this->pdo->prepare("INSERT INTO user_sessions (user_id, session_token, expires_at) VALUES (?, ?, ?)");
                $stmt->execute([$user['id'], $sessionToken, $expiresAt]);

                // If user had a guest cart, merge it with their account
                if ($guestSessionId) {
                    $stmt = $this->pdo->prepare("UPDATE cart SET user_id = ? WHERE session_id = ? AND user_id IS NULL");
                    $stmt->execute([$user['id'], $guestSessionId]);
                }

                return [
                    'success' => true,
                    'message' => 'Login successful',
                    'user' => $user,
                    'session_token' => $sessionToken
                ];
            } else {
                return ['success' => false, 'message' => 'Invalid credentials'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    // Validate session
    public function validateSession($sessionToken)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT u.*, s.expires_at FROM user_sessions s JOIN users u ON s.user_id = u.id WHERE s.session_token = ? AND s.expires_at > NOW()");
            $stmt->execute([$sessionToken]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Logout user
    public function logoutUser($sessionToken)
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM user_sessions WHERE session_token = ?");
            return $stmt->execute([$sessionToken]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Check if user is admin
    public function isAdmin($sessionToken)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT u.role FROM user_sessions s JOIN users u ON s.user_id = u.id WHERE s.session_token = ? AND s.expires_at > NOW()");
            $stmt->execute([$sessionToken]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            return $user && $user['role'] === 'admin';
        } catch (PDOException $e) {
            return false;
        }
    }

    // Get user purchase history
    public function getUserPurchaseHistory($userId)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT o.*, oi.quantity, oi.unit_price, p.name as product_name
                FROM orders o
                LEFT JOIN order_items oi ON o.id = oi.order_id
                LEFT JOIN products p ON oi.product_id = p.id
                WHERE o.user_id = ?
                ORDER BY o.created_at DESC
            ");
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Get all users (admin only)
    public function getAllUsers($currentUserToken)
    {
        if (!$this->isAdmin($currentUserToken)) {
            return ['success' => false, 'message' => 'Access denied'];
        }

        try {
            $stmt = $this->pdo->prepare("SELECT id, username, email, first_name, last_name, role, created_at, is_active FROM users ORDER BY created_at DESC");
            $stmt->execute();
            return ['success' => true, 'users' => $stmt->fetchAll(PDO::FETCH_ASSOC)];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }
}