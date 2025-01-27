<?php

declare (strict_types = 1);

namespace App\Modules\Controllers;

use App\Modules\Entities\Client;
use App\Modules\Entities\ClientContact;
use App\Validators\ClientValidator;
use App\View;
use Exception;

class ClientController extends Controller
{
    private Client $client;

    private ClientContact $contact;

    public function __construct()
    {
        $this->client  = new Client();
        $this->contact = new ClientContact();
    }

    public function index(): View
    {
        return View::make('clients/index');
    }

    public function store(): bool | string
    {
        header("Content-Type: application/json;");
        // Get client request data
        // Get POST data
        $request = json_decode(file_get_contents('php://input'), true);

        try {
            // Validate request
            $validator = new ClientValidator($request, $this->client);

            if (! $validator->validate()) {
                return $this->jsonResponse([
                    'status'  => 'error',
                    'message' => 'Validation failed',
                    'errors'  => $validator->getErrors(),
                ], 422);
            }

            // Create client
            $clientCode = $this->client->save($request['name']);

            if ($clientCode) {
                return $this->jsonResponse([
                    'status'  => 'success',
                    'message' => 'Client created successfully',
                    'data'    => [
                        'clientCode' => $clientCode,
                        'name'       => $request['name'],
                    ],
                ], 201);
            }

            throw new Exception("Failed to create client");

        } catch (Exception $e) {
            return $this->jsonResponse([
                'status'  => 'error',
                'message' => 'Failed to create client',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function list(): string | bool
    {
        try {
            // Get clients
            $clients = $this->client->list();

            if ($clients) {
                return $this->jsonResponse([
                    'status' => 'success',
                    'data'   => $clients,
                ], 200);
            }

            if (! $clients) {
                return $this->jsonResponse([
                    'status' => 'success',
                    'data'   => [],
                ], 200);
            }

        } catch (Exception $e) {
            return $this->jsonResponse([
                'status'  => 'error',
                'message' => 'Failed to fetch clients',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    // Retrieve list of contacts linked to a given client
    public function contacts(): string | bool
    {
        header('Content-Type: application/json');

        try {
            // Validate and retrieve client ID
            if (! isset($_GET['client_code'])) {
                return $this->jsonResponse([
                    'status'  => 'fail',
                    'message' => 'Invalid client code',
                ], 400);
            }

            $clientCode = $_GET['client_code'];

            // Get client contacts
            $contacts = $this->client->getClientContacts($clientCode);

            if ($contacts) {
                return $this->jsonResponse([
                    'status' => 'success',
                    'data'   => $contacts,
                ], 200);
            }

            if (! $contacts) {
                return $this->jsonResponse([
                    'status' => 'success',
                    'data'   => [],
                ], 200);
            }

            throw new Exception('Failed to fetch contacts');

        } catch (Exception $e) {
            return $this->jsonResponse([
                'status'  => 'error',
                'message' => 'Failed to fetch contacts',
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
            if (! isset($request['client_code']) || ! isset($request['contact_ids']) || ! is_array($request['contact_ids'])) {
                return $this->jsonResponse([
                    'status'  => 'error',
                    'message' => 'Invalid inputs',
                ], 400);
            }

            $clientCode = (string)$request['client_code'];
            $contactIds = array_map('intval', $request['contact_ids']); // Ensure all IDs are integers

            // Link each contact to the client
            foreach ($contactIds as $contactId) {
                $this->contact->link($clientCode, $contactId);
            }

            if ($clientCode) {
                return $this->jsonResponse([
                    'status'  => 'success',
                    'message' => 'Client linked to contacts successfully.',
                ], 201);
            }

            throw new Exception("Failed to link contacts");

        } catch (Exception $e) {
            return $this->jsonResponse([
                'status'  => 'error',
                'message' => 'Failed to link contacts',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function unlink(): bool | string
    {
        header("Content-Type: application/json;");
        // Get client request data
        // Get POST data
        $request = json_decode(file_get_contents('php://input'), true);

        try {

            // Validate input
            if (! isset($request['client_code']) || ! isset($request['contact_id'])) {
                return $this->jsonResponse([
                    'status'  => 'error',
                    'message' => 'Invalid inputs',
                ], 400);
            }

            $clientCode = (string)$request['client_code'];
            $contactId = (int)$request['contact_id']; // Ensure ID is an integer

            // Unlink client from contact
            $result = $this->contact->unlink($clientCode, $contactId);

            if ($result) {
                return $this->jsonResponse([
                    'status'  => 'success',
                    'message' => 'Contact unlinked successfully.',
                ], 201);
            }

            throw new Exception("Failed to unlink contact");

        } catch (Exception $e) {
            return $this->jsonResponse([
                'status'  => 'error',
                'message' => 'Failed to unlink contact',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
