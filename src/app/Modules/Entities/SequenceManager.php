<?php
declare(strict_types=1);

namespace App\Modules\Entities;

use App\App;
use App\DB;
use Exception;
use PDO;

class SequenceManager {
    private DB $conn;
    private const SEQUENCE_NAME = 'client_sequence';
    
    public function __construct(DB $conn)
    {
        $this->conn = $conn;
    }
    
    public function getNextSequenceNumber(): int {
        try {
            $this->conn->beginTransaction();
            
            // Get or create sequence record
            $stmt = $this->conn->prepare("
                INSERT INTO sequences (name, current_value)
                VALUES (:sequence_name, 1)
                ON DUPLICATE KEY UPDATE current_value = current_value + 1
            ");
            
            $stmt->execute([':sequence_name' => self::SEQUENCE_NAME]);
            
            // Get the updated sequence value
            $stmt = $this->conn->prepare("
                SELECT current_value 
                FROM sequences 
                WHERE name = :sequence_name
                FOR UPDATE
            ");
            
            $stmt->execute([':sequence_name' => self::SEQUENCE_NAME]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $this->conn->commit();
            return (int) $result['current_value'];
            
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw new Exception("Failed to generate sequence number: " . $e->getMessage());
        }
    }
}