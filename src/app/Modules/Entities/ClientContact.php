<?php
declare (strict_types = 1);

namespace App\Modules\Entities;

use App\App;
use App\DB;
use PDO;

class ClientContact
{
    private DB $conn;

    private $table = 'client_contact';

    public function __construct()
    {
        $this->conn = App::db();
    }

    public function link(string $clientCode, int $contactId)
    {
        $sql  = "INSERT INTO {$this->table} (client_code, contact_id) VALUES (:client_code, :contact_id)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':client_code' => $clientCode, ':contact_id' => $contactId]);
    }

    public function getContactsByClient(string $clientCode)
    {
        $sql = "
            SELECT contacts.*
            FROM contacts
            INNER JOIN client_contact ON contacts.id = client_contact.contact_id
            WHERE client_contact.client_code = :client_code
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':client_code' => $clientCode]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getClientsByContact(int $contactId)
    {
        $sql = "
            SELECT clients.*
            FROM clients
            INNER JOIN client_contact ON clients.client_code = client_contact.client_code
            WHERE client_contact.contact_id = :contact_id
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':contact_id' => $contactId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function unlink(string $clientCode, int $contactId)
    {
        
        $sql  = `DELETE FROM {$this->table} 
                    WHERE {$this->table}.client_code = :contact_id
                    AND {$this->table}.contact_id = :contact_id`;
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':contact_id', $contactId, PDO::PARAM_INT);
        $stmt->bindParam(':client_code', $clientCode, PDO::PARAM_STR);

        return $stmt->execute();
    }
}
