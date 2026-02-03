<?php
require_once 'db_config.php';

class ContactManager {
    private $pdo;
    
    public function __construct($database) {
        $this->pdo = $database;
    }
    
    // Submit contact message
    public function submitMessage($name, $email, $subject, $message) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO contact_messages (name, email, subject, message) 
                VALUES (?, ?, ?, ?)
            ");
            
            $result = $stmt->execute([$name, $email, $subject, $message]);
            
            if ($result) {
                return [
                    'success' => true, 
                    'message' => 'Message sent successfully', 
                    'message_id' => $this->pdo->lastInsertId()
                ];
            } else {
                return ['success' => false, 'message' => 'Failed to send message'];
            }
        } catch(PDOException $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }
    
    // Get all contact messages
    public function getAllMessages($status = null) {
        try {
            $sql = "SELECT * FROM contact_messages";
            $params = [];
            
            if ($status) {
                $sql .= " WHERE status = ?";
                $params[] = $status;
            }
            
            $sql .= " ORDER BY created_at DESC";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return [];
        }
    }
    
    // Get message by ID
    public function getMessageById($messageId) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM contact_messages WHERE id = ?");
            $stmt->execute([$messageId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return false;
        }
    }
    
    // Update message status
    public function updateMessageStatus($messageId, $status) {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE contact_messages 
                SET status = ?, replied_at = NOW() 
                WHERE id = ?
            ");
            return $stmt->execute([$status, $messageId]);
        } catch(PDOException $e) {
            return false;
        }
    }
    
    // Get contact statistics
    public function getStatistics() {
        try {
            $stats = [];
            
            // Total messages
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM contact_messages");
            $stmt->execute();
            $stats['total'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Messages by status
            $stmt = $this->pdo->prepare("SELECT status, COUNT(*) as count FROM contact_messages GROUP BY status");
            $stmt->execute();
            $stats['by_status'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Recent messages
            $stmt = $this->pdo->prepare("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5");
            $stmt->execute();
            $stats['recent'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $stats;
        } catch(PDOException $e) {
            return [];
        }
    }
    
    // Submit FAQ feedback
    public function submitFAQFeedback($faqId, $helpful, $userId = null, $ipAddress = null) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO faq_feedback (faq_id, helpful, user_id, ip_address) 
                VALUES (?, ?, ?, ?)
            ");
            
            $result = $stmt->execute([$faqId, $helpful, $userId, $ipAddress]);
            
            if ($result) {
                return ['success' => true, 'message' => 'Feedback recorded'];
            } else {
                return ['success' => false, 'message' => 'Failed to record feedback'];
            }
        } catch(PDOException $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }
    
    // Get FAQ feedback statistics
    public function getFAQFeedbackStats($faqId = null) {
        try {
            $sql = "SELECT faq_id, SUM(helpful) as helpful_count, COUNT(*) as total_count FROM faq_feedback";
            $params = [];
            
            if ($faqId) {
                $sql .= " WHERE faq_id = ?";
                $params[] = $faqId;
            }
            
            $sql .= " GROUP BY faq_id";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return [];
        }
    }
}
?>