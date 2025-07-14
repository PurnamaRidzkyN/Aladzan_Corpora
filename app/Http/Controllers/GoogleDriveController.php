<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GoogleDriveController extends Controller
{
    public function redirectToGoogle()
    {
        $query = http_build_query([
            'client_id' => env('GOOGLE_DRIVE_CLIENT_ID'),
            'redirect_uri' => url('/oauth2/callback'),
            'response_type' => 'code',
            'scope' => 'https://www.googleapis.com/auth/drive.file',
            'access_type' => 'offline',
            'prompt' => 'consent',
        ]);

        return redirect("https://accounts.google.com/o/oauth2/v2/auth?$query");
    }

    public function handleGoogleCallback(Request $request)
    {
        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'client_id' => env('GOOGLE_DRIVE_CLIENT_ID'),
            'client_secret' => env('GOOGLE_DRIVE_CLIENT_SECRET'),
            'code' => $request->code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => url('/oauth2/callback'),
        ]);

        $data = $response->json();

        // Tampilkan tokennya di browser
        dd($data);
    }
}
