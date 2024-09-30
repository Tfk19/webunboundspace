<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use App\Imports\UsersImport;

class FirebaseController extends Controller
{
    public function index()
    {
        // Inisialisasi Firebase dengan file kredensial dan database URL
        $firebase = (new Factory)
            ->withServiceAccount(base_path('firebase_credentials.json'))
            ->withDatabaseUri('https://unboundspace-d47fb-default-rtdb.firebaseio.com')
            ->createDatabase();

        // Ambil data dari Firebase
        $reference = $firebase->getReference('users');
        $data = $reference->getValue();

        $users = []; // Initialize an empty array to hold users
        $uniqueInstitutions = []; // Array to hold unique institutions

        // Check if data is not null and is an array
        if ($data && is_array($data)) {
            foreach ($data as $userId => $user) {
                // Add user to users array
                $users[$userId] = [
                    'nama' => $user['nama'] ?? 'N/A',
                    'email' => $user['email'] ?? 'N/A',
                    'nohp' => $user['nohp'] ?? 'N/A',
                    'asalInstansi' => $user['asalInstansi'] ?? 'N/A',
                    'status' => $user['status'] ?? 'N/A',
                ];

                // Collect unique institutions, excluding "admin"
                if (isset($user['asalInstansi']) && $user['asalInstansi'] !== 'admin' && !in_array($user['asalInstansi'], $uniqueInstitutions)) {
                    $uniqueInstitutions[] = $user['asalInstansi'];
                }
            }
        }

        // Kirim data ke view (only unique institutions)
        return view('firebase.index', compact('uniqueInstitutions', 'users'));
    }
    public function showUsersByInstansi($asalInstansi)
    {
        // Inisialisasi Firebase dengan file kredensial dan database URL
        $firebase = (new Factory)
            ->withServiceAccount(base_path('firebase_credentials.json'))
            ->withDatabaseUri('https://unboundspace-d47fb-default-rtdb.firebaseio.com')
            ->createDatabase();

        // Ambil data dari Firebase
        $reference = $firebase->getReference('users');
        $data = $reference->getValue();

        $usersFromInstansi = []; // Initialize an empty array for users from the specific instansi

        // Check if data is not null and is an array
        if ($data && is_array($data)) {
            foreach ($data as $userId => $user) {
                if (isset($user['asalInstansi']) && $user['asalInstansi'] === $asalInstansi) {
                    $usersFromInstansi[] = [
                        'nama' => $user['nama'] ?? 'N/A',
                        'ratings' => $user['ratings'] ?? [], // Get user ratings
                        'selectedGroup' => $user['selectedGroup'] ?? 'N/A', // Get selected group
                        'personalityResult' => $user['personalityResult'] ?? ['result' => 'N/A'],
                    ];
                }
            }
        }

        if (empty($usersFromInstansi)) {
            return redirect()->route('firebase.index')->with('error', 'No users found for this institution.');
        }

        // Kirim data ke view
        return view('firebase.userDetails', compact('usersFromInstansi', 'asalInstansi'));
    }

    public function exportUsersByInstansi($asalInstansi)
{
    $firebase = (new Factory)
        ->withServiceAccount(base_path('firebase_credentials.json'))
        ->withDatabaseUri('https://unboundspace-d47fb-default-rtdb.firebaseio.com')
        ->createDatabase();

    $reference = $firebase->getReference('users');
    $data = $reference->getValue();

    $usersFromInstansi = []; // Initialize an empty array for users from the specific instansi

    if ($data && is_array($data)) {
        foreach ($data as $userId => $user) {
            if (isset($user['asalInstansi']) && $user['asalInstansi'] === $asalInstansi) {
                $usersFromInstansi[] = [
                    'nama' => $user['nama'] ?? 'N/A',
                    'selectedGroup' => $user['selectedGroup'] ?? 'N/A',
                    'agility' => $user['ratings']['agility'] ?? 'N/A',
                    'leadership' => $user['ratings']['leadership'] ?? 'N/A',
                    'teamWork' => $user['ratings']['teamWork'] ?? 'N/A',
                    'personalityResult' => $user['personalityResult']['result'] ?? 'N/A',
                ];
            }
        }
    }

    // Export to Excel
    return Excel::download(new UsersExport($usersFromInstansi), 'users_'.$asalInstansi.'.xlsx');
}

public function uploadUsers(Request $request)
{
    $request->validate([
        'excelFile' => 'required|file|mimes:xlsx,xls',
        'asalInstansi' => 'required|string',
    ]);

    // Process the uploaded file
    $file = $request->file('excelFile');

    // Use the UsersImport class to handle the import
    Excel::import(new UsersImport($request->input('asalInstansi')), $file);

    return redirect()->back()->with('success', 'Users uploaded successfully.');
}
}
