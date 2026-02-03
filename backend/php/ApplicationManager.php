<?php
require_once 'db_config.php';

class ApplicationManager {
    private $pdo;
    
    public function __construct($database) {
        $this->pdo = $database;
    }
    
    // Submit internship/scholarship application
    public function submitApplication($data, $uploadedFiles = []) {
        try {
            $this->pdo->beginTransaction();
            
            // Insert application
            $stmt = $this->pdo->prepare("
                INSERT INTO applications (
                    full_name, email, phone, address, qualification, 
                    gender, college_school, previous_scholarship, self_description, photo_count
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $result = $stmt->execute([
                $data['full_name'],
                $data['email'],
                $data['phone'],
                $data['address'],
                $data['qualification'],
                $data['gender'],
                $data['college_school'],
                $data['previous_scholarship'] === 'Yes' ? 1 : 0,
                $data['self_description'],
                count($uploadedFiles)
            ]);
            
            if (!$result) {
                throw new Exception("Failed to insert application");
            }
            
            $applicationId = $this->pdo->lastInsertId();
            
            // Insert uploaded documents
            if (!empty($uploadedFiles)) {
                $stmt = $this->pdo->prepare("
                    INSERT INTO application_documents (application_id, file_name, file_path, file_size) 
                    VALUES (?, ?, ?, ?)
                ");
                
                foreach ($uploadedFiles as $file) {
                    $stmt->execute([
                        $applicationId,
                        $file['name'],
                        $file['path'],
                        $file['size']
                    ]);
                }
            }
            
            $this->pdo->commit();
            return [
                'success' => true, 
                'message' => 'Application submitted successfully', 
                'application_id' => $applicationId
            ];
            
        } catch(Exception $e) {
            $this->pdo->rollback();
            return ['success' => false, 'message' => 'Submission failed: ' . $e->getMessage()];
        }
    }
    
    // Get all applications
    public function getAllApplications($status = null) {
        try {
            $sql = "SELECT * FROM applications";
            $params = [];
            
            if ($status) {
                $sql .= " WHERE status = ?";
                $params[] = $status;
            }
            
            $sql .= " ORDER BY submitted_at DESC";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return [];
        }
    }
    
    // Get application by ID
    public function getApplicationById($applicationId) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT a.*, COUNT(ad.id) as document_count 
                FROM applications a 
                LEFT JOIN application_documents ad ON a.id = ad.application_id 
                WHERE a.id = ? 
                GROUP BY a.id
            ");
            $stmt->execute([$applicationId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return false;
        }
    }
    
    // Get application documents
    public function getApplicationDocuments($applicationId) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM application_documents WHERE application_id = ? ORDER BY uploaded_at");
            $stmt->execute([$applicationId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return [];
        }
    }
    
    // Update application status
    public function updateApplicationStatus($applicationId, $status, $notes = '') {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE applications 
                SET status = ?, reviewer_notes = ?, reviewed_at = NOW() 
                WHERE id = ?
            ");
            return $stmt->execute([$status, $notes, $applicationId]);
        } catch(PDOException $e) {
            return false;
        }
    }
    
    // Get application statistics
    public function getStatistics() {
        try {
            $stats = [];
            
            // Total applications
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM applications");
            $stmt->execute();
            $stats['total'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Applications by status
            $stmt = $this->pdo->prepare("SELECT status, COUNT(*) as count FROM applications GROUP BY status");
            $stmt->execute();
            $stats['by_status'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Recent applications
            $stmt = $this->pdo->prepare("SELECT * FROM applications ORDER BY submitted_at DESC LIMIT 5");
            $stmt->execute();
            $stats['recent'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $stats;
        } catch(PDOException $e) {
            return [];
        }
    }
}
?>