<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Kreait\Firebase\Database;

class UsersImport implements ToModel
{
    protected $asalInstansi;
    protected $database;

    public function __construct($asalInstansi)
    {
        $this->asalInstansi = $asalInstansi;

        // Initialize Firebase database
        $this->database = (new \Kreait\Firebase\Factory)
            ->withServiceAccount(base_path('firebase_credentials.json'))
            ->withDatabaseUri('https://unboundspace-d47fb-default-rtdb.firebaseio.com')
            ->createDatabase();
    }

    public function model(array $row)
    {
        // Assuming your Excel file has columns for 'nama', 'email', 'nohp', etc.
        $userId = uniqid(); // Generate a unique ID for the user

        // Prepare user data
        $userData = [
            'nama' => $row[0], // Assuming the first column is 'nama'
            'selectedGroup' => $row[1], // Assuming the second column is 'email'
            'asalInstansi' => $this->asalInstansi,
            // Add more fields as necessary
        ];

        // Save user to Firebase
        $this->database->getReference('users/' . $userId)->set($userData);

        return null; // Returning null since we're directly inserting to Firebase
    }
}
