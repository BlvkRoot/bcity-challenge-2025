<?php
declare (strict_types = 1);

namespace App\Modules\Entities;

use App\App;
use App\DB;
use PDO;

class ClientContact
{
    private DB $conn;

    public function __construct()
    {
        $this->conn = App::db();
    }

    public function link(string $clientCode, int $contactId)
    {
        // Check if contact is already linked before inserting
        $contactsLinked = $this->getClientsByContact($contactId);
        if (count($contactsLinked) > 0) {
            return;
        }
        $sql  = "INSERT INTO client_contact (client_code, contact_id) VALUES (:client_code, :contact_id)";
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
}
