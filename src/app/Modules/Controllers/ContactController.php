<?php

declare (strict_types = 1);

namespace App\Modules\Controllers;

use App\Modules\Entities\ClientContact;
use App\Modules\Entities\Contact;
use App\Validators\ContactValidator;
use App\View;
use Exception;

class ContactController extends Controller
{
    private Contact $contact;

    private ClientContact $client;

    public function __construct()
    {
        $this->contact = new Contact();
        $this->client = new ClientContact();
    }

    public function index(): View
    {
        return View::make('contacts/index');
    }

    public function store(): bool | string
    {
        header("Content-Type: application/json;");
        // Get contact request data
        // Get POST data
        $request = json_decode(file_get_contents('php://input'), true);

        try {
            // Validate request
            $validator = new ContactValidator($request, $this->contact);

            if (! $validator->validate()) {
                return $this->jsonResponse([
                    'status'  => 'error',
                    'message' => 'Validation failed',
                    'errors'  => $validator->getErrors(),
                ], 422);
            }

            // Create contact
            $result = $this->contact->save(
                $request['name'],
                $request['surname'],
                $request['email'],
            );

            if ($result) {
                return $this->jsonResponse([
                    'status'  => 'success',
                    'message' => 'Contact created successfully',
                    'data'    => [
                        'id' => $result
                    ],
                ], 201);
            }

            throw new Exception("Failed to create contact");

        } catch (Exception $e) {
            return $this->jsonResponse([
                'status'  => 'error',
                'message' => 'Failed to create contact',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function list(): string | bool
    {
        try {
            // Get contacts
            $contacts = $this->contact->list();

            if ($contacts) {
                return $this->jsonResponse([
                    'status' => 'success',
                    'data'   => $contacts,
                ], 200);
            }

            if (!$contacts) {
                return $this->jsonResponse([
                    'status' => 'success',
                    'data'   => [],
                ], 200);
            }

        } catch (Exception $e) {
            return $this->jsonResponse([
                'status'  => 'error',
                'message' => 'Failed to fetch contacts',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    // Retrieve list of clients linked to a given contact
    public function clients(): string | bool
    {
        header('Content-Type: application/json');

        try {

            // Validate and retrieve contact ID
            if (! isset($_GET['contact_id'])) {
                return $this->jsonResponse([
                    'status'  => 'fail',
                    'message' => 'Invalid contact_id',
                ], 400);
            }

            $contactId = (int)$_GET['contact_id'];

            // Get contact clients
            $clients = $this->contact->getContactClients($contactId);

            if ($clients) {
                return $this->jsonResponse([
                    'status' => 'success',
                    'data'   => $clients,
                ], 200);
            }

            if (!$clients) {
                return $this->jsonResponse([
                    'status' => 'success',
                    'data'   => [],
                ], 200);
            }

            throw new Exception('Failed to fetch clients');

        } catch (Exception $e) {
            return $this->jsonResponse([
                'status'  => 'error',
                'message' => 'Failed to fetch clients',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function link(): bool | string
    {
        header("Content-Type: application/json;");
        // Get client request data
        // Get POST data
        $request = json_decode(file_get_contents('php://input'), true);

        try {

            // Validate input
            if (! isset($request['contact_id']) || ! isset($request['client_codes']) || ! is_array($request['client_codes'])) {
                return $this->jsonResponse([
                    'status'  => 'error',
                    'message' => 'Invalid inputs',
                ], 400);
            }

            $contactId = (int)$request['contact_id'];
            $clientCodes = array_map('strval', $request['client_codes']); // Ensure all IDs are integers

            // Link each client to the contact
            foreach ($clientCodes as $clientCode) {
                $this->client->link($clientCode, $contactId);
            }
            
            if ($contactId) {
                return $this->jsonResponse([
                    'status'  => 'success',
                    'message' => 'Contact linked to client(s) successfully.',
                ], 201);
            }

            throw new Exception("Failed to link clients");

        } catch (Exception $e) {
            return $this->jsonResponse([
                'status'  => 'error',
                'message' => 'Failed to link clients',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
