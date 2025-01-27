<?php

declare (strict_types = 1);

namespace App\Modules\Entities;

use App\App;
use App\DB;
use Exception;
use PDO;
use PDOException;

class Contact
{
    private string $name;

    private string $surname;

    private string $email;

    private static int $paddingIndex = 0;

    private DB $conn;

    private $table = 'contacts';

    private ClientContact $contact;

    public function __construct()
    {
        $this->conn            = App::db();
        $this->contact = new ClientContact();
    }

    public function save(string $name,string $surname,string $email): bool | string
    {
        $query = "INSERT INTO {$this->table} (name, surname, email) VALUES (:name, :surname, :email)";
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare($query);

            $stmt->execute([
                ':name'        => $name,
                ':surname' => $surname,
                ':email' => $email
            ]);

            

            if ($stmt->rowCount() > 0) {
                $this->conn->commit();

                $stmt = $this->conn->query("SELECT LAST_INSERT_ID()");
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                return (string)$result['LAST_INSERT_ID()'];;
            }
            return false;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            throw new Exception($e->getMessage());
        }
    }
    public function list(): array|bool
    {
        try {
            $stmt = $this->conn->query("
                SELECT 
                    contacts.id,
                    contacts.name,
                    contacts.surname,
                    contacts.email,
                    COUNT(client_contact.client_code) AS client_count
                FROM 
                    contacts
                LEFT JOIN 
                    client_contact ON contacts.id = client_contact.contact_id
                GROUP BY 
                    contacts.id, contacts.name, contacts.surname, contacts.email
                ORDER BY 
                    surname, name ASC
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

    public function getContactClients(int $contactId): array|bool{
        try {
            $clients = $this->contact->getClientsByContact($contactId);

            if(count($clients) == 0) {
                return false;
            }

            return $clients;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function getContactsByEmail(string $email)
    {
        $sql = "
            SELECT contacts.*
            FROM contacts
            WHERE contacts.email = :email
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
