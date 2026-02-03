<?php
require_once 'db_config.php';

class ServiceManager {
    private $pdo;
    
    public function __construct($database) {
        $this->pdo = $database;
    }
    
    // Book repair service
    public function bookService($data) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO service_bookings (
                    user_id, customer_name, email, phone, device_type, device_brand, 
                    device_model, issue_description, problems, has_bill_proof, 
                    device_condition, preferred_date, preferred_time
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $problemsJson = json_encode($data['problems']);
            
            $result = $stmt->execute([
                $data['user_id'] ?? null,
                $data['customer_name'],
                $data['email'],
                $data['phone'],
                $data['device_type'],
                $data['device_brand'],
                $data['device_model'],
                $data['issue_description'],
                $problemsJson,
                $data['has_bill_proof'],
                $data['device_condition'],
                $data['preferred_date'],
                $data['preferred_time']
            ]);
            
            if ($result) {
                return [
                    'success' => true, 
                    'message' => 'Service booked successfully', 
                    'booking_id' => $this->pdo->lastInsertId()
                ];
            } else {
                return ['success' => false, 'message' => 'Booking failed'];
            }
        } catch(PDOException $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }
    
    // Get all services
    public function getAllServices() {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM services WHERE is_active = TRUE ORDER BY name");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return [];
        }
    }
    
    // Get booking by ID
    public function getBookingById($bookingId) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT sb.*, u.username, u.email as user_email 
                FROM service_bookings sb 
                LEFT JOIN users u ON sb.user_id = u.id 
                WHERE sb.id = ?
            ");
            $stmt->execute([$bookingId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return false;
        }
    }
    
    // Get user bookings
    public function getUserBookings($userId) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT * FROM service_bookings 
                WHERE user_id = ? 
                ORDER BY created_at DESC
            ");
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return [];
        }
    }
    
    // Update booking status
    public function updateBookingStatus($bookingId, $status, $notes = '') {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE service_bookings 
                SET status = ?, booking_notes = ? 
                WHERE id = ?
            ");
            return $stmt->execute([$status, $notes, $bookingId]);
        } catch(PDOException $e) {
            return false;
        }
    }
}
?>