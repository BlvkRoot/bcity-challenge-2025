<?php

declare (strict_types = 1);

namespace App\Modules\Entities;

use App\App;
use App\DB;
use Exception;
use PDO;
use PDOException;

class Client
{
    private string $clientCode;

    private static int $paddingIndex = 0;

    private DB $conn;

    private $table = 'clients';

    private SequenceManager $sequenceManager;

    private ClientContact $contact;

    public function __construct()
    {
        $this->conn            = App::db();
        $this->sequenceManager = new SequenceManager($this->conn);
        $this->contact = new ClientContact();
    }

    public function save(string $name): bool | string
    {
        $query = "INSERT INTO {$this->table} (name, client_code) VALUES (:name, :client_code)";
        $this->generateUniqueClientCode($name);

        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare($query);

            $stmt->execute([
                ':name'        => $name,
                ':client_code' => $this->clientCode,
            ]);

            if ($stmt->rowCount() > 0) {
                $this->conn->commit();

                return $this->clientCode;
            }
            return false;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            throw new Exception($e->getMessage());
        }
    }

    private function generateUniqueClientCode(string $name): void
    {
        // Normalize client name for code generation
        $namePrefix = $this->extractClientCodePrefix($name);

        // Get the next sequence number
        $sequenceNumber = $this->sequenceManager->getNextSequenceNumber();

        // Format the client code
        $this->clientCode = sprintf(
            '%s%03d',
            $namePrefix,
            $sequenceNumber
        );
    }

    private function extractClientCodePrefix(string $name): string
    {
        $paddingChars = range('A', 'Z');
        // Remove extra spaces and trim
        $cleanName = trim(preg_replace('/\s+/', ' ', $name));

        // Split the name into words
        $words = explode(' ', $cleanName);

        if (count($words) > 1) {
            // For multiple words, take first letter of each word
            $prefix = '';
            foreach ($words as $word) {
                if (! empty($word)) {
                    $prefix .= strtoupper(substr($word, 0, 1));
                }
            }
        } else {
            // For single word, take first three letters
            $prefix = strtoupper(substr($words[0], 0, 3));
        }

        // If prefix is less than 3 characters, pad with rotating A-Z characters
        if (strlen($prefix) < 3) {
            while (strlen($prefix) < 3) {
                $prefix .= $paddingChars[self::$paddingIndex];
                self::$paddingIndex = (self::$paddingIndex + 1) % count($paddingChars);
            }
        }

        // Ensure we only return exactly 3 characters
        return substr($prefix, 0, 3);
    }

    public function list(): array|bool
    {
        try {
            $stmt = $this->conn->query("
                SELECT 
                    clients.client_code,
                    clients.name,
                    COUNT(client_contact.contact_id) AS contact_count
                FROM 
                    clients
                LEFT JOIN 
                    client_contact ON clients.client_code = client_contact.client_code
                GROUP BY 
                    clients.client_code, clients.name
                ORDER BY 
                    name ASC
            ");
            
            $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if(count($clients) == 0) {
                return false;
            }

            return $clients;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function getClientContacts(string $clientCode): array|bool{
        try {
            $contacts = $this->contact->getContactsByClient($clientCode);

            if(count($contacts) == 0) {
                return false;
            }

            return $contacts;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
